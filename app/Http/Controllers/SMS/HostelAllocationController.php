<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\HostelRoom;
use App\Models\HostelBed;
use App\Models\HostelAllocation;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class HostelAllocationController extends Controller
{
    public function index(Request $request)
    {
        $allocations = HostelAllocation::with(['student', 'bed.room.hostel'])->get();
        $hostels = Hostel::with('rooms.beds')->get();
        $students = Student::all(); // Might want to filter out already allocated students

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.pages.sms.hostel.reports.pdf_allocation', compact('allocations', 'request'));
                return $pdf->download('hostel_allocation_report.pdf');
            } elseif ($request->export === 'excel') {
                return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\HostelAllocationExport($allocations), 'hostel_allocation_report.xlsx');
            } elseif ($request->export === 'print') {
                return view('backend.pages.sms.hostel.reports.pdf_allocation', compact('allocations', 'request'));
            }
        }

        return view('backend.pages.sms.hostel.allocations', compact('allocations', 'hostels', 'students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'hostel_bed_id' => 'required|exists:hostel_beds,id',
            'start_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Check if bed is available
            $bed = HostelBed::findOrFail($data['hostel_bed_id']);
            if ($bed->status !== 'Available') {
                throw new \Exception("The selected bed is not available.");
            }

            // Check if student is already active in a hostel
            $activeAllocation = HostelAllocation::where('student_id', $data['student_id'])->where('status', 'Active')->first();
            if ($activeAllocation) {
                throw new \Exception("This student is already allocated to a hostel bed.");
            }

            $data['status'] = 'Active';
            HostelAllocation::create($data);

            // Update bed status
            $bed->update(['status' => 'Allocated']);

            DB::commit();
            Alert::success('Success', 'Student allocated to hostel bed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
        }

        return back();
    }

    public function vacate(Request $request, $id)
    {
        $allocation = HostelAllocation::findOrFail($id);
        
        $request->validate([
            'end_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $allocation->update([
                'status' => 'Vacated',
                'end_date' => $request->end_date
            ]);

            // Free up the bed
            $allocation->bed->update(['status' => 'Available']);

            DB::commit();
            Alert::success('Success', 'Student vacated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Error', $e->getMessage());
        }

        return back();
    }
}
