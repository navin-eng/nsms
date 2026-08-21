@extends('backend.pages.layout.master')
@push('b-title', 'Edit Student')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Edit Student Profile</h3>
            <p class="text-muted mb-0">Update information for {{ $student->full_name }}.</p>
        </div>
        <div>
            <a href="{{ route('sms.students.show', $student->id) }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-eye"></i> View Profile
            </a>
            <a href="{{ route('sms.students.index') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left"></i> Back to Directory
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sms.students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="accordion" id="admissionAccordion">
            
            <!-- Section 1: Academic & Enrollment Info -->
            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                <h2 class="accordion-header" id="headingEnrollment">
                    <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEnrollment" aria-expanded="true" aria-controls="collapseEnrollment">
                        <i class="bi bi-mortarboard text-primary me-2"></i> 1. Academic & Enrollment Details
                    </button>
                </h2>
                <div id="collapseEnrollment" class="accordion-collapse collapse show" aria-labelledby="headingEnrollment">
                    <div class="accordion-body bg-white pt-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Admission Number <span class="text-danger">*</span></label>
                                <input type="text" name="admission_no" class="form-control fw-bold" value="{{ old('admission_no', $student->admission_no) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Admission Date</label>
                                <input type="date" name="admission_date" class="form-control" value="{{ old('admission_date', $student->admission_date) }}">
                            </div>
                            
                            @php
                                $currentEnrollment = $student->currentEnrollment;
                            @endphp

                            <div class="col-md-3">
                                <label class="form-label">Current Academic Year</label>
                                <select name="academic_year_id" class="form-select">
                                    <option value="">Select Year (Leaves unchanged)</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ (old('academic_year_id') == $year->id || ($currentEnrollment && $currentEnrollment->academic_year_id == $year->id)) ? 'selected' : '' }}>
                                            {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Roll Number</label>
                                <input type="number" name="roll_no" class="form-control" value="{{ old('roll_no', $currentEnrollment->roll_no ?? '') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Class</label>
                                <select name="academic_class_id" class="form-select">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ (old('academic_class_id') == $c->id || ($currentEnrollment && $currentEnrollment->academic_class_id == $c->id)) ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Section</label>
                                <select name="section_id" class="form-select">
                                    <option value="">Select Section</option>
                                    @foreach($sections as $s)
                                        <option value="{{ $s->id }}" data-class-ids="{{ $s->academicClasses->pluck('id')->join(',') }}" {{ (old('section_id') == $s->id || ($currentEnrollment && $currentEnrollment->section_id == $s->id)) ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stream</label>
                                <select name="stream_id" class="form-select">
                                    <option value="">Select Stream</option>
                                    @foreach($streams as $st)
                                        <option value="{{ $st->id }}" {{ (old('stream_id') == $st->id || ($currentEnrollment && $currentEnrollment->stream_id == $st->id)) ? 'selected' : '' }}>{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mt-4">
                                <label class="form-label fw-bold">Student Status</label>
                                <select name="status" class="form-select {{ $student->status == 'Active' ? 'border-success' : 'border-danger' }}">
                                    <option value="Active" {{ $student->status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Graduated" {{ $student->status == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="Transferred" {{ $student->status == 'Transferred' ? 'selected' : '' }}>Transferred</option>
                                    <option value="Dropped" {{ $student->status == 'Dropped' ? 'selected' : '' }}>Dropped</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Student Personal Info -->
            <div class="accordion-item border-0 shadow-sm mb-3 rounded">
                <h2 class="accordion-header" id="headingPersonal">
                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonal" aria-expanded="false" aria-controls="collapsePersonal">
                        <i class="bi bi-person text-primary me-2"></i> 2. Student Personal Information
                    </button>
                </h2>
                <div id="collapsePersonal" class="accordion-collapse collapse" aria-labelledby="headingPersonal">
                    <div class="accordion-body bg-white pt-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob', $student->dob) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Blood Group</label>
                                <select name="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                        <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control" value="{{ old('category', $student->category) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Current Address</label>
                                <input type="text" name="current_address" class="form-control" value="{{ old('current_address', $student->current_address) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Permanent Address</label>
                                <input type="text" name="permanent_address" class="form-control" value="{{ old('permanent_address', $student->permanent_address) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Update Photo (JPG, PNG)</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                                @if($student->photo)
                                    <div class="mt-2 text-center">
                                        <img src="{{ asset('storage/' . $student->photo) }}" class="img-thumbnail" style="height: 80px; width: 80px; object-fit: cover; border-radius: 50%;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Previous School Details</label>
                                <textarea name="previous_school_details" class="form-control" rows="1">{{ old('previous_school_details', $student->previous_school_details) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Guardian Details -->
            <div class="accordion-item border-0 shadow-sm mb-4 rounded">
                <h2 class="accordion-header" id="headingGuardian">
                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGuardian" aria-expanded="false" aria-controls="collapseGuardian">
                        <i class="bi bi-people text-primary me-2"></i> 3. Parent & Guardian Details
                    </button>
                </h2>
                <div id="collapseGuardian" class="accordion-collapse collapse" aria-labelledby="headingGuardian">
                    <div class="accordion-body bg-white pt-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted small text-uppercase fw-bold">Father Details</label>
                                <input type="text" name="father_name" class="form-control mb-2" value="{{ old('father_name', $student->guardian->father_name ?? '') }}">
                                <input type="text" name="father_phone" class="form-control mb-2" value="{{ old('father_phone', $student->guardian->father_phone ?? '') }}">
                                <input type="text" name="father_occupation" class="form-control" value="{{ old('father_occupation', $student->guardian->father_occupation ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small text-uppercase fw-bold">Mother Details</label>
                                <input type="text" name="mother_name" class="form-control mb-2" value="{{ old('mother_name', $student->guardian->mother_name ?? '') }}">
                                <input type="text" name="mother_phone" class="form-control mb-2" value="{{ old('mother_phone', $student->guardian->mother_phone ?? '') }}">
                                <input type="text" name="mother_occupation" class="form-control" value="{{ old('mother_occupation', $student->guardian->mother_occupation ?? '') }}">
                            </div>
                            <div class="col-md-4 border-start">
                                <label class="form-label text-muted small text-uppercase fw-bold">Local Guardian Details</label>
                                <input type="text" name="guardian_name" class="form-control mb-2" value="{{ old('guardian_name', $student->guardian->guardian_name ?? '') }}">
                                <input type="text" name="guardian_relation" class="form-control mb-2" value="{{ old('guardian_relation', $student->guardian->guardian_relation ?? '') }}">
                                <input type="text" name="guardian_phone" class="form-control mb-2" value="{{ old('guardian_phone', $student->guardian->guardian_phone ?? '') }}">
                                <input type="email" name="guardian_email" class="form-control" value="{{ old('guardian_email', $student->guardian->guardian_email ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Accordion -->

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-5 py-2">
                <i class="bi bi-save me-1"></i> Update Profile
            </button>
        </div>
    </form>
@endsection
