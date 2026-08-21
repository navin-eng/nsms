<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\StudentAttendance;
use App\Models\ExamResult;
use App\Models\Notice;

class DashboardController extends Controller
{
    public function setChildContext(Request $request)
    {
        $request->validate(['child_id' => 'required|exists:students,id']);
        
        $user = Auth::user();
        $guardian = Guardian::where('user_id', $user->id)->first();
        
        if ($guardian) {
            $child = Student::where('id', $request->child_id)->where('guardian_id', $guardian->id)->first();
            if ($child) {
                session(['active_child_id' => $child->id]);
            }
        }
        
        return back();
    }

    public function index()
    {
        $childId = session('active_child_id');
        $child = Student::with('currentEnrollment.academicClass', 'currentEnrollment.section')->findOrFail($childId);
        
        // Fetch brief summary for dashboard
        // Attendance today
        $attendanceToday = StudentAttendance::where('student_id', $childId)
            ->whereDate('attendance_date', today())
            ->first();
            
        // Recent Notices
        $notices = Notice::latest()->take(5)->get();
        
        // Fee Summary (outstanding)
        $outstandingFees = \App\Models\FeeInvoice::where('student_id', $childId)
            ->where('status', '!=', 'Paid')
            ->sum('net_amount'); // Simplified, actual logic might involve partial payments
            
        // Or if we can just pass the child object
        
        return view('parent.dashboard', compact('child', 'attendanceToday', 'notices', 'outstandingFees'));
    }
}
