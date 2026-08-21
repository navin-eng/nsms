@extends('backend.pages.layout.master')
@section('title', 'Issue Certificate')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Issue Student Certificate</h5>
        <p class="text-muted small mb-0">Generate verified Character, Transfer, Bonafide, or Achievement certificates with QR code verification.</p>
    </div>
    <div>
        <a href="{{ route('sms.certificates.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Certificate History
        </a>
    </div>
</div>

<form action="{{ route('sms.certificates.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><span class="badge bg-primary me-2">1</span>Student & Certificate Type</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Select Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="studentSelect" class="form-select select2" required>
                                <option value="">-- Choose Student --</option>
                                @foreach($students as $st)
                                    <option value="{{ $st->id }}" {{ (request('student_id') == $st->id || ($selectedStudent && $selectedStudent->id == $st->id)) ? 'selected' : '' }}>
                                        {{ $st->full_name }} (Adm: {{ $st->admission_no ?? '#' . $st->id }} | Class: {{ $st->currentEnrollment?->academicClass?->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Certificate Type <span class="text-danger">*</span></label>
                            <select name="type" id="certTypeSelect" class="form-select" required>
                                <option value="character">Character & Conduct Certificate</option>
                                <option value="transfer">Transfer Certificate (TC)</option>
                                <option value="bonafide">Bonafide / Study Certificate</option>
                                <option value="completion">Course / Class Completion</option>
                                <option value="merit">Merit & Excellence Certificate</option>
                                <option value="custom">Custom Certificate</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Certificate Title / Heading <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="certTitleInput" class="form-control" value="Character & Conduct Certificate" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><span class="badge bg-primary me-2">2</span>Certificate Content & Custom Details</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6" id="conductGroup">
                            <label class="form-label fw-semibold">Character / Conduct Assessment</label>
                            <input type="text" name="conduct" class="form-control" value="Good and Exemplary" placeholder="e.g. Good and Satisfactory">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Academic Session / Year</label>
                            <input type="text" name="session_year" class="form-control" value="{{ date('Y') }}" placeholder="e.g. 2025-2026">
                        </div>

                        <div class="col-12" id="reasonGroup" style="display:none;">
                            <label class="form-label fw-semibold">Reason for Leaving (for Transfer Certificate)</label>
                            <input type="text" name="reason" class="form-control" placeholder="e.g. Parent relocation / Completed highest class">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Additional Remarks / Custom Statement</label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Any special achievement, attendance note, or custom endorsement text..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                <i class="bi bi-award me-1"></i>Generate & Print Certificate
            </button>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm border-top border-primary border-3">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-shield-check text-primary me-2"></i>QR Code Verification</h6>
                </div>
                <div class="card-body p-4 text-muted small">
                    <p>Every certificate generated by the system automatically includes:</p>
                    <ul class="ps-3 mb-3">
                        <li>A unique serial number (e.g. <code>CERT-2026-0001</code>).</li>
                        <li>An authentic vector QR code embedded on the printed document.</li>
                        <li>A secure verification landing page confirming student identity, issue date, and legitimacy.</li>
                    </ul>
                    <div class="alert alert-info py-2 px-3 small mb-0">
                        <i class="bi bi-info-circle me-1"></i> Certificates can be revoked at any time from the Certificate History log if necessary.
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({ width: '100%' });

    const presets = {
        'character': 'Character & Conduct Certificate',
        'transfer': 'School Leaving & Transfer Certificate',
        'bonafide': 'Bonafide Student Certificate',
        'completion': 'Certificate of Course Completion',
        'merit': 'Certificate of Academic Excellence',
        'custom': 'Certificate of Recognition'
    };

    $('#certTypeSelect').on('change', function() {
        const type = $(this).val();
        if (presets[type]) {
            $('#certTitleInput').val(presets[type]);
        }

        if (type === 'transfer') {
            $('#reasonGroup').slideDown(150);
        } else {
            $('#reasonGroup').slideUp(150);
        }
    });
});
</script>
@endpush
@endsection
