<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelAttendance;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class HostelAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $hostels = Hostel::all();
        
        $calendarService = app(\App\Services\CalendarService::class);
        $defaultDateAD = date('Y-m-d');
        $defaultDateDisplay = $calendarService->displayDate($defaultDateAD);
        
        $inputDate = $request->input('date', $defaultDateDisplay);
        $selectedDateDisplay = $inputDate;
        $selectedDateAD = $calendarService->toDbDate($inputDate)?->toDateString() ?? $defaultDateAD;

        $selectedHostelId = $request->input('hostel_id');
        $allocations = collect();
        $attendances = collect();

        if ($selectedHostelId) {
            // Get all active allocations in this hostel on the selected date
            // Assuming start_date <= date and (end_date IS NULL or end_date >= date)
            $allocations = HostelAllocation::with(['student', 'bed.room.hostel'])
                ->whereHas('bed.room', function($q) use ($selectedHostelId) {
                    $q->where('hostel_id', $selectedHostelId);
                })
                ->where('start_date', '<=', $selectedDateAD)
                ->where(function($q) use ($selectedDateAD) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', $selectedDateAD);
                })
                ->get();

            $attendances = HostelAttendance::whereIn('hostel_allocation_id', $allocations->pluck('id'))
                ->whereDate('date', $selectedDateAD)
                ->get()
                ->keyBy('hostel_allocation_id');
        }

        if ($request->has('export')) {
            $attendancesList = $attendances->values(); // Convert map to collection for the export
            if ($request->export === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.sms.hostel.reports.pdf_attendance', ['attendances' => $attendancesList, 'request' => $request]);
                return $pdf->download('hostel_attendance_report.pdf');
            } elseif ($request->export === 'excel') {
                return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\HostelAttendanceExport($attendancesList), 'hostel_attendance_report.xlsx');
            } elseif ($request->export === 'print') {
                return view('backend.pages.sms.hostel.reports.pdf_attendance', ['attendances' => $attendancesList, 'request' => $request]);
            }
        }

        return view('backend.pages.sms.hostel.attendance', compact(
            'hostels', 'selectedHostelId', 'selectedDateDisplay', 'selectedDateAD', 'allocations', 'attendances', 'calendarService'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'hostel_id' => 'required|exists:hostels,id',
            'attendance' => 'nullable|array',
            'attendance.*.status' => 'required|in:Present,Absent,Leave,Late',
            'attendance.*.remarks' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $date = $request->date;
            
            if ($request->has('attendance')) {
                foreach ($request->attendance as $allocationId => $data) {
                    HostelAttendance::updateOrCreate(
                        [
                            'hostel_allocation_id' => $allocationId,
                            'date' => $date
                        ],
                        [
                            'status' => $data['status'],
                            'remarks' => $data['remarks'] ?? null
                        ]
                    );
                }
            }

            DB::commit();
            Alert::success('Success', 'Hostel attendance saved successfully for ' . system_date($date));
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', 'Failed to save attendance: ' . $e->getMessage());
        }

        return back();
    }
}
