<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\Student;
use App\Models\ExamSchedule;
use App\Models\ExamMark;
use App\Models\GradingRule;
use Illuminate\Http\Request;

class TranscriptController extends Controller
{
    public function index(Request $request)
    {
        $years = AcademicYear::orderByDesc('start_date')->get();
        $classes = AcademicClass::orderBy('level')->get();

        $selectedYear = null;
        $selectedClass = null;
        $students = collect();

        if ($request->has('academic_year_id') && $request->has('academic_class_id')) {
            $selectedYear = AcademicYear::find($request->academic_year_id);
            $selectedClass = AcademicClass::find($request->academic_class_id);

            if ($selectedClass) {
                // Fetch students enrolled in this class
                $students = Student::whereHas('enrollments', function ($q) use ($selectedClass) {
                    $q->where('academic_class_id', $selectedClass->id)
                        ->whereIn('status', ['Continuing', 'Promoted', 'New']);
                })->orderBy('first_name')->get();
            }
        }

        return view('backend.pages.sms.transcripts.index', compact('years', 'classes', 'selectedYear', 'selectedClass', 'students'));
    }

    public function print(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'student_id' => 'required|exists:students,id',
        ]);

        $year = AcademicYear::findOrFail($request->academic_year_id);
        $class = AcademicClass::findOrFail($request->academic_class_id);
        $student = Student::findOrFail($request->student_id);

        // Fetch all published exams for this academic year
        $exams = Exam::where('academic_year_id', $year->id)
            ->where('is_published', true)
            ->orderBy('start_date')
            ->get();

        if ($exams->isEmpty()) {
            return back()->with('error', 'No published exams found for this academic year.');
        }

        $examIds = $exams->pluck('id');

        // Fetch all schedules for these exams for this class
        $schedules = ExamSchedule::with('subject')
            ->whereIn('exam_id', $examIds)
            ->where('academic_class_id', $class->id)
            ->get();

        // Get unique subjects across all exams, sorted by order_level
        $subjects = $schedules->pluck('subject')->unique('id')->sortBy('order_level');

        // Fetch all marks for this student across these exams
        $marks = ExamMark::whereIn('exam_id', $examIds)
            ->where('student_id', $student->id)
            ->get()
            ->groupBy('exam_id');

        $gradingRules = GradingRule::orderBy('min_percent', 'desc')->get();

        return view('backend.pages.sms.transcripts.print', compact('year', 'class', 'student', 'exams', 'subjects', 'schedules', 'marks', 'gradingRules'));
    }
}
