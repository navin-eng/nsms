@extends('backend.pages.layout.master')
@section('title', 'Certificate History')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Issued Certificates Record</h5>
        <p class="text-muted small mb-0">Manage and verify Character, Transfer, Bonafide, and Merit certificates.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sms.certificates.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Issue New Certificate
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('sms.certificates.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search by Cert No, Student Name, Adm No...">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Certificate Types</option>
                    <option value="character" {{ request('type') == 'character' ? 'selected' : '' }}>Character Certificate</option>
                    <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>Transfer Certificate (TC)</option>
                    <option value="bonafide" {{ request('type') == 'bonafide' ? 'selected' : '' }}>Bonafide Certificate</option>
                    <option value="completion" {{ request('type') == 'completion' ? 'selected' : '' }}>Course Completion</option>
                    <option value="merit" {{ request('type') == 'merit' ? 'selected' : '' }}>Merit / Achievement</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Active / Issued</option>
                    <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('sms.certificates.index') }}" class="btn btn-sm btn-light border"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($certificates->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-muted" style="font-size: 0.75rem; text-transform: uppercase;">
                    <tr>
                        <th class="ps-4">Cert #</th>
                        <th>Student Details</th>
                        <th>Type & Title</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                        <th>Issued By</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($certificates as $cert)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold font-monospace text-primary">{{ $cert->certificate_no }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $cert->student->full_name }}</div>
                            <small class="text-muted">Adm: {{ $cert->student->admission_no ?? '#' . $cert->student->id }} | Class: {{ $cert->student->currentEnrollment?->academicClass?->name ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">{{ ucfirst($cert->type) }}</span>
                            <div class="small text-muted mt-1">{{ $cert->title }}</div>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $cert->issue_date->format('M d, Y') }}</div>
                        </td>
                        <td>
                            @if($cert->status === 'issued')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle me-1"></i>Valid</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="bi bi-x-circle me-1"></i>Revoked</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $cert->issuer?->name ?? 'Admin' }}</small>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('sms.certificates.print', $cert->id) }}" target="_blank" class="btn btn-light border text-primary" title="Print Official Certificate">
                                    <i class="bi bi-printer"></i> Print
                                </a>
                                <a href="{{ $cert->verification_url }}" target="_blank" class="btn btn-light border text-info" title="Test Public QR Verification">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                                @if($cert->status === 'issued')
                                    <button type="button" class="btn btn-light border text-warning btn-revoke" data-id="{{ $cert->id }}" data-no="{{ $cert->certificate_no }}" title="Revoke Certificate">
                                        <i class="bi bi-shield-x"></i>
                                    </button>
                                @endif
                                <form action="{{ route('sms.certificates.destroy', $cert->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this certificate permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light border text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $certificates->withQueryString()->links() }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-award fs-1 d-block mb-3 opacity-50"></i>
            <h6>No Certificates Issued</h6>
            <p class="small mb-3">Generate transfer, character, bonafide, or merit certificates with QR verification.</p>
            <a href="{{ route('sms.certificates.create') }}" class="btn btn-sm btn-primary">Issue First Certificate</a>
        </div>
        @endif
    </div>
</div>

{{-- Modal: Revoke Certificate --}}
<div class="modal fade" id="revokeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="revokeForm" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-shield-exclamation me-2"></i>Revoke Certificate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Are you sure you want to revoke certificate <strong id="revokeCertNo"></strong>? Anyone scanning its verification QR code will be notified that it has been revoked.</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Reason for Revocation <span class="text-danger">*</span></label>
                    <textarea name="revocation_reason" class="form-control" rows="3" required placeholder="e.g. Issued with incorrect student name; replaced by CERT-2026-0005."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger px-4">Revoke Certificate</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-revoke').on('click', function() {
        var id = $(this).data('id');
        var certNo = $(this).data('no');
        var actionUrl = "{{ url('admin/sms/certificates') }}/" + id + "/revoke";
        $('#revokeForm').attr('action', actionUrl);
        $('#revokeCertNo').text(certNo);
        $('#revokeModal').modal('show');
    });
});
</script>
@endpush
@endsection
