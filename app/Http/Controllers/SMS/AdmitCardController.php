<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\ExamSchedule;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdmitCardController extends Controller
{
    public function index(Request $request)
    {
        $exams = Exam::with('academicYear')->orderByDesc('start_date')->get();
        $classes = AcademicClass::orderBy('level')->get();
        $sections = Section::orderBy('name')->get();
        $setting = SiteSetting::first();

        $selectedExamId = $request->input('exam_id');
        $selectedClassId = $request->input('class_id');
        $selectedSectionId = $request->input('section_id');

        $students = collect();
        $schedules = collect();
        $exam = null;
        $class = null;

        if ($selectedExamId && $selectedClassId) {
            $exam = Exam::with('academicYear')->find($selectedExamId);
            $class = AcademicClass::find($selectedClassId);

            // Fetch schedules for this exam and class
            $schedules = ExamSchedule::with('subject')
                ->where('exam_id', $selectedExamId)
                ->where('academic_class_id', $selectedClassId)
                ->orderBy('exam_date')
                ->orderBy('start_time')
                ->get();

            // Fetch students
            $query = Student::with(['currentEnrollment.academicClass', 'currentEnrollment.section'])
                ->whereHas('enrollments', function ($q) use ($selectedClassId, $selectedSectionId) {
                    $q->where('academic_class_id', $selectedClassId);
                    if ($selectedSectionId) {
                        $q->where('section_id', $selectedSectionId);
                    }
                });

            if ($request->filled('student_id')) {
                $query->where('id', $request->student_id);
            }

            $students = $query->orderBy('first_name')->get();
        }

        $layout = $request->input('layout', 'a4_multiple');

        if ($request->has('print') && $students->isNotEmpty()) {
            return view('backend.pages.sms.admit_cards.print', compact('exam', 'class', 'students', 'schedules', 'setting', 'layout'));
        }

        return view('backend.pages.sms.admit_cards.index', compact('exams', 'classes', 'sections', 'students', 'schedules', 'exam', 'class', 'setting', 'selectedExamId', 'selectedClassId', 'selectedSectionId', 'layout'));
    }
}
