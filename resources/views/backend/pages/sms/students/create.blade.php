@extends('backend.pages.layout.master')
@push('b-title', 'Admit New Student')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Admit New Student</h3>
            <p class="text-muted mb-0">Fill in the details to enroll a new student.</p>
        </div>
        <div>
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

    <form action="{{ route('sms.students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Accordion for Form Sections -->
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
                                <input type="text" name="admission_no" class="form-control fw-bold" value="{{ old('admission_no', 'ADM-' . date('Y') . '-' . rand(100,999)) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Admission Date</label>
                                <input type="date" name="admission_date" class="form-control" value="{{ old('admission_date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Academic Year <span class="text-danger">*</span></label>
                                <select name="academic_year_id" class="form-select" required>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ (old('academic_year_id') == $year->id || ($activeYear && $activeYear->id == $year->id)) ? 'selected' : '' }}>
                                            {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Roll Number</label>
                                <input type="number" name="roll_no" class="form-control" value="{{ old('roll_no') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Class <span class="text-danger">*</span></label>
                                <select name="academic_class_id" class="form-select" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ old('academic_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Section</label>
                                <select name="section_id" class="form-select">
                                    <option value="">Select Section</option>
                                    @foreach($sections as $s)
                                        <option value="{{ $s->id }}" {{ old('section_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stream</label>
                                <select name="stream_id" class="form-select">
                                    <option value="">Select Stream</option>
                                    @foreach($streams as $st)
                                        <option value="{{ $st->id }}" {{ old('stream_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                    @endforeach
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
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Blood Group</label>
                                <select name="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    <option value="A+">A+</option><option value="A-">A-</option>
                                    <option value="B+">B+</option><option value="B-">B-</option>
                                    <option value="O+">O+</option><option value="O-">O-</option>
                                    <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control" placeholder="General, OBC, etc." value="{{ old('category') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Current Address</label>
                                <input type="text" name="current_address" class="form-control" value="{{ old('current_address') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Permanent Address</label>
                                <input type="text" name="permanent_address" class="form-control" value="{{ old('permanent_address') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Student Photo (JPG, PNG)</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Previous School Details</label>
                                <textarea name="previous_school_details" class="form-control" rows="1">{{ old('previous_school_details') }}</textarea>
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
                                <input type="text" name="father_name" class="form-control mb-2" placeholder="Father's Name" value="{{ old('father_name') }}">
                                <input type="text" name="father_phone" class="form-control mb-2" placeholder="Father's Phone" value="{{ old('father_phone') }}">
                                <input type="text" name="father_occupation" class="form-control" placeholder="Father's Occupation" value="{{ old('father_occupation') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small text-uppercase fw-bold">Mother Details</label>
                                <input type="text" name="mother_name" class="form-control mb-2" placeholder="Mother's Name" value="{{ old('mother_name') }}">
                                <input type="text" name="mother_phone" class="form-control mb-2" placeholder="Mother's Phone" value="{{ old('mother_phone') }}">
                                <input type="text" name="mother_occupation" class="form-control" placeholder="Mother's Occupation" value="{{ old('mother_occupation') }}">
                            </div>
                            <div class="col-md-4 border-start">
                                <label class="form-label text-muted small text-uppercase fw-bold">Local Guardian Details</label>
                                <input type="text" name="guardian_name" class="form-control mb-2" placeholder="Guardian's Name" value="{{ old('guardian_name') }}">
                                <input type="text" name="guardian_relation" class="form-control mb-2" placeholder="Relation (e.g., Uncle)" value="{{ old('guardian_relation') }}">
                                <input type="text" name="guardian_phone" class="form-control mb-2" placeholder="Guardian's Phone" value="{{ old('guardian_phone') }}">
                                <input type="email" name="guardian_email" class="form-control" placeholder="Guardian's Email" value="{{ old('guardian_email') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Accordion -->

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-5 py-2">
                <i class="bi bi-save me-1"></i> Save Admission
            </button>
        </div>
    </form>
@endsection
