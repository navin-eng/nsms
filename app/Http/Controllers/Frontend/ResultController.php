<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $exams = Exam::where('is_published', true)->orderBy('created_at', 'desc')->get();
        return view('frontend.pages.results', compact('exams'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'symbol_number' => 'required|string',
        ]);

        $exam = Exam::findOrFail($request->exam_id);
        
        if (!$exam->is_published) {
            return back()->with('error', 'Results for this exam have not been published yet.');
        }
        
        $exams = Exam::where('is_published', true)->orderBy('created_at', 'desc')->get();

        $student = \App\Models\Student::where('registration_number', $request->symbol_number)->first();
        
        if (!$student) {
            return back()->with('error', 'No student found with the provided Registration/Symbol Number.');
        }

        $marks = \App\Models\ExamMark::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->get();

        if ($marks->isEmpty()) {
            return back()->with('error', 'No results published for this student in the selected exam.');
        }
        
        // Find the student's class for this exam based on schedules they have marks for
        $subjectIds = $marks->pluck('subject_id');
        $schedule = \App\Models\ExamSchedule::where('exam_id', $exam->id)
            ->whereIn('subject_id', $subjectIds)
            ->first();
            
        $classId = $schedule ? $schedule->academic_class_id : null;
        
        $schedules = collect();
        if ($classId) {
            $schedules = \App\Models\ExamSchedule::with('subject')
                ->where('exam_id', $exam->id)
                ->where('academic_class_id', $classId)
                ->get()
                ->sortBy('subject.order_level');
        }

        $marks = $marks->keyBy('subject_id');
        $gradingRules = \App\Models\GradingRule::orderBy('min_percent', 'desc')->get();

        return view('frontend.pages.results', compact('exams', 'exam', 'student', 'marks', 'schedules', 'gradingRules'));
    }
}
