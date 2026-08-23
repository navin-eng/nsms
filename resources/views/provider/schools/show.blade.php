@extends('provider.layout.master')

@section('title', $school->name . ' — Tenant Management')
@section('page-title', $school->name)
@section('breadcrumb', 'Schools > Manage')

@section('content')
<div class="mb-4">
    <a href="{{ route('provider.schools.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i> Back to School Directory
    </a>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold mb-0 text-dark">{{ $school->name }}</h4>
                <span class="badge bg-secondary bg-opacity-10 text-secondary border font-monospace">{{ $school->school_code }}</span>
                @if($school->status === 'active')
                    <span class="badge bg-success bg-opacity-15 text-success">Active</span>
                @elseif($school->status === 'trial')
                    <span class="badge bg-info bg-opacity-15 text-info">Trial</span>
                @elseif($school->status === 'suspended')
                    <span class="badge bg-warning bg-opacity-15 text-warning">Suspended</span>
                @else
                    <span class="badge bg-danger bg-opacity-15 text-danger">{{ ucfirst($school->status) }}</span>
                @endif
            </div>
            <p class="text-muted small mb-0 mt-1">Tenant ID #{{ $school->id }} · {{ $school->contact_email }} · Package: <strong>{{ $school->package_name }}</strong></p>
        </div>

        <!-- Quick Status Control Modal Trigger -->
        <div class="d-flex gap-2">
            <button class="btn btn-warning fw-semibold btn-sm px-3" data-bs-toggle="modal" data-bs-target="#statusModal">
                <i class="bi bi-toggles2 me-1"></i> Change Status
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Metrics & Info -->
    <div class="col-lg-7">
        <!-- Live Enrolment Stats -->
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="card-god-metric text-center">
                    <div class="text-muted small fw-semibold text-uppercase">Students</div>
                    <div class="fs-4 fw-bold mono text-success">{{ number_format($school->students_count) }}</div>
                </div>
            </div>
            <div class="col-4">
                <div class="card-god-metric text-center">
                    <div class="text-muted small fw-semibold text-uppercase">Faculty &amp; Staff</div>
                    <div class="fs-4 fw-bold mono text-info">{{ number_format($school->staff_count) }}</div>
                </div>
            </div>
            <div class="col-4">
                <div class="card-god-metric text-center">
                    <div class="text-muted small fw-semibold text-uppercase">Portal Users</div>
                    <div class="fs-4 fw-bold mono text-primary">{{ number_format($school->users_count) }}</div>
                </div>
            </div>
        </div>

        <!-- School Super Admin Details -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="fw-bold mb-0 text-dark">School Super Admin Account</h5>
                @if($superAdmin)
                    <button class="btn btn-outline-danger btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#resetPassModal">
                        <i class="bi bi-key-fill me-1"></i> Reset Password &amp; Mail
                    </button>
                @endif
            </div>

            @if($superAdmin)
                <div class="p-3 rounded-2 border bg-light bg-opacity-50">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-bold text-dark">{{ $superAdmin->name }}</div>
                            <small class="text-muted mono">{{ $superAdmin->email }}</small>
                        </div>
                        <span class="badge bg-primary bg-opacity-15 text-primary">Super Admin</span>
                    </div>
                    <div class="text-muted small pt-2 border-top d-flex justify-content-between align-items-center">
                        <span>Login using: <code>{{ $school->school_code }}</code> + <code>{{ $superAdmin->email }}</code></span>
                        <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-shield-check"></i> Verified</span>
                    </div>
                </div>
            @else
                <div class="text-muted small">No dedicated super admin account found.</div>
            @endif
        </div>

        <!-- Provider Audit Logs for this school -->
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">Tenant Audit Trail</h5>
            <div class="d-flex flex-column gap-2">
                @forelse($auditLogs as $log)
                    <div class="p-2 rounded-2 border bg-light bg-opacity-50">
                        <div class="d-flex justify-content-between small">
                            <span class="mono fw-semibold text-primary" style="font-size:0.75rem;">{{ $log->action }}</span>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $log->created_at->format('M d, H:i') }}</span>
                        </div>
                        <p class="text-muted small mb-0" style="font-size: 0.78rem;">
                            {{ $log->description }}
                        </p>
                    </div>
                @empty
                    <div class="text-muted small">No provider logs for this tenant yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Module Entitlements -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="fw-bold mb-0 text-dark">Module Entitlements</h5>
                <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 0.72rem;">GOD MODE</span>
            </div>
            <p class="text-muted small mb-3">Enable or disable modules authorized under this institution's MoU agreement:</p>

            <form action="{{ route('provider.schools.modules', $school->id) }}" method="POST">
                @csrf
                <div class="d-flex flex-column gap-2 mb-4">
                    @foreach($allModules as $key => $label)
                        @php($isEnabled = $school->hasModule($key))
                        <label class="d-flex align-items-center justify-content-between p-2 rounded-2 border bg-light bg-opacity-50" style="cursor: pointer;">
                            <div>
                                <span class="fw-semibold small {{ $isEnabled ? 'text-dark' : 'text-muted' }}">{{ $label }}</span>
                                <div class="mono text-muted" style="font-size: 0.7rem;">{{ $key }}</div>
                            </div>
                            <input class="form-check-input" type="checkbox" name="modules[]" value="{{ $key }}" {{ $isEnabled ? 'checked' : '' }}>
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-success fw-semibold w-100 py-2">
                    <i class="bi bi-save me-1"></i> Update Module Entitlements
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Update School Operational Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('provider.schools.status', $school->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Target Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $school->status == 'active' ? 'selected' : '' }}>Active (Normal Operational State)</option>
                            <option value="trial" {{ $school->status == 'trial' ? 'selected' : '' }}>Trial (Time-limited access)</option>
                            <option value="suspended" {{ $school->status == 'suspended' ? 'selected' : '' }}>Suspended (Access blocked due to non-payment/policy)</option>
                            <option value="disabled" {{ $school->status == 'disabled' ? 'selected' : '' }}>Disabled (School users cannot use system)</option>
                            <option value="expired" {{ $school->status == 'expired' ? 'selected' : '' }}>Expired (Subscription period ended)</option>
                            <option value="archived" {{ $school->status == 'archived' ? 'selected' : '' }}>Archived (Read-only historical state)</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small text-muted fw-semibold">Reason for Status Change (Audit Trail)</label>
                        <textarea name="reason" rows="3" class="form-control small" placeholder="e.g. MoU renewed, payment overdue, seasonal suspension..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-semibold">Apply Status Change</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom pb-2">
                <div>
                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-key-fill me-1"></i> Reset School Admin Password</h5>
                    <small class="text-muted">Target: {{ $superAdmin->email ?? 'Super Admin' }}</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('provider.schools.reset_password', $school->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small text-muted fw-semibold mb-0">New Password</label>
                            <button type="button" class="btn btn-link p-0 text-decoration-none small text-success fw-semibold" id="autoGenResetBtn" style="font-size: 0.75rem;">
                                <i class="bi bi-magic"></i> Auto-Generate
                            </button>
                        </div>
                        <input type="text" name="new_password" id="resetPasswordInput" class="form-control font-monospace" placeholder="Enter or auto-generate password" value="Nsms@{{ rand(1000, 9999) }}" required>
                    </div>

                    <div class="p-3 rounded-2 border bg-light mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="send_email" value="1" id="sendEmailCheck" checked>
                            <label class="form-check-label small fw-semibold" for="sendEmailCheck">
                                Email credentials to School Administration
                            </label>
                        </div>

                        <div id="recipientEmailWrap">
                            <label class="form-label small text-muted mb-1">Recipient Email Address</label>
                            <input type="email" name="recipient_email" class="form-control form-control-sm" placeholder="school@email.com" value="{{ $school->contact_email ?? $superAdmin->email ?? '' }}">
                            <small class="text-muted" style="font-size: 0.7rem;">Defaults to school's official contact email.</small>
                        </div>
                    </div>

                    <div class="alert alert-warning py-2 px-3 small mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-shield-exclamation fs-5"></i>
                        <div>This action will be permanently recorded in the platform compliance audit trail.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> Update &amp; Dispatch Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('autoGenResetBtn')?.addEventListener('click', function() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
        let pass = 'Nsms@';
        for (let i = 0; i < 6; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        const input = document.getElementById('resetPasswordInput');
        if (input) {
            input.value = pass;
            input.focus();
        }
    });

    document.getElementById('sendEmailCheck')?.addEventListener('change', function() {
        const wrap = document.getElementById('recipientEmailWrap');
        if (wrap) {
            wrap.style.display = this.checked ? 'block' : 'none';
        }
    });
</script>
@endpush
@endsection
