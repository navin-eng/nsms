@extends('backend.pages.layout.master')
@push('b-title', 'Staff Profile')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Staff Profile</h3>
            <p class="text-muted mb-0">Detailed information and documents.</p>
        </div>
        <div>
            <a href="{{ route('sms.staff.edit', $staff->id) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil"></i> Edit Profile
            </a>
            <a href="{{ route('sms.staff.index') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <!-- Profile Overview Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <img src="{{ $staff->photo ? asset('storage/' . $staff->photo) : asset('backend/images/avatar.png') }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #f1f5f9;">
                    <h5 class="fw-bold mb-1">{{ $staff->full_name }}</h5>
                    <p class="text-muted mb-2">{{ $staff->designation->name ?? 'No Designation' }}</p>
                    
                    @if($staff->status == 'Active')
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Active</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">{{ $staff->status }}</span>
                    @endif

                    <hr class="my-4">

                    <div class="d-flex justify-content-between text-start mb-2">
                        <span class="text-muted">Employee ID</span>
                        <span class="fw-semibold">{{ $staff->employee_id }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-2">
                        <span class="text-muted">Department</span>
                        <span class="fw-semibold">{{ $staff->department->name ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-muted">Joined</span>
                        <span class="fw-semibold">{{ $staff->date_of_joining ? \Carbon\Carbon::parse($staff->date_of_joining)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="card-title m-0 fw-bold">Contact Info</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3 text-primary"><i class="bi bi-telephone"></i></div>
                                <div>
                                    <div class="small text-muted">Phone</div>
                                    <div>{{ $staff->phone ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3 text-primary"><i class="bi bi-envelope"></i></div>
                                <div>
                                    <div class="small text-muted">Email</div>
                                    <div>{{ $staff->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3 text-danger"><i class="bi bi-heart-pulse"></i></div>
                                <div>
                                    <div class="small text-muted">Emergency Contact</div>
                                    <div>{{ $staff->emergency_contact ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3 text-success"><i class="bi bi-geo-alt"></i></div>
                                <div>
                                    <div class="small text-muted">Current Address</div>
                                    <div>{{ $staff->current_address ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <ul class="nav nav-tabs admin-tabs mb-4" id="staffTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Personal Details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="docs-tab" data-bs-toggle="tab" data-bs-target="#docs" type="button" role="tab">Documents & Certificates</button>
                </li>
            </ul>

            <div class="tab-content" id="staffTabContent">
                <!-- Details Tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="card-title m-0 fw-bold">Personal Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-sm-4">
                                    <div class="small text-muted">Gender</div>
                                    <div class="fw-semibold">{{ $staff->gender ?? 'N/A' }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="small text-muted">Date of Birth</div>
                                    <div class="fw-semibold">{{ $staff->dob ? \Carbon\Carbon::parse($staff->dob)->format('M d, Y') : 'N/A' }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="small text-muted">Marital Status</div>
                                    <div class="fw-semibold">{{ $staff->marital_status ?? 'N/A' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="small text-muted">Permanent Address</div>
                                    <div class="fw-semibold">{{ $staff->permanent_address ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="card-title m-0 fw-bold">Professional Background</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="text-primary mb-2">Qualifications</h6>
                                <p class="mb-0">{{ $staff->qualification ?? 'No qualifications provided.' }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-primary mb-2">Work Experience</h6>
                                <p class="mb-0">{{ $staff->experience ?? 'No experience provided.' }}</p>
                            </div>
                            @if($staff->resume)
                                <div>
                                    <a href="{{ asset('storage/' . $staff->resume) }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-file-earmark-pdf"></i> View Resume / CV
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="docs" role="tabpanel">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0 fw-bold">Uploaded Documents</h6>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
                                <i class="bi bi-upload"></i> Upload
                            </button>
                        </div>
                        <div class="card-body p-0">
                            @if($staff->documents->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Document Title</th>
                                                <th>Uploaded On</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($staff->documents as $doc)
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                                        <span class="fw-semibold">{{ $doc->title }}</span>
                                                    </td>
                                                    <td class="text-muted small">{{ $doc->created_at->format('M d, Y') }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-info me-1">View</a>
                                                        <form action="{{ route('sms.staff.documents.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this document?')">
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
                <form action="{{ route('sms.staff.documents.store', $staff->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Document Title (e.g. ID Card, Certificate)</label>
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
