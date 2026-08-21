<?php

namespace App\Http\Controllers\StudentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Exam;
use App\Models\AcademicYear;

class ResultController extends Controller
{
    public function results()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $examIds = \App\Models\ExamMark::where('student_id', $student->id)->pluck('exam_id')->unique();
        
        $exams = Exam::whereIn('id', $examIds)->where('is_published', true)->orderByDesc('start_date')->get();
        $years = AcademicYear::whereIn('id', $exams->pluck('academic_year_id')->unique())->orderByDesc('start_date')->get();
        
        return view('student.results', compact('student', 'exams', 'years'));
    }

    public function exams()
    {
        return $this->results();
    }
}
