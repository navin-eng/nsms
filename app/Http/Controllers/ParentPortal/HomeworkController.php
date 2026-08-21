<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Homework;
use Illuminate\Support\Facades\Session;

class HomeworkController extends Controller
{
    public function index()
    {
        $guardian = Guardian::where('user_id', auth()->id())->first();
        if (!$guardian) {
            return redirect()->route('parent.dashboard')->with('error', 'Guardian profile not found.');
        }

        $activeStudentId = Session::get('parent_active_student_id');
        $student = Student::where('id', $activeStudentId)->where('guardian_id', $guardian->id)->first();

        if (!$student) {
            $student = $guardian->students()->first();
        }

        $activeEnrollment = $student ? $student->currentEnrollment : null;

        $homeworks = collect();
        if ($activeEnrollment && $student) {
            $homeworks = Homework::with(['subject', 'submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
                ->where('class_id', $activeEnrollment->academic_class_id)
                ->where('section_id', $activeEnrollment->section_id)
                ->latest()
                ->get();
        }

        return view('parent.homework', compact('guardian', 'student', 'activeEnrollment', 'homeworks'));
    }
}
