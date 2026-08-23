<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelAttendance;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HostelAttendanceExport;
use App\Exports\HostelAllocationExport;
use Carbon\Carbon;

class HostelReportController extends Controller
{
    public function attendance(Request $request)
    {
        $hostels = Hostel::all();
        $query = HostelAttendance::with(['student', 'hostel']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            // Default to current month
            $query->whereBetween('date', [now()->startOfMonth()->format('Y-m-d'), now()->endOfMonth()->format('Y-m-d')]);
        }

        if ($request->filled('hostel_id')) {
            $query->where('hostel_id', $request->hostel_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                $pdf = Pdf::loadView('backend.pages.sms.hostel.reports.pdf_attendance', compact('attendances', 'request'));
                return $pdf->download('hostel_attendance_report.pdf');
            } elseif ($request->export === 'excel') {
                return Excel::download(new HostelAttendanceExport($attendances), 'hostel_attendance_report.xlsx');
            } elseif ($request->export === 'print') {
                return view('backend.pages.sms.hostel.reports.pdf_attendance', compact('attendances', 'request'));
            }
        }

        return view('backend.pages.sms.hostel.reports.attendance', compact('attendances', 'hostels'));
    }

    public function allocation(Request $request)
    {
        $hostels = Hostel::all();
        $query = HostelAllocation::with(['student', 'bed.room.hostel']);

        if ($request->filled('hostel_id')) {
            $query->whereHas('bed.room', function($q) use ($request) {
                $q->where('hostel_id', $request->hostel_id);
            });
        }

        if ($request->filled('room_type')) {
            $query->whereHas('bed.room', function($q) use ($request) {
                $q->where('room_type', $request->room_type);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $allocations = $query->orderBy('created_at', 'desc')->get();

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                $pdf = Pdf::loadView('backend.pages.sms.hostel.reports.pdf_allocation', compact('allocations', 'request'));
                return $pdf->download('hostel_allocation_report.pdf');
            } elseif ($request->export === 'excel') {
                return Excel::download(new HostelAllocationExport($allocations), 'hostel_allocation_report.xlsx');
            } elseif ($request->export === 'print') {
                return view('backend.pages.sms.hostel.reports.pdf_allocation', compact('allocations', 'request'));
            }
        }

        return view('backend.pages.sms.hostel.reports.allocation', compact('allocations', 'hostels'));
    }
}
