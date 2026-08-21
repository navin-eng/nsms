@extends('student.layout.master')
@section('title', 'My Homework & Assignments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">My Homework & Assignments</h4>
        <p class="text-muted small mb-0">Track your class assignments, submit your work online, and view teacher feedback.</p>
    </div>
</div>

@if(!$activeEnrollment)
<div class="alert alert-warning border-warning shadow-sm rounded-4 p-4">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> You are not currently enrolled in an active class. Please contact the school administration.
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
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $pendingCount }}</h5>
                    <small class="text-muted">Pending Work</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-info bg-opacity-10 text-info rounded-3 fs-4">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
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
                    <small class="text-muted">Graded & Reviewed</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Assignments List</h5>
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
                        <th>Status / Score</th>
                        <th>Teacher Remarks</th>
                        <th class="text-end pe-4">Action</th>
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
                                            <i class="bi bi-paperclip me-1"></i>Task File
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
                                        <div class="fw-bold text-success mt-1 fs-6">
                                            {{ $submission->marks_obtained }} / {{ $hw->total_marks ?? 100 }}
                                        </div>
                                    @else
                                        @php
                                            $isLate = \Carbon\Carbon::parse($submission->submission_date)->isAfter($dueDate->endOfDay());
                                        @endphp
                                        <span class="badge bg-{{ $isLate ? 'warning' : 'info' }}-subtle text-{{ $isLate ? 'warning' : 'info' }} border px-2 py-1">
                                            <i class="bi bi-check2 me-1"></i>{{ $isLate ? 'Submitted Late' : 'Submitted' }}
                                        </span>
                                        <div class="text-muted small" style="font-size: 0.72rem;">
                                            {{ $submission->submission_date->format('M d, h:i A') }}
                                        </div>
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
                                @if($submission && $submission->feedback)
                                    <div class="p-2 bg-light rounded-3 border small" style="max-width: 200px;">
                                        <i class="bi bi-chat-left-quote-fill text-primary me-1"></i>"{{ $submission->feedback }}"
                                    </div>
                                @elseif($submission)
                                    <span class="text-muted small fst-italic">Awaiting teacher review</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-{{ $submission ? 'outline-primary' : 'primary' }} rounded-pill px-3 py-1 btn-submit-hw"
                                    data-id="{{ $hw->id }}"
                                    data-title="{{ $hw->title }}"
                                    data-desc="{{ $hw->description }}"
                                    data-due="{{ $dueDate->format('M d, Y') }}"
                                    data-marks="{{ $hw->total_marks ?? 100 }}"
                                    data-submitted="{{ $submission ? '1' : '0' }}"
                                    data-file="{{ $submission->file_path ?? '' }}"
                                    data-comments="{{ $submission->comments ?? '' }}"
                                    data-status="{{ $submission->status ?? '' }}"
                                    data-score="{{ $submission->marks_obtained ?? '' }}"
                                    data-feedback="{{ $submission->feedback ?? '' }}">
                                    @if($submission)
                                        <i class="bi bi-arrow-repeat me-1"></i>Update / View
                                    @else
                                        <i class="bi bi-cloud-arrow-up me-1"></i>Submit Work
                                    @endif
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-check fs-1 d-block mb-3 text-success opacity-50"></i>
                                <h6>No Homework Assigned</h6>
                                <p class="small mb-0">You have completed all assignments or no homework has been assigned currently.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Modal: Submit / View Assignment --}}
<div class="modal fade" id="submitHomeworkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="submitHomeworkForm" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-cloud-upload text-primary me-2"></i>Assignment Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Assignment Info Box --}}
                <div class="p-3 bg-light rounded-4 border mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold text-dark mb-0" id="modalHwTitle"></h5>
                        <span class="badge bg-primary px-3 py-1" id="modalHwMarks"></span>
                    </div>
                    <div class="text-muted small mb-2" id="modalHwDue"></div>
                    <div class="small text-dark" id="modalHwDesc"></div>
                </div>

                {{-- Existing Grade / Feedback Banner --}}
                <div id="modalFeedbackBox" class="alert alert-success border-success mb-4" style="display:none;">
                    <h6 class="fw-bold mb-1"><i class="bi bi-award-fill me-1"></i>Teacher Evaluation: <span id="modalScoreText"></span></h6>
                    <div class="small" id="modalFeedbackText"></div>
                </div>

                {{-- Submission Form Inputs --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Your Work (PDF, Word, Image, ZIP) <span class="text-danger">*</span></label>
                    <input type="file" name="file" class="form-control">
                    <div id="currentUploadedFile" class="small mt-1 text-muted" style="display:none;"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Notes / Remarks for Teacher (Optional)</label>
                    <textarea name="comments" id="modalCommentsInput" class="form-control" rows="3" placeholder="Write any notes, explanations, or questions about your assignment submission..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-send me-1"></i>Submit Assignment</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-submit-hw').on('click', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var desc = $(this).data('desc') || 'No additional instructions.';
        var due = $(this).data('due');
        var marks = $(this).data('marks');
        var isSubmitted = $(this).data('submitted') == '1';
        var file = $(this).data('file');
        var comments = $(this).data('comments') || '';
        var status = $(this).data('status');
        var score = $(this).data('score');
        var feedback = $(this).data('feedback');

        var actionUrl = "{{ url('student/homework') }}/" + id + "/submit";
        $('#submitHomeworkForm').attr('action', actionUrl);
        $('#modalHwTitle').text(title);
        $('#modalHwDesc').text(desc);
        $('#modalHwDue').html('<i class="bi bi-calendar-event me-1"></i>Due Date: <strong>' + due + '</strong>');
        $('#modalHwMarks').text(marks + ' Marks');
        $('#modalCommentsInput').val(comments);

        if (file) {
            $('#currentUploadedFile').html('Currently uploaded: <a href="/' + file + '" target="_blank" class="fw-semibold text-primary"><i class="bi bi-file-earmark-check me-1"></i>View Uploaded File</a>').show();
        } else {
            $('#currentUploadedFile').hide();
        }

        if (status === 'graded') {
            $('#modalScoreText').text(score + ' / ' + marks);
            $('#modalFeedbackText').text(feedback ? '"' + feedback + '"' : 'No written feedback provided.');
            $('#modalFeedbackBox').show();
        } else {
            $('#modalFeedbackBox').hide();
        }

        $('#submitHomeworkModal').modal('show');
    });
});
</script>
@endpush
@endsection
