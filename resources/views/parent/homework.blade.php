@extends('parent.layout.master')
@section('title', 'Child Homework & Assignments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">{{ $student ? $student->full_name . "'s Homework" : 'Homework & Assignments' }}</h4>
        <p class="text-muted small mb-0">Monitor your child's class homework, submission status, and teacher remarks.</p>
    </div>
</div>

@if(!$student || !$activeEnrollment)
<div class="alert alert-warning border-warning shadow-sm rounded-4 p-4">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> No active student selected or student is not enrolled in a class.
</div>
@else

{{-- Stats Overview --}}
@php
    $totalCount = $homeworks->count();
    $submittedCount = $homeworks->filter(fn($h) => $h->submissions->isNotEmpty())->count();
    $gradedCount = $homeworks->filter(fn($h) => $h->submissions->where('status', 'graded')->isNotEmpty())->count();
    $pendingCount = $totalCount - $submittedCount;
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 fs-4">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $totalCount }}</h5>
                    <small class="text-muted">Total Assigned</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3 fs-4">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $pendingCount }}</h5>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-info bg-opacity-10 text-info rounded-3 fs-4">
                    <i class="bi bi-cloud-check-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $submittedCount }}</h5>
                    <small class="text-muted">Submitted</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 fs-4">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $gradedCount }}</h5>
                    <small class="text-muted">Graded</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Homework & Submission Status</h5>
        <span class="badge bg-light text-dark border">{{ $activeEnrollment->academicClass->name ?? '' }} - {{ $activeEnrollment->section->name ?? '' }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted" style="font-size: 0.78rem; text-transform: uppercase;">
                    <tr>
                        <th class="ps-4" style="width: 30%;">Assignment</th>
                        <th>Subject</th>
                        <th>Deadline</th>
                        <th>Submission Status</th>
                        <th>Score</th>
                        <th>Teacher Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($homeworks as $hw)
                        @php
                            $submission = $hw->submissions->first();
                            $dueDate = \Carbon\Carbon::parse($hw->due_date);
                            $isDue = $dueDate->isPast();
                            $dueSoon = $dueDate->isToday() || $dueDate->isTomorrow();
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark fs-6">{{ $hw->title }}</div>
                                @if($hw->description)
                                    <small class="text-muted d-block text-truncate mt-1" style="max-width: 300px;" title="{{ $hw->description }}">
                                        {{ $hw->description }}
                                    </small>
                                @endif
                                <div class="d-flex gap-2 mt-2">
                                    <span class="badge bg-light text-dark border"><i class="bi bi-award me-1"></i>{{ $hw->total_marks ?? 100 }} Marks</span>
                                    @if($hw->file_path)
                                        <a href="{{ asset($hw->file_path) }}" target="_blank" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none">
                                            <i class="bi bi-paperclip me-1"></i>Assignment File
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                    {{ $hw->subject->name ?? 'General' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-{{ $isDue && !$submission ? 'danger' : ($dueSoon && !$submission ? 'warning' : 'dark') }}">
                                    <i class="bi bi-calendar-event me-1"></i>{{ $dueDate->format('M d, Y') }}
                                </div>
                                <small class="text-muted">{{ $dueDate->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($submission)
                                    @if($submission->status === 'graded')
                                        <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Graded</span>
                                    @else
                                        @php
                                            $isLate = \Carbon\Carbon::parse($submission->submission_date)->isAfter($dueDate->endOfDay());
                                        @endphp
                                        <span class="badge bg-{{ $isLate ? 'warning' : 'info' }}-subtle text-{{ $isLate ? 'warning' : 'info' }} border px-2 py-1">
                                            <i class="bi bi-check2 me-1"></i>{{ $isLate ? 'Submitted Late' : 'Submitted' }}
                                        </span>
                                    @endif
                                    <div class="text-muted small" style="font-size: 0.72rem;">
                                        {{ $submission->submission_date->format('M d, h:i A') }}
                                    </div>
                                    @if($submission->file_path)
                                        <a href="{{ asset($submission->file_path) }}" target="_blank" class="small text-primary text-decoration-none d-block mt-1">
                                            <i class="bi bi-file-earmark-check me-1"></i>View Work
                                        </a>
                                    @endif
                                @else
                                    @if($isDue)
                                        <span class="badge bg-danger-subtle text-danger border px-2 py-1"><i class="bi bi-exclamation-circle me-1"></i>Overdue</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($submission && $submission->status === 'graded')
                                    <span class="fw-bold text-success fs-6">{{ $submission->marks_obtained }} / {{ $hw->total_marks ?? 100 }}</span>
                                @elseif($submission)
                                    <span class="badge bg-light text-muted border">Ungraded</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($submission && $submission->feedback)
                                    <div class="p-2 bg-light rounded-3 border small" style="max-width: 220px;">
                                        <i class="bi bi-chat-left-quote-fill text-primary me-1"></i>"{{ $submission->feedback }}"
                                    </div>
                                @elseif($submission)
                                    <span class="text-muted small fst-italic">Pending evaluation</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-check fs-1 d-block mb-3 text-success opacity-50"></i>
                                <h6>No Homework Assigned</h6>
                                <p class="small mb-0">There is currently no homework assigned for {{ $student->full_name }}.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
