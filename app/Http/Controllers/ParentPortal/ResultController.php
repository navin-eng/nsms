<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Exam;
use App\Models\AcademicYear;

class ResultController extends Controller
{
    public function index()
    {
        $childId = session('active_child_id');
        $child = Student::findOrFail($childId);
        
        // Find published exams for this child's marks
        $examIds = \App\Models\ExamMark::where('student_id', $childId)->pluck('exam_id')->unique();
        
        $exams = Exam::whereIn('id', $examIds)->where('is_published', true)->orderByDesc('start_date')->get();
        $years = AcademicYear::whereIn('id', $exams->pluck('academic_year_id')->unique())->orderByDesc('start_date')->get();
        
        return view('parent.results', compact('child', 'exams', 'years'));
    }

    public function printMarkSheet(Request $request, $exam_id)
    {
        $childId = session('active_child_id');
        $child = Student::findOrFail($childId);
        
        $exam = Exam::where('id', $exam_id)->where('is_published', true)->firstOrFail();
        
        $marks = \App\Models\ExamMark::where('exam_id', $exam->id)
            ->where('student_id', $child->id)
            ->get();
            
        if ($marks->isEmpty()) {
            return back()->with('error', 'No marks found for this exam.');
        }

        // Get class from schedule based on one of the subjects
        $schedule = \App\Models\ExamSchedule::where('exam_id', $exam->id)
            ->where('subject_id', $marks->first()->subject_id)
            ->first();
            
        $class = \App\Models\AcademicClass::findOrFail($schedule->academic_class_id);
        
        $schedules = \App\Models\ExamSchedule::with('subject')
            ->where('exam_id', $exam->id)
            ->where('academic_class_id', $class->id)
            ->get()
            ->sortBy('subject.order_level');

        $marks = $marks->keyBy('subject_id');
        $gradingRules = \App\Models\GradingRule::orderBy('min_percent', 'desc')->get();

        // Pass to the same view used by admin or a copied one for parent
        return view('backend.pages.sms.exams.results.print', compact('exam', 'student', 'class', 'schedules', 'marks', 'gradingRules'))->with('student', $child)->with('studentResult', (object)['rank' => null]);
    }
    
    public function printTranscript(Request $request, $year_id)
    {
        $childId = session('active_child_id');
        $student = Student::findOrFail($childId);
        $year = AcademicYear::findOrFail($year_id);
        
        $exams = Exam::where('academic_year_id', $year->id)
                     ->where('is_published', true)
                     ->orderBy('start_date')
                     ->get();

        if ($exams->isEmpty()) {
            return back()->with('error', 'No published exams found for this academic year.');
        }

        $examIds = $exams->pluck('id');
        
        $marks = \App\Models\ExamMark::whereIn('exam_id', $examIds)
            ->where('student_id', $student->id)
            ->get();
            
        if ($marks->isEmpty()) {
            return back()->with('error', 'No marks found for this year.');
        }

        $schedule = \App\Models\ExamSchedule::whereIn('exam_id', $examIds)
            ->where('subject_id', $marks->first()->subject_id)
            ->first();
            
        $class = \App\Models\AcademicClass::findOrFail($schedule->academic_class_id);

        $schedules = \App\Models\ExamSchedule::with('subject')
            ->whereIn('exam_id', $examIds)
            ->where('academic_class_id', $class->id)
            ->get();
            
        $subjects = $schedules->pluck('subject')->unique('id')->sortBy('order_level');

        $marks = $marks->groupBy('exam_id');
        $gradingRules = \App\Models\GradingRule::orderBy('min_percent', 'desc')->get();

        return view('backend.pages.sms.transcripts.print', compact('year', 'class', 'student', 'exams', 'subjects', 'schedules', 'marks', 'gradingRules'));
    }
}
