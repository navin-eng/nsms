<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\Enrollment;
use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\Stream;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Student::with(['guardian', 'currentEnrollment.academicClass', 'currentEnrollment.section']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('currentEnrollment', function($q) use ($request) {
                $q->where('academic_class_id', $request->class_id);
            });
        }

        $students = $query->latest()->get();
        $classes = AcademicClass::all();

        return view('backend.pages.sms.students.index', compact('students', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = AcademicClass::all();
        $sections = Section::all();
        $streams = Stream::all();
        $academicYears = AcademicYear::all();
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('backend.pages.sms.students.create', compact('classes', 'sections', 'streams', 'academicYears', 'activeYear'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'admission_no' => 'required|string|unique:students,admission_no',
            'academic_year_id' => 'required|exists:academic_years,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Guardian
            $guardian = Guardian::create($request->only([
                'father_name', 'father_phone', 'father_occupation',
                'mother_name', 'mother_phone', 'mother_occupation',
                'guardian_name', 'guardian_relation', 'guardian_phone', 'guardian_email', 'guardian_address'
            ]));

            // 2. Upload Photo if any
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('students/photos', 'public');
            }

            // 3. Create Student
            $student = Student::create([
                'guardian_id' => $guardian->id,
                'admission_no' => $request->admission_no,
                'admission_date' => $request->admission_date ?? now(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'blood_group' => $request->blood_group,
                'religion' => $request->religion,
                'category' => $request->category,
                'current_address' => $request->current_address,
                'permanent_address' => $request->permanent_address,
                'photo' => $photoPath,
                'previous_school_details' => $request->previous_school_details,
                'status' => 'Active',
            ]);

            // 4. Create Initial Enrollment
            Enrollment::create([
                'student_id' => $student->id,
                'academic_year_id' => $request->academic_year_id,
                'academic_class_id' => $request->academic_class_id,
                'section_id' => $request->section_id,
                'stream_id' => $request->stream_id,
                'roll_no' => $request->roll_no,
                'status' => 'Continuing',
            ]);

            DB::commit();
            return redirect()->route('sms.students.index')->with('success', 'Student admitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $student->load(['guardian', 'enrollments.academicYear', 'enrollments.academicClass', 'enrollments.section', 'documents']);
        return view('backend.pages.sms.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $classes = AcademicClass::all();
        $sections = Section::all();
        $streams = Stream::all();
        $academicYears = AcademicYear::all();
        
        return view('backend.pages.sms.students.edit', compact('student', 'classes', 'sections', 'streams', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'admission_no' => 'required|string|unique:students,admission_no,' . $student->id,
        ]);

        DB::beginTransaction();
        try {
            // Update Guardian
            $student->guardian->update($request->only([
                'father_name', 'father_phone', 'father_occupation',
                'mother_name', 'mother_phone', 'mother_occupation',
                'guardian_name', 'guardian_relation', 'guardian_phone', 'guardian_email', 'guardian_address'
            ]));

            // Update Photo
            if ($request->hasFile('photo')) {
                if ($student->photo && \Storage::disk('public')->exists($student->photo)) {
                    \Storage::disk('public')->delete($student->photo);
                }
                $student->photo = $request->file('photo')->store('students/photos', 'public');
            }

            // Update Student
            $student->update([
                'admission_no' => $request->admission_no,
                'admission_date' => $request->admission_date,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'blood_group' => $request->blood_group,
                'religion' => $request->religion,
                'category' => $request->category,
                'current_address' => $request->current_address,
                'permanent_address' => $request->permanent_address,
                'previous_school_details' => $request->previous_school_details,
                'status' => $request->status ?? $student->status,
            ]);

            // Update Current Enrollment (for simplicity, update the latest one if year is provided)
            if ($request->filled('academic_year_id')) {
                $enrollment = Enrollment::where('student_id', $student->id)
                                        ->where('academic_year_id', $request->academic_year_id)
                                        ->first();
                if ($enrollment) {
                    $enrollment->update([
                        'academic_class_id' => $request->academic_class_id,
                        'section_id' => $request->section_id,
                        'stream_id' => $request->stream_id,
                        'roll_no' => $request->roll_no,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sms.students.show', $student->id)->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        if ($student->photo && \Storage::disk('public')->exists($student->photo)) {
            \Storage::disk('public')->delete($student->photo);
        }
        $student->delete();
        return redirect()->route('sms.students.index')->with('success', 'Student deleted successfully.');
    }

    /**
     * Print the student profile/admission form.
     */
    public function print(Student $student)
    {
        $student->load(['guardian', 'enrollments.academicYear', 'enrollments.academicClass', 'enrollments.section']);
        $siteSetting = \App\Models\SiteSetting::first();
        return view('backend.pages.sms.students.print', compact('student', 'siteSetting'));
    }
}
