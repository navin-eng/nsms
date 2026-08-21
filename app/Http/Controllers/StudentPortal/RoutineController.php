<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\TimetableEntry;
use App\Models\Period;

class RoutineController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::with(['enrollments'])->where('user_id', $user->id)->firstOrFail();
        $activeEnrollment = $student->enrollments()->latest()->first();

        $timetable = [];
        $periods = Period::orderBy('start_time')->get();

        if ($activeEnrollment) {
            $entries = TimetableEntry::with(['subject', 'teacher'])
                ->where('academic_class_id', $activeEnrollment->academic_class_id)
                ->where('section_id', $activeEnrollment->section_id)
                ->get();
                
            foreach ($entries as $entry) {
                $timetable[$entry->day_of_week][$entry->period_id] = $entry;
            }
        }

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('student.routine', compact('student', 'timetable', 'periods', 'days', 'activeEnrollment'));
    }
}
