@extends('backend.pages.layout.master')
@push('b-title', 'Application Details')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Application Details</h3>
        <p class="text-muted mb-0">Review application and manage enrollment.</p>
    </div>
    <div>
        <a href="{{ route('sms.admissions.applications.print', $application->id) }}" target="_blank" class="btn btn-primary me-2">
            <i class="bi bi-printer"></i> Print
        </a>
        <a href="{{ route('sms.admissions.applications.index') }}" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    <!-- Left Column: Applicant Info -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="card-title m-0 fw-bold">Applicant Information</h6>
                <div>
                    @if($application->status == 'Pending')
                        <span class="badge bg-warning text-dark px-3 py-2">Pending</span>
                    @elseif($application->status == 'Approved')
                        <span class="badge bg-success px-3 py-2">Approved</span>
                    @elseif($application->status == 'Rejected')
                        <span class="badge bg-danger px-3 py-2">Rejected</span>
                    @elseif($application->status == 'Enrolled')
                        <span class="badge bg-primary px-3 py-2">Enrolled</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">Full Name</div>
                    <div class="col-sm-8 fw-semibold">{{ $application->first_name }} {{ $application->last_name }}</div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">Date of Birth & Gender</div>
                    <div class="col-sm-8">{{ $application->dob->format('d M, Y') }} ({{ $application->gender }})</div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">Applied Class & Year</div>
                    <div class="col-sm-8">{{ $application->academicClass->name ?? '-' }} ({{ $application->academicYear->name ?? '-' }})</div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">Parents</div>
                    <div class="col-sm-8">
                        <div>Father: {{ $application->father_name ?? 'N/A' }}</div>
                        <div>Mother: {{ $application->mother_name ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 text-muted">Contact Number</div>
                    <div class="col-sm-8">{{ $application->contact_number }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Previous School</div>
                    <div class="col-sm-8">{{ $application->previous_school ?? 'None' }}</div>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="card-title m-0 fw-bold">Admission Documents</h6>
                @if($application->status != 'Enrolled')
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocModal">
                    <i class="bi bi-upload"></i> Upload
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                @if($application->documents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Document Title</th>
                                    <th>Upload Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($application->documents as $doc)
                                <tr>
                                    <td>{{ $doc->title }}</td>
                                    <td>{{ $doc->created_at->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($application->status != 'Enrolled')
                                        <form action="{{ route('sms.admissions.documents.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this document?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        No documents uploaded yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Actions -->
    <div class="col-lg-4">
        @if($application->status != 'Enrolled')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="card-title m-0 fw-bold">Application Actions</h6>
            </div>
            <div class="card-body">
                
                @if($application->status == 'Pending')
                    <p class="small text-muted mb-3">Review the application and decide to approve or reject it.</p>
                    <form action="{{ route('sms.admissions.applications.status', $application->id) }}" method="POST" class="mb-2">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this application?')">
                            <i class="bi bi-check-circle"></i> Approve Application
                        </button>
                    </form>
                    <form action="{{ route('sms.admissions.applications.status', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this application?')">
                            <i class="bi bi-x-circle"></i> Reject Application
                        </button>
                    </form>
                @elseif($application->status == 'Approved')
                    <p class="small text-muted mb-3">This application is approved. You can now enroll the applicant as a student.</p>
                    <form action="{{ route('sms.admissions.applications.enroll', $application->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100" onclick="return confirm('This will create a new Student profile and transfer all documents. Proceed?')">
                            <i class="bi bi-person-plus"></i> Enroll as Student
                        </button>
                    </form>
                    
                    <hr>
                    <form action="{{ route('sms.admissions.applications.status', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Pending">
                        <button type="submit" class="btn btn-outline-secondary w-100 btn-sm">
                            <i class="bi bi-arrow-counterclockwise"></i> Revert to Pending
                        </button>
                    </form>
                @elseif($application->status == 'Rejected')
                    <p class="small text-muted mb-3 text-danger">This application was rejected.</p>
                    <form action="{{ route('sms.admissions.applications.status', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Pending">
                        <button type="submit" class="btn btn-outline-secondary w-100 btn-sm">
                            <i class="bi bi-arrow-counterclockwise"></i> Revert to Pending
                        </button>
                    </form>
                @endif
                
            </div>
        </div>

        <div class="card border-0 shadow-sm border-danger border-top border-3">
            <div class="card-body">
                <form action="{{ route('sms.admissions.applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('Permanently delete this application? This action cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash"></i> Delete Application
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="alert alert-info">
            <h5 class="alert-heading"><i class="bi bi-info-circle"></i> Enrolled</h5>
            <p class="mb-0">This applicant has been successfully enrolled as a student. All documents have been transferred to the student profile.</p>
        </div>
        @endif
    </div>
</div>

<!-- Upload Document Modal -->
@if($application->status != 'Enrolled')
<div class="modal fade" id="uploadDocModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sms.admissions.documents.store', $application->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Admission Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Document Title (e.g. Birth Certificate)</label>
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
@endif

@endsection
