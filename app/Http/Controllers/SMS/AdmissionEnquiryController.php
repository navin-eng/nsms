<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\AdmissionEnquiry;
use App\Models\AcademicClass;
use Illuminate\Http\Request;

class AdmissionEnquiryController extends Controller
{
    public function index()
    {
        $enquiries = AdmissionEnquiry::with('academicClass')->orderByDesc('enquiry_date')->get();
        $classes = AcademicClass::orderBy('numeric_value')->get();
        return view('backend.pages.sms.admissions.enquiries.index', compact('enquiries', 'classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'academic_class_id' => 'nullable|exists:academic_classes,id',
            'source' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'enquiry_date' => 'required|date',
        ]);
        
        $data['status'] = 'Open';

        AdmissionEnquiry::create($data);

        return back()->with('success', 'Enquiry recorded successfully.');
    }

    public function update(Request $request, AdmissionEnquiry $enquiry)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'academic_class_id' => 'nullable|exists:academic_classes,id',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Followed Up,Closed',
            'notes' => 'nullable|string',
            'enquiry_date' => 'required|date',
        ]);

        $enquiry->update($data);

        return back()->with('success', 'Enquiry updated successfully.');
    }

    public function destroy(AdmissionEnquiry $enquiry)
    {
        $enquiry->delete();
        return back()->with('success', 'Enquiry deleted successfully.');
    }
}
