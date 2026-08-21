<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $childId = session('active_child_id');
        $child = Student::findOrFail($childId);
        
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        $attendances = StudentAttendance::where('student_id', $childId)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->orderBy('attendance_date', 'asc')
            ->get();
            
        $summary = [
            'present' => $attendances->where('status', 'Present')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'half_day' => $attendances->where('status', 'Half Day')->count(),
            'total' => $attendances->count()
        ];
        
        return view('parent.attendance', compact('child', 'attendances', 'summary', 'month', 'year'));
    }
}
