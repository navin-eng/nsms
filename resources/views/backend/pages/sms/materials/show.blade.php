@extends('backend.pages.layout.master')
@section('title', 'View Study Material')
@section('backend-content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 d-print-none">
    <div>
        <h3 class="mb-1">Study Material Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('sms.materials.index') }}">Materials</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $material->title }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary shadow-sm"><i class="bi bi-printer me-1"></i> Print</button>
        <a href="{{ route('sms.materials.index') }}" class="btn btn-light border shadow-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 fw-bold">{{ $material->title }}</h5>
        <span class="badge bg-{{ $material->status == 'Active' ? 'success' : 'secondary' }} rounded-pill px-3">{{ $material->status }}</span>
    </div>
    <div class="card-body p-4">
        <div class="row g-4 mb-4">
            <div class="col-md-4 col-6">
                <div class="text-body-secondary small fw-semibold text-uppercase mb-1">Class</div>
                <div class="fs-6 fw-medium">{{ $material->academicClass->name ?? 'N/A' }}</div>
            </div>
            <div class="col-md-4 col-6">
                <div class="text-body-secondary small fw-semibold text-uppercase mb-1">Subject</div>
                <div class="fs-6 fw-medium">{{ $material->subject->name ?? 'N/A' }}</div>
            </div>
            <div class="col-md-4 col-6">
                <div class="text-body-secondary small fw-semibold text-uppercase mb-1">Date Uploaded</div>
                <div class="fs-6 fw-medium"><i class="bi bi-calendar-check me-1"></i>{{ $material->created_at->format('M d, Y') }}</div>
            </div>
        </div>

        <hr class="border-light-subtle my-4">

        <div>
            <h6 class="fw-bold mb-3">Description</h6>
            <div class="p-4 bg-body-tertiary rounded-4 border">
                @if($material->description)
                    {!! nl2br(e($material->description)) !!}
                @else
                    <span class="text-muted fst-italic">No description provided.</span>
                @endif
            </div>
        </div>

        @if($material->file_path)
            <div class="mt-4 d-print-none">
                <h6 class="fw-bold mb-3">Attachment</h6>
                <a href="{{ asset($material->file_path) }}" target="_blank" class="btn btn-outline-info rounded-pill px-4">
                    <i class="bi bi-cloud-arrow-down me-1"></i> Download / View Attached File
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        .d-print-none {
            display: none !important;
        }
    }
</style>
@endsection
