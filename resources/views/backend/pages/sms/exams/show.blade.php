@extends('backend.pages.layout.master')

@section('title', 'Exam Details')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-0">Exam Details</h3>
            <p class="text-muted">{{ $exam->title }}</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('sms.exams.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Back to Exams
            </a>
            <a href="{{ route('sms.exams.edit', $exam->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Exam
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary border-bottom pb-2 mb-3">Exam Information</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">Title:</dt>
                        <dd class="col-sm-7 fw-bold">{{ $exam->title }}</dd>
                        
                        <dt class="col-sm-5 text-muted">Academic Year:</dt>
                        <dd class="col-sm-7">{{ $exam->academicYear->name ?? '-' }}</dd>
                        
                        <dt class="col-sm-5 text-muted">Start Date:</dt>
                        <dd class="col-sm-7">{{ $exam->start_date ? $exam->start_date->format('M d, Y') : '-' }}</dd>
                        
                        <dt class="col-sm-5 text-muted">End Date:</dt>
                        <dd class="col-sm-7">{{ $exam->end_date ? $exam->end_date->format('M d, Y') : '-' }}</dd>
                        
                        <dt class="col-sm-5 text-muted">Status:</dt>
                        <dd class="col-sm-7">
                            @if($exam->status == 'Upcoming')
                                <span class="badge bg-info text-dark">Upcoming</span>
                            @elseif($exam->status == 'Ongoing')
                                <span class="badge bg-warning text-dark">Ongoing</span>
                            @else
                                <span class="badge bg-success">Completed</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary border-bottom pb-2 mb-3">Quick Actions</h5>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <a href="{{ route('sms.exam-schedules.index') }}" class="text-decoration-none">
                                <div class="card border-info shadow-none bg-info bg-opacity-10 text-center py-4 h-100 transition-hover">
                                    <i class="bi bi-calendar3 display-6 text-info mb-2"></i>
                                    <h6 class="text-info mb-0">Manage Schedules</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('sms.exam-marks.index') }}" class="text-decoration-none">
                                <div class="card border-warning shadow-none bg-warning bg-opacity-10 text-center py-4 h-100 transition-hover">
                                    <i class="bi bi-pencil-square display-6 text-warning mb-2"></i>
                                    <h6 class="text-warning mb-0">Marks Entry</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('sms.exam-results.index') }}" class="text-decoration-none">
                                <div class="card border-success shadow-none bg-success bg-opacity-10 text-center py-4 h-100 transition-hover">
                                    <i class="bi bi-file-earmark-bar-graph display-6 text-success mb-2"></i>
                                    <h6 class="text-success mb-0">View Results</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: all 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection
