@extends('backend.pages.layout.master')
@section('title', 'Homework Details & Submissions')
@section('backend-content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 d-print-none">
    <div>
        <h4 class="mb-1 fw-bold">Homework Details & Submissions</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('sms.homework.index') }}">Homework</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $homework->title }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary shadow-sm"><i class="bi bi-printer me-1"></i> Print Roster</button>
        <a href="{{ route('sms.homework.index') }}" class="btn btn-sm btn-light border shadow-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Left: Homework Info --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="card-title m-0 fw-bold">Assignment Overview</h6>
                <span class="badge bg-{{ $homework->status == 'Active' ? 'success' : 'secondary' }}-subtle text-{{ $homework->status == 'Active' ? 'success' : 'secondary' }} border px-3 py-1 rounded-pill">{{ $homework->status }}</span>
            </div>
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark mb-3">{{ $homework->title }}</h5>

                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Class & Section:</span>
                        <span class="fw-semibold">{{ $homework->academicClass->name ?? 'N/A' }} ({{ $homework->section->name ?? 'N/A' }})</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Subject:</span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">{{ $homework->subject->name ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Total Marks:</span>
                        <span class="fw-bold text-success">{{ $homework->total_marks ?? 100 }} pts</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted small">Due Date:</span>
                        @php $isPast = \Carbon\Carbon::parse($homework->due_date)->isPast(); @endphp
                        <span class="fw-bold text-{{ $isPast ? 'danger' : 'dark' }}">
                            <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($homework->due_date)->format('M d, Y') }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold small text-muted text-uppercase mb-2">Instructions / Description</h6>
                    <div class="p-3 bg-light rounded-3 border small">
                        @if($homework->description)
                            {!! nl2br(e($homework->description)) !!}
                        @else
                            <span class="text-muted fst-italic">No additional instructions provided.</span>
                        @endif
                    </div>
                </div>

                @if($homework->file_path)
                    <div class="d-print-none">
                        <h6 class="fw-bold small text-muted text-uppercase mb-2">Teacher's Attachment</h6>
                        <a href="{{ asset($homework->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill w-100 py-2">
                            <i class="bi bi-paperclip me-1"></i> Download Assignment File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Student Submissions Roster --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title m-0 fw-bold">Student Submissions & Evaluation</h6>
                    <small class="text-muted">{{ $homework->submissions->count() }} of {{ $students->count() }} students submitted</small>
                </div>
                <div class="progress" style="width: 140px; height: 10px;">
                    @php
                        $pct = $students->count() > 0 ? round(($homework->submissions->count() / $students->count()) * 100) : 0;
                    @endphp
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="bg-light text-muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">
                            <tr>
                                <th class="ps-4">Student</th>
                                <th>Submission</th>
                                <th>Submitted Work</th>
                                <th>Score / Marks</th>
                                <th class="text-end pe-4 d-print-none">Evaluation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $st)
                                @php
                                    $submission = $submissionsByStudent->get($st->id);
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-sm rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-muted" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($st->first_name, 0, 1) . substr($st->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $st->full_name }}</div>
                                                <small class="text-muted">Adm: {{ $st->admission_no ?? '#' . $st->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($submission)
                                            @php
                                                $isLate = \Carbon\Carbon::parse($submission->submission_date)->isAfter(\Carbon\Carbon::parse($homework->due_date)->endOfDay());
                                            @endphp
                                            <span class="badge bg-{{ $isLate ? 'warning' : 'success' }}-subtle text-{{ $isLate ? 'warning' : 'success' }} border px-2 py-1">
                                                <i class="bi bi-check-circle me-1"></i>{{ $isLate ? 'Late Submitted' : 'Submitted' }}
                                            </span>
                                            <div class="text-muted" style="font-size: 0.72rem;">
                                                {{ $submission->submission_date->format('M d, h:i A') }}
                                            </div>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                                <i class="bi bi-dash-circle me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission && $submission->file_path)
                                            <a href="{{ asset($submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2 text-decoration-none" style="font-size: 0.75rem;">
                                                <i class="bi bi-file-earmark-arrow-down me-1"></i>View Work
                                            </a>
                                            @if($submission->comments)
                                                <div class="text-muted small text-truncate mt-1" style="max-width: 180px;" title="{{ $submission->comments }}">
                                                    "{{ $submission->comments }}"
                                                </div>
                                            @endif
                                        @elseif($submission && $submission->comments)
                                            <span class="small text-muted fst-italic">"{{ $submission->comments }}"</span>
                                        @else
                                            <span class="text-muted small fst-italic">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission && $submission->status === 'graded')
                                            <span class="fw-bold text-success">{{ $submission->marks_obtained }} / {{ $homework->total_marks ?? 100 }}</span>
                                            @if($submission->feedback)
                                                <div class="text-muted small text-truncate" style="max-width: 150px;" title="{{ $submission->feedback }}">
                                                    <i class="bi bi-chat-left-quote me-1"></i>{{ $submission->feedback }}
                                                </div>
                                            @endif
                                        @elseif($submission)
                                            <span class="badge bg-info-subtle text-info border px-2 py-1">Ungraded</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 d-print-none">
                                        @if($submission)
                                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 btn-grade"
                                                data-id="{{ $submission->id }}"
                                                data-student="{{ $st->full_name }}"
                                                data-marks="{{ $submission->marks_obtained }}"
                                                data-total="{{ $homework->total_marks ?? 100 }}"
                                                data-feedback="{{ $submission->feedback }}">
                                                <i class="bi bi-award me-1"></i>{{ $submission->status === 'graded' ? 'Update Grade' : 'Grade' }}
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-light border text-muted rounded-pill px-3 py-1" disabled>Not Submitted</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No students enrolled in this class/section.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Grade Submission --}}
<div class="modal fade" id="gradeSubmissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="gradeSubmissionForm" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-award text-success me-2"></i>Evaluate Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label text-muted small mb-1">Student</label>
                    <div class="fw-bold fs-6 text-dark" id="modalStudentName"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Marks Obtained <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="marks_obtained" id="modalMarksInput" class="form-control" required min="0" step="0.5" placeholder="e.g. 18">
                        <span class="input-group-text bg-light" id="modalTotalMarks">/ 100</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Teacher Feedback & Remarks</label>
                    <textarea name="feedback" id="modalFeedbackInput" class="form-control" rows="3" placeholder="Provide constructive feedback, praise, or areas of improvement..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success rounded-pill px-4">Save Grade & Feedback</button>
            </div>
        </form>
    </div>
</div>

<style>
@media print {
    .d-print-none { display: none !important; }
    .card { border: 1px solid #ddd !important; box-shadow: none !important; }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-grade').on('click', function() {
        var id = $(this).data('id');
        var student = $(this).data('student');
        var marks = $(this).data('marks');
        var total = $(this).data('total');
        var feedback = $(this).data('feedback') || '';

        var actionUrl = "{{ url('admin/sms/homework/submissions') }}/" + id + "/grade";
        $('#gradeSubmissionForm').attr('action', actionUrl);
        $('#modalStudentName').text(student);
        $('#modalMarksInput').val(marks !== undefined && marks !== null ? marks : '');
        $('#modalMarksInput').attr('max', total);
        $('#modalTotalMarks').text('/ ' + total);
        $('#modalFeedbackInput').val(feedback);

        $('#gradeSubmissionModal').modal('show');
    });
});
</script>
@endpush
@endsection
