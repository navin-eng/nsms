<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\ClassSubjectAssignment;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassSubjectAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $classes       = AcademicClass::with('stream')->orderBy('numeric_value')->get();
        $subjects      = Subject::orderBy('name')->get();
        $sections      = Section::with('academicClass')->orderBy('name')->get();

        // Get staff members whose linked user account has the "Teacher" role
        $teacherUserIds = \DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'Teacher')
            ->pluck('model_has_roles.model_id');

        $teachers = Staff::whereIn('user_id', $teacherUserIds)
                         ->where('status', 'Active')
                         ->orderBy('first_name')
                         ->get();

        // Filter assignments
        $query = ClassSubjectAssignment::with(['academicYear', 'academicClass', 'section', 'subject', 'staff']);

        if ($request->filled('year_id')) {
            $query->where('academic_year_id', $request->year_id);
        }
        if ($request->filled('class_id')) {
            $query->where('academic_class_id', $request->class_id);
        }

        $assignments = $query->orderBy('academic_class_id')->paginate(20)->withQueryString();

        // Default active year for the form
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('backend.pages.sms.assignments.index', compact(
            'academicYears', 'classes', 'subjects', 'sections', 'teachers', 'assignments', 'activeYear'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id'  => 'required|exists:academic_years,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'section_id'        => 'nullable|exists:sections,id',
            'subject_id'        => 'required|exists:subjects,id',
            'staff_id'          => 'nullable|exists:staff,id',
            'weekly_periods'    => 'nullable|integer|min:1|max:50',
        ]);

        // Check duplicate
        $exists = ClassSubjectAssignment::where([
            'academic_year_id'  => $request->academic_year_id,
            'academic_class_id' => $request->academic_class_id,
            'section_id'        => $request->section_id ?: null,
            'subject_id'        => $request->subject_id,
        ])->exists();

        if ($exists) {
            return back()->with('error', 'This subject is already assigned to this class for the selected year.');
        }

        ClassSubjectAssignment::create($request->only([
            'academic_year_id', 'academic_class_id', 'section_id', 'subject_id', 'staff_id', 'weekly_periods'
        ]));

        return back()->with('success', 'Subject assigned successfully.');
    }

    public function update(Request $request, ClassSubjectAssignment $assignment)
    {
        $request->validate([
            'staff_id'       => 'nullable|exists:staff,id',
            'weekly_periods' => 'nullable|integer|min:1|max:50',
        ]);

        $assignment->update($request->only(['staff_id', 'weekly_periods']));
        return back()->with('success', 'Assignment updated successfully.');
    }

    public function destroy(ClassSubjectAssignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Assignment removed successfully.');
    }

    /**
     * AJAX: Get sections for a given class
     */
    public function sectionsByClass(AcademicClass $academicClass)
    {
        $sections = Section::whereHas('academicClasses', fn($q) => $q->where('academic_classes.id', $academicClass->id))->get(['sections.id', 'sections.name']);
        return response()->json($sections);
    }

    /**
     * AJAX: Get subjects for a given class
     */
    public function subjectsByClass(AcademicClass $academicClass)
    {
        $subjects = $academicClass->subjects()->get(['subjects.id', 'subjects.name', 'subjects.code']);
        return response()->json($subjects);
    }
}
