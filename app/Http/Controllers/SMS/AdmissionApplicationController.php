<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AdmissionApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = AdmissionApplication::with(['academicYear', 'academicClass'])->orderByDesc('application_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->paginate(20)->withQueryString();
        return view('backend.pages.sms.admissions.applications.index', compact('applications'));
    }

    public function create()
    {
        $classes = AcademicClass::orderBy('numeric_value')->get();
        $years = AcademicYear::orderByDesc('start_date')->get();
        return view('backend.pages.sms.admissions.applications.create', compact('classes', 'years'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'academic_year_id' => 'required|exists:academic_years,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20',
            'previous_school' => 'nullable|string|max:255',
            'application_date' => 'required|date',
        ]);
        
        $data['status'] = 'Pending';
        
        $application = AdmissionApplication::create($data);

        return redirect()->route('sms.admissions.applications.show', $application->id)->with('success', 'Application submitted successfully.');
    }

    public function show(AdmissionApplication $application)
    {
        $application->load(['academicYear', 'academicClass', 'documents']);
        return view('backend.pages.sms.admissions.applications.show', compact('application'));
    }

    public function print(AdmissionApplication $application)
    {
        $application->load(['academicYear', 'academicClass']);
        return view('backend.pages.sms.admissions.applications.print', compact('application'));
    }

    public function updateStatus(Request $request, AdmissionApplication $application)
    {
        $request->validate(['status' => 'required|in:Approved,Rejected,Pending']);
        $application->update(['status' => $request->status]);

        return back()->with('success', 'Application status updated.');
    }

    public function destroy(AdmissionApplication $application)
    {
        $application->delete();
        return redirect()->route('sms.admissions.applications.index')->with('success', 'Application deleted.');
    }

    public function enroll(Request $request, AdmissionApplication $application)
    {
        if ($application->status !== 'Approved') {
            return back()->with('error', 'Only approved applications can be enrolled.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Create Guardian
            $guardian = \App\Models\Guardian::create([
                'father_name' => $application->father_name,
                'father_phone' => $application->contact_number,
                'mother_name' => $application->mother_name,
                'guardian_name' => $application->father_name ?? $application->mother_name,
                'guardian_phone' => $application->contact_number,
                'guardian_relation' => $application->father_name ? 'Father' : 'Mother',
            ]);

            // 2. Create Student
            // Generate Admission No (Example logic)
            $lastStudent = \App\Models\Student::latest('id')->first();
            $nextId = $lastStudent ? $lastStudent->id + 1 : 1;
            $admissionNo = 'ADM' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $student = \App\Models\Student::create([
                'admission_no' => $admissionNo,
                'admission_date' => now()->toDateString(),
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'gender' => $application->gender,
                'dob' => $application->dob,
                'previous_school_details' => $application->previous_school,
                'guardian_id' => $guardian->id,
                'status' => 'Active',
            ]);

            // 3. Create Enrollment for the current academic year/class
            \App\Models\Enrollment::create([
                'student_id' => $student->id,
                'academic_year_id' => $application->academic_year_id,
                'academic_class_id' => $application->academic_class_id,
                'enrollment_date' => now()->toDateString(),
            ]);

            // 4. Transfer Documents
            foreach ($application->documents as $doc) {
                \App\Models\StudentDocument::create([
                    'student_id' => $student->id,
                    'title' => $doc->title,
                    'document_path' => $doc->document_path,
                ]);
            }

            // 5. Update Application Status
            $application->update(['status' => 'Enrolled']);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('sms.students.show', $student->id)->with('success', 'Student enrolled successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Enrollment failed: ' . $e->getMessage());
        }
    }
}
