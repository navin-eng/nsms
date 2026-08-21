@extends('parent.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">Welcome back, {{ Auth::user()->name }}</h4>
        <p class="text-muted">Here's what's happening with <strong>{{ $child->first_name }}'s</strong> academics.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="bi bi-person-badge fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Current Class</h6>
                        <h4 class="mb-0 fw-bold">{{ $child->currentEnrollment->academicClass->name ?? 'N/A' }}</h4>
                    </div>
                </div>
                <div class="small text-muted border-top pt-2">
                    Section: <strong>{{ $child->currentEnrollment->section->name ?? 'N/A' }}</strong> | Roll No: <strong>{{ $child->roll_number ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="bi bi-calendar-check fs-4 text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Today's Attendance</h6>
                        <h4 class="mb-0 fw-bold">
                            @if($attendanceToday)
                                @if($attendanceToday->status == 'Present')
                                    <span class="text-success">Present</span>
                                @elseif($attendanceToday->status == 'Absent')
                                    <span class="text-danger">Absent</span>
                                @elseif($attendanceToday->status == 'Late')
                                    <span class="text-warning">Late</span>
                                @else
                                    {{ $attendanceToday->status }}
                                @endif
                            @else
                                <span class="text-muted">Not Marked</span>
                            @endif
                        </h4>
                    </div>
                </div>
                <div class="small text-muted border-top pt-2">
                    View full <a href="{{ route('parent.attendance') }}">attendance history</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="bi bi-cash-stack fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Outstanding Fees</h6>
                        <h4 class="mb-0 fw-bold">{{ number_format($outstandingFees, 2) }}</h4>
                    </div>
                </div>
                <div class="small text-muted border-top pt-2">
                    View <a href="{{ route('parent.fees') }}">fee statements and invoices</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-megaphone text-primary me-2"></i>Recent Notices</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($notices as $notice)
                        <li class="list-group-item p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">{{ $notice->title }}</h6>
                                <span class="badge bg-light text-dark">{{ $notice->published_at ? \Carbon\Carbon::parse($notice->published_at)->format('M d, Y') : $notice->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-muted mb-0 small">{{ Str::limit(strip_tags($notice->content), 150) }}</p>
                        </li>
                    @empty
                        <li class="list-group-item p-4 text-center text-muted">
                            No recent notices available.
                        </li>
                    @endforelse
                </ul>
                <div class="card-footer bg-white text-center py-3">
                    <a href="{{ route('parent.notices') }}" class="btn btn-sm btn-outline-primary">View All Notices</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white text-center">
            <div class="card-body d-flex flex-column justify-content-center align-items-center p-5">
                <i class="bi bi-award fs-1 mb-3"></i>
                <h4 class="fw-bold mb-3">Academic Results</h4>
                <p class="text-white-50 mb-4">View {{ $child->first_name }}'s exam marks, grades, and annual transcripts.</p>
                <a href="{{ route('parent.results') }}" class="btn btn-light rounded-pill px-4 fw-bold">View Results</a>
            </div>
        </div>
    </div>
</div>
@endsection
