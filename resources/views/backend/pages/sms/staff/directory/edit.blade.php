@extends('backend.pages.layout.master')
@push('b-title', 'Edit Staff')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Edit Staff Profile</h3>
            <p class="text-muted mb-0">Update information for {{ $staff->full_name }}.</p>
        </div>
        <div>
            <a href="{{ route('sms.staff.show', $staff->id) }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-eye"></i> View Profile
            </a>
            <a href="{{ route('sms.staff.index') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left"></i> Back to Directory
            </a>
        </div>
    </div>

    <form action="{{ route('sms.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="card-title m-0 fw-bold"><i class="bi bi-person text-primary me-2"></i>Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $staff->first_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $staff->last_name) }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $staff->phone) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $staff->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $staff->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $staff->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob', $staff->dob) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Marital Status</label>
                                <select name="marital_status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="Single" {{ old('marital_status', $staff->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('marital_status', $staff->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Current Address</label>
                                <input type="text" name="current_address" class="form-control" value="{{ old('current_address', $staff->current_address) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Permanent Address</label>
                                <input type="text" name="permanent_address" class="form-control" value="{{ old('permanent_address', $staff->permanent_address) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Emergency Contact Name/Number</label>
                                <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $staff->emergency_contact) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic & Professional -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="card-title m-0 fw-bold"><i class="bi bi-mortarboard text-primary me-2"></i>Academic & Professional</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Qualification (Degrees/Certifications)</label>
                                <textarea name="qualification" class="form-control" rows="2">{{ old('qualification', $staff->qualification) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Work Experience</label>
                                <textarea name="experience" class="form-control" rows="2">{{ old('experience', $staff->experience) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Update Resume/CV (PDF, Word)</label>
                                <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                                @if($staff->resume)
                                    <div class="mt-2 small">
                                        <a href="{{ asset('storage/' . $staff->resume) }}" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i> Current Resume</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Employment Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="card-title m-0 fw-bold"><i class="bi bi-briefcase text-primary me-2"></i>Employment Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                            <input type="text" name="employee_id" class="form-control fw-bold" value="{{ old('employee_id', $staff->employee_id) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $staff->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Designation / Role</label>
                            <select name="designation_id" class="form-select">
                                <option value="">Select Designation</option>
                                @foreach($designations as $desig)
                                    <option value="{{ $desig->id }}" {{ old('designation_id', $staff->designation_id) == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date of Joining</label>
                            <input type="date" name="date_of_joining" class="form-control" value="{{ old('date_of_joining', $staff->date_of_joining) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contract Type</label>
                            <select name="contract_type" class="form-select">
                                <option value="Permanent" {{ old('contract_type', $staff->contract_type) == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                <option value="Contract" {{ old('contract_type', $staff->contract_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Part-Time" {{ old('contract_type', $staff->contract_type) == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Basic Salary</label>
                            <div class="input-group">
                                <span class="input-group-text">NPR</span>
                                <input type="number" name="basic_salary" class="form-control" step="0.01" value="{{ old('basic_salary', $staff->basic_salary) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Account Status</label>
                            <select name="status" class="form-select {{ $staff->status == 'Active' ? 'border-success' : 'border-danger' }}">
                                <option value="Active" {{ old('status', $staff->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Resigned" {{ old('status', $staff->status) == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                                <option value="Terminated" {{ old('status', $staff->status) == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                            </select>
                        </div>
                        
                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Update Staff Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                            @if($staff->photo)
                                <div class="mt-2 text-center">
                                    <img src="{{ asset('storage/' . $staff->photo) }}" class="img-thumbnail" style="height: 80px; width: 80px; object-fit: cover; border-radius: 50%;">
                                </div>
                            @endif
                        </div>

                        <div class="form-check form-switch mt-4 mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" name="show_on_website" id="showWeb" value="1" {{ old('show_on_website', $staff->show_on_website) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="showWeb">List on Frontend Website</label>
                            <div class="small text-muted">If checked, this staff member will be visible on the public website team page.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mt-2">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
