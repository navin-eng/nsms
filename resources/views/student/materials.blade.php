@extends('student.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">Study Materials</h4>
        <p class="text-muted">Downloadable resources for your class.</p>
    </div>
</div>

@if(!$activeEnrollment)
<div class="alert alert-warning border-warning shadow-sm">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> You are not currently enrolled in any class.
</div>
@else
<div class="row g-4">
    @forelse($materials as $mat)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
                            <i class="bi bi-file-earmark-text fs-3"></i>
                        </div>
                        <span class="badge bg-light text-dark border">{{ $mat->subject->name ?? 'General' }}</span>
                    </div>
                    <h5 class="fw-bold mb-2">{{ $mat->title }}</h5>
                    <p class="text-muted small flex-grow-1">{{ $mat->description ?? 'No description provided.' }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $mat->created_at->format('M d, Y') }}</small>
                        @if($mat->file_path)
                            <a href="{{ asset($mat->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="bi bi-download me-1"></i> Download
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-folder2-open fs-1 d-block mb-3 text-secondary opacity-50"></i>
                    No study materials uploaded for your class yet.
                </div>
            </div>
        </div>
    @endforelse
</div>
@endif
@endsection
