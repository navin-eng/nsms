@extends('backend.pages.layout.master')
@push('b-title', 'New Application')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">New Application</h3>
        <p class="text-muted mb-0">Enter the details for the new admission application.</p>
    </div>
    <a href="{{ route('sms.admissions.applications.index') }}" class="btn btn-light border">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('sms.admissions.applications.store') }}" method="POST">
            @csrf
            
            <h5 class="mb-3 border-bottom pb-2">Student Information</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Date of Birth *</label>
                    <input type="date" name="dob" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gender *</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <h5 class="mb-3 border-bottom pb-2 mt-4">Academic Details</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Academic Year *</label>
                    <select name="academic_year_id" class="form-select" required>
                        <option value="">Select Year</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Class Applying For *</label>
                    <select name="academic_class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Previous School (Optional)</label>
                    <input type="text" name="previous_school" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Application Date *</label>
                    <input type="date" name="application_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <h5 class="mb-3 border-bottom pb-2 mt-4">Parent/Guardian Information</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Father's Name</label>
                    <input type="text" name="father_name" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mother's Name</label>
                    <input type="text" name="mother_name" class="form-control">
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" required>
                </div>
            </div>

            <div class="text-end border-top pt-3">
                <button type="submit" class="btn btn-primary px-4">Submit Application</button>
            </div>
        </form>
    </div>
</div>
@endsection
