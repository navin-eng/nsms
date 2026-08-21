<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Student;
use App\Models\AcademicClass;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['student.currentEnrollment.academicClass', 'issuer'])->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_no', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('admission_no', 'like', "%{$search}%");
                  });
            });
        }

        $certificates = $query->paginate(15);
        $classes = AcademicClass::orderBy('level')->get();

        return view('backend.pages.sms.certificates.index', compact('certificates', 'classes'));
    }

    public function create(Request $request)
    {
        $classes = AcademicClass::orderBy('level')->get();
        $students = Student::with(['currentEnrollment.academicClass', 'currentEnrollment.section'])->orderBy('first_name')->get();
        $selectedStudent = $request->has('student_id') ? Student::find($request->student_id) : null;

        return view('backend.pages.sms.certificates.create', compact('classes', 'students', 'selectedStudent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type'       => 'required|in:character,transfer,bonafide,completion,merit,custom',
            'title'      => 'required|string|max:255',
            'issue_date' => 'required|date',
            'conduct'    => 'nullable|string|max:255',
            'reason'     => 'nullable|string|max:500',
            'remarks'    => 'nullable|string|max:1000',
        ]);

        $metadata = [
            'conduct'     => $request->conduct ?? 'Good and Exemplary',
            'reason'      => $request->reason,
            'remarks'     => $request->remarks,
            'session'     => $request->session_year ?? date('Y'),
            'passed_exam' => $request->passed_exam,
            'division'    => $request->division,
        ];

        $certificate = Certificate::create([
            'student_id' => $request->student_id,
            'type'       => $request->type,
            'title'      => $request->title,
            'issue_date' => $request->issue_date,
            'metadata'   => $metadata,
            'status'     => 'issued',
            'issued_by'  => auth()->id(),
        ]);

        Alert::success('Generated', "Certificate {$certificate->certificate_no} has been issued successfully.");
        return redirect()->route('sms.certificates.print', $certificate->id);
    }

    public function print($id)
    {
        $certificate = Certificate::with(['student.currentEnrollment.academicClass', 'student.currentEnrollment.section', 'issuer'])->findOrFail($id);
        $setting = SiteSetting::first();
        $student = $certificate->student;
        $enrollment = $student->currentEnrollment;

        return view('backend.pages.sms.certificates.print', compact('certificate', 'setting', 'student', 'enrollment'));
    }

    public function revoke(Request $request, $id)
    {
        $request->validate([
            'revocation_reason' => 'required|string|max:500',
        ]);

        $certificate = Certificate::findOrFail($id);
        $certificate->update([
            'status'            => 'revoked',
            'revocation_reason' => $request->revocation_reason,
        ]);

        Alert::warning('Revoked', "Certificate {$certificate->certificate_no} has been marked as revoked.");
        return back();
    }

    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->delete();

        Alert::success('Deleted', 'Certificate deleted successfully.');
        return back();
    }
}
