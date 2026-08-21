@extends('parent.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">Child Profile</h4>
        <p class="text-muted">Personal and academic details for {{ $child->first_name }}.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    @if($child->photo)
                        <img src="{{ asset('storage/' . $child->photo) }}" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #fff;">
                    @else
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center text-primary fw-bold" style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ substr($child->first_name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <h4 class="fw-bold mb-1">{{ $child->full_name }}</h4>
                <p class="text-muted mb-3">{{ $child->currentEnrollment->academicClass->name ?? 'N/A' }} {{ $child->currentEnrollment->section ? '('.$child->currentEnrollment->section->name.')' : '' }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-light text-dark border"><i class="bi bi-hash me-1"></i> Roll: {{ $child->roll_number ?? 'N/A' }}</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-upc-scan me-1"></i> Adm: {{ $child->admission_no }}</span>
                </div>
                
                <hr>
                
                <div class="text-start mt-4">
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">Date of Birth</label>
                        <div class="fw-medium">{{ $child->dob ? \Carbon\Carbon::parse($child->dob)->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">Gender</label>
                        <div class="fw-medium">{{ $child->gender ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">Blood Group</label>
                        <div class="fw-medium">{{ $child->blood_group ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Guardian Details</h5>
            </div>
            <div class="card-body">
                @if($child->guardian)
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Father's Name</label>
                        <div class="fw-medium">{{ $child->guardian->father_name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Mother's Name</label>
                        <div class="fw-medium">{{ $child->guardian->mother_name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Guardian Contact</label>
                        <div class="fw-medium">{{ $child->guardian->guardian_phone ?? $child->guardian->father_phone ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Guardian Email</label>
                        <div class="fw-medium">{{ $child->guardian->guardian_email ?? 'N/A' }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block mb-1">Address</label>
                        <div class="fw-medium">{{ $child->guardian->guardian_address ?? 'N/A' }}</div>
                    </div>
                </div>
                @else
                <div class="text-muted">No guardian information found.</div>
                @endif
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Medical & Other Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Religion</label>
                        <div class="fw-medium">{{ $child->religion ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Category</label>
                        <div class="fw-medium">{{ $child->category ?? 'N/A' }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block mb-1">Medical History</label>
                        <div class="fw-medium">{{ $child->medical_history ?? 'None provided' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
