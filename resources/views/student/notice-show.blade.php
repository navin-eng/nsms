@extends('student.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('student.notices') }}" class="btn btn-sm btn-light border mb-3"><i class="bi bi-arrow-left me-1"></i> Back to Notices</a>
        <h4 class="mb-1">Notice Details</h4>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-5">
        <div class="text-center mb-5 pb-4 border-bottom">
            <h2 class="fw-bold mb-3">{{ $notice->title }}</h2>
            <div class="text-muted d-flex justify-content-center gap-4">
                <span><i class="bi bi-calendar3 me-1"></i> Published: {{ $notice->created_at->format('l, F j, Y') }}</span>
                <span><i class="bi bi-clock me-1"></i> {{ $notice->created_at->format('h:i A') }}</span>
            </div>
        </div>
        
        <div class="notice-content fs-5" style="line-height: 1.8;">
            {!! $notice->content !!}
        </div>
        
        @if($notice->file)
        <div class="mt-5 pt-4 border-top">
            <h6 class="fw-bold mb-3"><i class="bi bi-paperclip me-2"></i>Attached Document</h6>
            <a href="{{ asset('storage/'.$notice->file) }}" target="_blank" class="btn btn-outline-primary shadow-sm rounded-pill px-4">
                <i class="bi bi-cloud-download me-2"></i>Download Attachment
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
