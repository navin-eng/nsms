<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Department;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class IdCardController extends Controller
{
    public function students(Request $request)
    {
        $classes = AcademicClass::orderBy('level')->get();
        $sections = Section::orderBy('name')->get();
        $setting = SiteSetting::first();

        $selectedClassId = $request->input('class_id');
        $selectedSectionId = $request->input('section_id');
        $layout = $request->input('layout', 'portrait'); // portrait or landscape
        $template = $request->input('template', 'modern'); // modern, classic, elegant

        $students = collect();
        if ($selectedClassId) {
            $query = Student::with(['currentEnrollment.academicClass', 'currentEnrollment.section', 'guardian'])
                ->whereHas('enrollments', function ($q) use ($selectedClassId, $selectedSectionId) {
                    $q->where('academic_class_id', $selectedClassId);
                    if ($selectedSectionId) {
                        $q->where('section_id', $selectedSectionId);
                    }
                });

            if ($request->filled('student_id')) {
                $query->where('id', $request->student_id);
            }

            if ($request->filled('student_ids') && is_array($request->student_ids)) {
                $query->whereIn('id', $request->student_ids);
            }

            $students = $query->orderBy('first_name')->get();
        }

        $customTemplates = \App\Models\IdCardTemplate::where('type', 'student')->get();
        $selectedTemplateId = $request->input('template_id');
        $printFormat = $request->input('print_format', 'a4'); // a4 or id_printer

        if ($request->has('print') && $students->isNotEmpty()) {
            $customTemplate = null;
            if (is_numeric($selectedTemplateId)) {
                $customTemplate = \App\Models\IdCardTemplate::find($selectedTemplateId);
            }
            return view('backend.pages.sms.id_cards.print_students', compact('students', 'setting', 'layout', 'template', 'customTemplate', 'printFormat'));
        }

        return view('backend.pages.sms.id_cards.students', compact('classes', 'sections', 'students', 'setting', 'selectedClassId', 'selectedSectionId', 'layout', 'template', 'customTemplates', 'selectedTemplateId', 'printFormat'));
    }

    public function apiStudents(Request $request)
    {
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
        if (!$classId) {
            return response()->json([]);
        }
        $query = Student::with(['currentEnrollment.academicClass', 'currentEnrollment.section'])
            ->whereHas('enrollments', function ($q) use ($classId, $sectionId) {
                $q->where('academic_class_id', $classId);
                if ($sectionId) {
                    $q->where('section_id', $sectionId);
                }
            });
        $students = $query->orderBy('first_name')->get()->map(function ($st) {
            return [
                'id' => $st->id,
                'name' => $st->full_name,
                'admission_no' => $st->admission_no,
                'roll_number' => $st->currentEnrollment?->roll_number
            ];
        });
        return response()->json($students);
    }

    public function staff(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        $setting = SiteSetting::first();
        $layout = $request->input('layout', 'portrait');

        $query = Staff::with(['department', 'designation'])->where('status', 'active');
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        $staffMembers = $query->orderBy('first_name')->get();

        if ($request->has('print') && $staffMembers->isNotEmpty()) {
            return view('backend.pages.sms.id_cards.print_staff', compact('staffMembers', 'setting', 'layout'));
        }

        return view('backend.pages.sms.id_cards.staff', compact('departments', 'staffMembers', 'setting', 'layout'));
    }
}
