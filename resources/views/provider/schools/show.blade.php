@extends('provider.layout.master')

@section('title', $school->name . ' — School Overview')

@section('content')
<div class="mb-4">
    <a href="{{ route('provider.schools.index') }}" class="text-secondary small text-decoration-none mb-2 d-inline-block">
        &larr; Back to School List
    </a>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold mb-0">{{ $school->name }}</h4>
                <span class="badge bg-secondary bg-opacity-25 font-monospace text-white">{{ $school->school_code }}</span>
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
            <p class="text-secondary small mb-0 mt-1">Tenant ID #{{ $school->id }} · {{ $school->contact_email }} · Package: <strong>{{ $school->package_name }}</strong></p>
        </div>

        <!-- Quick Status Control Modal Trigger -->
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#statusModal">
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
                <div class="stat-god-card">
                    <div class="text-secondary small">Students</div>
                    <div class="fs-4 fw-bold mono text-white">{{ number_format($school->students_count) }}</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-god-card">
                    <div class="text-secondary small">Faculty &amp; Staff</div>
                    <div class="fs-4 fw-bold mono text-white">{{ number_format($school->staff_count) }}</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-god-card">
                    <div class="text-secondary small">School Users</div>
                    <div class="fs-4 fw-bold mono text-white">{{ number_format($school->users_count) }}</div>
                </div>
            </div>
        </div>

        <!-- School Super Admin Details -->
        <div class="card-god p-4 mb-4">
            <h5 class="fw-bold mb-3 text-white">School Super Admin Account</h5>
            @php($superAdmin = $school->users->first())
            @if($superAdmin)
                <div class="p-3 rounded-2" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-bold text-white">{{ $superAdmin->name }}</div>
                            <small class="text-secondary mono">{{ $superAdmin->email }}</small>
                        </div>
                        <span class="badge bg-primary bg-opacity-15 text-primary">Super Admin</span>
                    </div>
                    <div class="text-secondary small pt-2 border-top border-secondary border-opacity-10">
                        Login using: <code>{{ $school->school_code }}</code> + <code>{{ $superAdmin->email }}</code>
                    </div>
                </div>
            @else
                <div class="text-secondary small">No dedicated super admin account found.</div>
            @endif
        </div>

        <!-- Provider Audit Logs for this school -->
        <div class="card-god p-4">
            <h5 class="fw-bold mb-3 text-white">Recent Provider Actions on this Tenant</h5>
            <div class="d-flex flex-column gap-2">
                @forelse($auditLogs as $log)
                    <div class="p-2 rounded-2" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle);">
                        <div class="d-flex justify-content-between small">
                            <span class="mono fw-semibold text-emerald-400" style="color:var(--emerald-400);">{{ $log->action }}</span>
                            <span class="text-secondary" style="font-size: 0.72rem;">{{ $log->created_at->format('M d, H:i') }}</span>
                        </div>
                        <p class="text-secondary small mb-0" style="font-size: 0.78rem;">
                            {{ $log->description }}
                        </p>
                    </div>
                @empty
                    <div class="text-secondary small">No provider logs for this tenant.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Module Entitlements -->
    <div class="col-lg-5">
        <div class="card-god p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-white">Module Entitlements</h5>
                <span class="badge bg-secondary bg-opacity-25 text-secondary" style="font-size: 0.72rem;">GOD MODE</span>
            </div>
            <p class="text-secondary small mb-3">Toggle modules enabled for this school's package / MoU:</p>

            <form action="{{ route('provider.schools.modules', $school->id) }}" method="POST">
                @csrf
                <div class="d-flex flex-column gap-2 mb-4">
                    @foreach($allModules as $key => $label)
                        @php($isEnabled = $school->hasModule($key))
                        <label class="d-flex align-items-center justify-content-between p-2 rounded-2" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle); cursor: pointer;">
                            <div>
                                <span class="fw-semibold small {{ $isEnabled ? 'text-white' : 'text-secondary' }}">{{ $label }}</span>
                                <div class="mono text-secondary" style="font-size: 0.7rem;">{{ $key }}</div>
                            </div>
                            <input class="form-check-input" type="checkbox" name="modules[]" value="{{ $key }}" {{ $isEnabled ? 'checked' : '' }}>
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-emerald w-100">
                    <i class="bi bi-save me-1"></i> Update Module Entitlements
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-strong);">
            <div class="modal-header border-secondary border-opacity-10">
                <h5 class="modal-title fw-bold text-white">Update School Operational Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('provider.schools.status', $school->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-secondary fw-semibold">Target Status</label>
                        <select name="status" class="form-select bg-dark border-secondary border-opacity-25 text-white">
                            <option value="active" {{ $school->status == 'active' ? 'selected' : '' }}>Active (Normal Operational State)</option>
                            <option value="trial" {{ $school->status == 'trial' ? 'selected' : '' }}>Trial (Time-limited access)</option>
                            <option value="suspended" {{ $school->status == 'suspended' ? 'selected' : '' }}>Suspended (Access blocked due to non-payment/policy)</option>
                            <option value="disabled" {{ $school->status == 'disabled' ? 'selected' : '' }}>Disabled (School users cannot use system)</option>
                            <option value="expired" {{ $school->status == 'expired' ? 'selected' : '' }}>Expired (Subscription period ended)</option>
                            <option value="archived" {{ $school->status == 'archived' ? 'selected' : '' }}>Archived (Read-only historical state)</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small text-secondary fw-semibold">Reason for Status Change (Audit Trail)</label>
                        <textarea name="reason" rows="3" class="form-control bg-dark border-secondary border-opacity-25 text-white small" placeholder="e.g. MoU renewed, payment overdue, seasonal suspension..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-10">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Apply Status Change</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
