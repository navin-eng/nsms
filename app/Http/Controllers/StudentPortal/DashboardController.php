<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Notice;
use App\Models\StudentAttendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        // Notices
        $notices = Notice::latest()->take(5)->get();
        
        // Today's attendance
        $attendanceToday = StudentAttendance::where('student_id', $student->id)
            ->whereDate('date', Carbon::today())
            ->first();

        // Active enrollment
        $activeEnrollment = $student->enrollments()->latest()->first();
        
        // Due Homework
        $dueHomeworks = collect();
        if ($activeEnrollment) {
            $dueHomeworks = \App\Models\Homework::where('class_id', $activeEnrollment->academic_class_id)
                ->where('section_id', $activeEnrollment->section_id)
                ->whereDate('due_date', '>=', Carbon::today())
                ->latest()
                ->take(3)
                ->get();
        }

        // Gamification & Enhancements
        $hour = Carbon::now()->hour;
        $greeting = 'Good Evening';
        if ($hour < 12) $greeting = 'Good Morning';
        elseif ($hour < 17) $greeting = 'Good Afternoon';

        $totalDays = StudentAttendance::where('student_id', $student->id)->count();
        $presentDays = StudentAttendance::where('student_id', $student->id)->where('status', 'Present')->count();
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

        $kudos = \App\Models\StudentKudo::where('student_id', $student->id)->latest()->take(3)->get();

        return view('student.dashboard', compact('student', 'notices', 'attendanceToday', 'activeEnrollment', 'dueHomeworks', 'greeting', 'attendancePercentage', 'kudos'));
    }
}
