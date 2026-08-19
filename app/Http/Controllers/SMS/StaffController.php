<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::with(['department', 'designation']);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('designation_id')) {
            $query->where('designation_id', $request->designation_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staffs = $query->orderBy('first_name')->paginate(20);
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();

        return view('backend.pages.sms.staff.directory.index', compact('staffs', 'departments', 'designations'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        return view('backend.pages.sms.staff.directory.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string|unique:staff,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = $request->except(['photo', 'resume']);
        $data['show_on_website'] = $request->has('show_on_website');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('staff/photos', 'public');
        }
        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('staff/resumes', 'public');
        }

        Staff::create($data);

        return redirect()->route('sms.staff.index')->with('success', 'Staff member registered successfully.');
    }

    public function show(Staff $staff)
    {
        $staff->load(['department', 'designation', 'documents']);
        return view('backend.pages.sms.staff.directory.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        return view('backend.pages.sms.staff.directory.edit', compact('staff', 'departments', 'designations'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'employee_id' => 'required|string|unique:staff,employee_id,' . $staff->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = $request->except(['photo', 'resume']);
        $data['show_on_website'] = $request->has('show_on_website');

        if ($request->hasFile('photo')) {
            if ($staff->photo) Storage::disk('public')->delete($staff->photo);
            $data['photo'] = $request->file('photo')->store('staff/photos', 'public');
        }
        if ($request->hasFile('resume')) {
            if ($staff->resume) Storage::disk('public')->delete($staff->resume);
            $data['resume'] = $request->file('resume')->store('staff/resumes', 'public');
        }

        $staff->update($data);

        return redirect()->route('sms.staff.show', $staff->id)->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        if ($staff->photo) Storage::disk('public')->delete($staff->photo);
        if ($staff->resume) Storage::disk('public')->delete($staff->resume);
        
        foreach ($staff->documents as $doc) {
            Storage::disk('public')->delete($doc->document_path);
            $doc->delete();
        }

        $staff->delete();
        return redirect()->route('sms.staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
