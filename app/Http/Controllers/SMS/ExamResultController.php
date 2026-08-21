<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    public function index(Request $request)
    {
        $exams = \App\Models\Exam::latest()->get();
        $classes = \App\Models\AcademicClass::orderBy('level')->get();

        $selectedExam = null;
        $selectedClass = null;
        $results = collect();
        $schedules = collect();

        if ($request->has('exam_id') && $request->has('academic_class_id')) {
            $selectedExam = \App\Models\Exam::find($request->exam_id);
            $selectedClass = \App\Models\AcademicClass::find($request->academic_class_id);

            if ($selectedClass) {
                // Get schedules for total possible marks calculation
                $schedules = \App\Models\ExamSchedule::where('exam_id', $selectedExam->id)
                    ->where('academic_class_id', $selectedClass->id)
                    ->get();

                $totalTheory = $schedules->sum('theory_full_marks');
                $totalPractical = $schedules->sum('practical_full_marks');
                $grandTotalMax = $totalTheory + $totalPractical;

                $students = \App\Models\Student::whereHas('enrollments', function ($q) use ($selectedClass) {
                    $q->where('academic_class_id', $selectedClass->id)
                        ->whereIn('status', ['Continuing', 'Promoted', 'New']);
                })->orderBy('first_name')->get();

                $gradingRules = \App\Models\GradingRule::orderBy('min_percent', 'desc')->get();

                foreach ($students as $student) {
                    $marks = \App\Models\ExamMark::where('exam_id', $selectedExam->id)
                        ->where('student_id', $student->id)
                        ->get();

                    $obtainedTheory = $marks->sum('theory_marks');
                    $obtainedPractical = $marks->sum('practical_marks');
                    $obtainedTotal = $obtainedTheory + $obtainedPractical;

                    $percentage = $grandTotalMax > 0 ? ($obtainedTotal / $grandTotalMax) * 100 : 0;

                    // Determine Grade & GPA
                    $finalGrade = '-';
                    $finalGpa = 0;

                    foreach ($gradingRules as $rule) {
                        if ($percentage >= $rule->min_percent && $percentage <= $rule->max_percent) {
                            $finalGrade = $rule->grade_name;
                            $finalGpa = $rule->grade_point;
                            break;
                        }
                    }

                    $results->push((object) [
                        'student' => $student,
                        'total_marks' => $obtainedTotal,
                        'percentage' => $percentage,
                        'gpa' => $finalGpa,
                        'grade' => $finalGrade,
                        'rank' => null // will be assigned below
                    ]);
                }

                // Sort by percentage descending and assign dense rank
                $results = $results->sortByDesc('percentage')->values();
                $currentRank = 1;
                $previousPercentage = -1;

                foreach ($results as $index => $result) {
                    if ($index === 0) {
                        $result->rank = $currentRank;
                    } else {
                        if ($result->percentage < $previousPercentage) {
                            $currentRank++;
                        }
                        $result->rank = $currentRank;
                    }
                    $previousPercentage = $result->percentage;
                }
            }
        }

        return view('backend.pages.sms.exams.results.index', compact('exams', 'classes', 'selectedExam', 'selectedClass', 'results', 'schedules'));
    }

    public function printMarkSheet(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:students,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
        ]);

        $exam = \App\Models\Exam::findOrFail($request->exam_id);
        $student = \App\Models\Student::findOrFail($request->student_id);
        $class = \App\Models\AcademicClass::findOrFail($request->academic_class_id);

        $schedules = \App\Models\ExamSchedule::with('subject')->where('exam_id', $exam->id)
            ->where('academic_class_id', $class->id)
            ->get()
            // Important: We sort by subject order_level
            ->sortBy('subject.order_level');

        $marks = \App\Models\ExamMark::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->get()
            ->keyBy('subject_id');

        $gradingRules = \App\Models\GradingRule::orderBy('min_percent', 'desc')->get();

        return view('backend.pages.sms.exams.results.print', compact('exam', 'student', 'class', 'schedules', 'marks', 'gradingRules'));
    }

    public function publish(Request $request, $id)
    {
        $exam = \App\Models\Exam::findOrFail($id);

        // Toggle publish status
        $exam->is_published = !$exam->is_published;
        $exam->save();

        $status = $exam->is_published ? 'published' : 'unpublished';
        return back()->with('success', "Exam results have been successfully {$status}.");
    }
}
