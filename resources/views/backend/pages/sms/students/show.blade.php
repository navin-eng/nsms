@extends('backend.pages.layout.master')
@push('b-title', 'Student Profile')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Student Profile</h3>
            <p class="text-muted mb-0">Detailed student information, guardian details, and enrollment history.</p>
        </div>
        <div>
            <a href="{{ route('sms.students.print', $student->id) }}" target="_blank" class="btn btn-secondary me-2">
                <i class="bi bi-printer"></i> Print Profile
            </a>
            <a href="{{ route('sms.students.edit', $student->id) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil"></i> Edit Profile
            </a>
            <a href="{{ route('sms.students.index') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Profile Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <img src="{{ $student->photo ? asset('storage/' . $student->photo) : asset('backend/images/avatar.png') }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #f1f5f9;">
                    <h5 class="fw-bold mb-1">{{ $student->full_name }}</h5>
                    <p class="text-muted mb-2">
                        @if($student->currentEnrollment)
                            {{ $student->currentEnrollment->academicClass->name ?? '' }} 
                            {{ $student->currentEnrollment->section ? '(' . $student->currentEnrollment->section->name . ')' : '' }}
                        @else
                            No Active Class
                        @endif
                    </p>
                    
                    @if($student->status == 'Active')
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Active</span>
                    @elseif($student->status == 'Graduated')
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Graduated</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">{{ $student->status }}</span>
                    @endif

                    <hr class="my-4">

                    <div class="d-flex justify-content-between text-start mb-2">
                        <span class="text-muted">Admission No</span>
                        <span class="fw-semibold">{{ $student->admission_no }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-2">
                        <span class="text-muted">Roll No</span>
                        <span class="fw-semibold">{{ $student->currentEnrollment->roll_no ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-2">
                        <span class="text-muted">Admission Date</span>
                        <span class="fw-semibold">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-2">
                        <span class="text-muted">DOB</span>
                        <span class="fw-semibold">{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-muted">Blood Group</span>
                        <span class="fw-semibold">{{ $student->blood_group ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Parent Quick Contact -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="card-title m-0 fw-bold">Primary Guardian</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <div class="small text-muted">Name</div>
                            <div class="fw-semibold">{{ $student->guardian->guardian_name ?? $student->guardian->father_name ?? 'N/A' }}</div>
                            <div class="small text-muted mt-1">{{ $student->guardian->guardian_relation ?? 'Father' }}</div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3 text-primary"><i class="bi bi-telephone"></i></div>
                                <div>
                                    <div class="small text-muted">Phone</div>
                                    <div>{{ $student->guardian->guardian_phone ?? $student->guardian->father_phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3 text-success"><i class="bi bi-geo-alt"></i></div>
                                <div>
                                    <div class="small text-muted">Address</div>
                                    <div>{{ $student->guardian->guardian_address ?? $student->current_address ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <ul class="nav nav-tabs admin-tabs mb-4" id="studentTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Personal & Guardian Details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Enrollment History</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs" type="button" role="tab">Documents</button>
                </li>
            </ul>

            <div class="tab-content" id="studentTabContent">
                <!-- Details Tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <!-- Personal info -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="card-title m-0 fw-bold">Personal Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <div class="small text-muted">Gender</div>
                                    <div class="fw-semibold">{{ $student->gender ?? 'N/A' }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="small text-muted">Religion</div>
                                    <div class="fw-semibold">{{ $student->religion ?? 'N/A' }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="small text-muted">Category</div>
                                    <div class="fw-semibold">{{ $student->category ?? 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="small text-muted">Current Address</div>
                                    <div class="fw-semibold">{{ $student->current_address ?? 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="small text-muted">Permanent Address</div>
                                    <div class="fw-semibold">{{ $student->permanent_address ?? 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="small text-muted">Previous School Details</div>
                                    <p class="mb-0">{{ $student->previous_school_details ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guardian Info -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="card-title m-0 fw-bold">Comprehensive Guardian Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6 border-end">
                                    <h6 class="text-primary mb-3">Father's Details</h6>
                                    <div class="mb-2"><span class="text-muted small">Name:</span> <span class="fw-semibold">{{ $student->guardian->father_name ?? 'N/A' }}</span></div>
                                    <div class="mb-2"><span class="text-muted small">Phone:</span> <span>{{ $student->guardian->father_phone ?? 'N/A' }}</span></div>
                                    <div><span class="text-muted small">Occupation:</span> <span>{{ $student->guardian->father_occupation ?? 'N/A' }}</span></div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Mother's Details</h6>
                                    <div class="mb-2"><span class="text-muted small">Name:</span> <span class="fw-semibold">{{ $student->guardian->mother_name ?? 'N/A' }}</span></div>
                                    <div class="mb-2"><span class="text-muted small">Phone:</span> <span>{{ $student->guardian->mother_phone ?? 'N/A' }}</span></div>
                                    <div><span class="text-muted small">Occupation:</span> <span>{{ $student->guardian->mother_occupation ?? 'N/A' }}</span></div>
                                </div>
                                
                                @if($student->guardian->guardian_name)
                                <div class="col-12 mt-4 pt-4 border-top">
                                    <h6 class="text-primary mb-3">Local Guardian Details</h6>
                                    <div class="row g-3">
                                        <div class="col-sm-6"><span class="text-muted small">Name:</span> <span class="fw-semibold">{{ $student->guardian->guardian_name }}</span></div>
                                        <div class="col-sm-6"><span class="text-muted small">Relation:</span> <span>{{ $student->guardian->guardian_relation }}</span></div>
                                        <div class="col-sm-6"><span class="text-muted small">Phone:</span> <span>{{ $student->guardian->guardian_phone }}</span></div>
                                        <div class="col-sm-6"><span class="text-muted small">Email:</span> <span>{{ $student->guardian->guardian_email }}</span></div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Tab -->
                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="card-title m-0 fw-bold">Academic Session History</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Academic Year</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Roll No</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->enrollments->sortByDesc('academicYear.start_date') as $enrollment)
                                            <tr>
                                                <td class="ps-3 fw-semibold">{{ $enrollment->academicYear->name ?? 'N/A' }}</td>
                                                <td>{{ $enrollment->academicClass->name ?? 'N/A' }}</td>
                                                <td>{{ $enrollment->section->name ?? 'N/A' }}</td>
                                                <td>{{ $enrollment->roll_no ?? 'N/A' }}</td>
                                                <td>
                                                    @if($enrollment->status == 'Continuing')
                                                        <span class="badge bg-success bg-opacity-10 text-success">Continuing</span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $enrollment->status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="docs" role="tabpanel">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0 fw-bold">Uploaded Documents</h6>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
                                <i class="bi bi-upload"></i> Upload Document
                            </button>
                        </div>
                        <div class="card-body p-0">
                            @if($student->documents->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Document Title</th>
                                                <th>Uploaded On</th>
                                                <th class="text-end pe-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($student->documents as $doc)
                                                <tr>
                                                    <td class="ps-3">
                                                        <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                                        <span class="fw-semibold">{{ $doc->title }}</span>
                                                    </td>
                                                    <td class="text-muted small">{{ $doc->created_at->format('M d, Y') }}</td>
                                                    <td class="text-end pe-3">
                                                        <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-info me-1">View</a>
                                                        <form action="{{ route('sms.students.documents.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this document?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x fs-1 d-block mb-2 opacity-50"></i>
                                    No documents uploaded yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Document Modal -->
    <div class="modal fade" id="uploadDocModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sms.students.documents.store', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Student Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Document Title (e.g. Birth Certificate, TC)</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Select File (PDF, JPG, PNG)</label>
                            <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
