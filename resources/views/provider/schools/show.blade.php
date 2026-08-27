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
                    <span class="badge bg-success text-white">Active</span>
                @elseif($school->status === 'trial')
                    <span class="badge bg-info text-dark">Trial</span>
                @elseif($school->status === 'suspended')
                    <span class="badge bg-warning text-dark">Suspended</span>
                @else
                    <span class="badge bg-danger text-white">{{ ucfirst($school->status) }}</span>
                @endif
            </div>
            <p class="text-muted small mb-0 mt-1">Tenant ID #{{ $school->id }} · {{ $school->contact_email }} · Package: <strong>{{ $school->package_name }}</strong></p>
        </div>

        <!-- Quick Status Control Modal Trigger -->
        <div class="d-flex gap-2">
            @if(auth('provider')->user()->can('provider_manage_schools'))
            <a href="{{ route('provider.schools.edit', $school->id) }}" class="btn btn-outline-primary fw-semibold btn-sm px-3">
                <i class="bi bi-pencil-square me-1"></i> Edit School
            </a>
            @endif
            @if(auth('provider')->user()->can('provider_support_tools') || auth('provider')->user()->can('provider_manage_billing'))
            <button class="btn btn-warning fw-semibold btn-sm px-3" data-bs-toggle="modal" data-bs-target="#statusModal">
                <i class="bi bi-toggles2 me-1"></i> Change Status
            </button>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Metrics & Info -->
    <div class="col-lg-7">
        <!-- Live Enrolment Stats -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card-god-metric text-center p-3 border rounded-3 bg-white shadow-sm h-100">
                    <div class="text-muted small fw-semibold text-uppercase">Students</div>
                    <div class="fs-4 fw-bold mono text-success">{{ number_format($studentsCount) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-god-metric text-center p-3 border rounded-3 bg-white shadow-sm h-100">
                    <div class="text-muted small fw-semibold text-uppercase">Faculty</div>
                    <div class="fs-4 fw-bold mono text-info">{{ number_format($staffCount) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-god-metric text-center p-3 border rounded-3 bg-white shadow-sm h-100">
                    <div class="text-muted small fw-semibold text-uppercase">Portal Users</div>
                    <div class="fs-4 fw-bold mono text-primary">{{ number_format($usersCount) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-god-metric text-center p-3 border rounded-3 bg-white shadow-sm h-100">
                    <div class="text-muted small fw-semibold text-uppercase">Classes</div>
                    <div class="fs-4 fw-bold mono text-warning">{{ number_format($classesCount ?? 0) }}</div>
                </div>
            </div>
        </div>

        <!-- School Super Admin Details -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="fw-bold mb-0 text-dark">School Super Admin Account</h5>
                @if($superAdmin)
                    @if(auth('provider')->user()->can('provider_support_tools'))
                    <button class="btn btn-outline-danger btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#resetPassModal">
                        <i class="bi bi-key-fill me-1"></i> Reset Password &amp; Mail
                    </button>
                    @endif
                @endif
            </div>

            @if(session('new_password'))
                <div class="alert alert-success border-success bg-success bg-opacity-10 d-flex align-items-center mb-3">
                    <i class="bi bi-shield-lock fs-3 text-success me-3"></i>
                    <div>
                        <strong class="d-block text-success mb-1">Generated Credentials (Please copy & save securely!)</strong>
                        <div class="font-monospace small">
                            Username / Email: <strong>{{ $superAdmin->email ?? 'N/A' }}</strong> <br>
                            Password: <strong>{{ session('new_password') }}</strong>
                        </div>
                    </div>
                </div>
            @endif

            @if($superAdmin)
                <div class="p-3 rounded-2 border bg-light bg-opacity-50">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-bold text-dark">{{ $superAdmin->name }}</div>
                            <small class="text-muted mono">{{ $superAdmin->email }}</small>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success">Super Admin</span>
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

        <!-- Billing History -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="fw-bold mb-0 text-dark">Billing History</h5>
            </div>
            @if($school->invoices && $school->invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr class="text-muted" style="font-size: 0.8rem;">
                                <th>Invoice</th>
                                <th>Amount</th>
                                <th>Package</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.85rem;">
                            @foreach($school->invoices()->latest()->get() as $invoice)
                                <tr>
                                    <td class="mono fw-semibold text-primary">{{ $invoice->invoice_number }}</td>
                                    <td>NPR {{ number_format($invoice->amount, 2) }}</td>
                                    <td>{{ $invoice->package_name }}</td>
                                    <td>
                                        @if($invoice->status == 'paid')
                                            <span class="badge bg-success bg-opacity-10 text-success">Paid</span>
                                        @elseif($invoice->status == 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ ucfirst($invoice->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $invoice->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('provider.billing.print', $invoice->id) }}" target="_blank" class="btn btn-outline-secondary" title="Print Invoice">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            @php
                                                $waMessage = "Hello from NSMS. Here is your latest invoice ({$invoice->invoice_number}) for NPR " . number_format($invoice->amount, 2) . ". You can view it here: " . route('provider.billing.print', $invoice->id);
                                            @endphp
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $school->phone) }}?text={{ urlencode($waMessage) }}" target="_blank" class="btn btn-outline-success" title="Share via WhatsApp">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-muted small">No invoices generated for this school yet.</div>
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

    <!-- Right Column: Module Entitlements & Billing -->
    <div class="col-lg-5">
        <!-- Billing & Subscription -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="fw-bold mb-0 text-dark">Subscription Plan</h5>
                @if(auth('provider')->user()->can('provider_manage_billing'))
                <button class="btn btn-outline-success btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#renewModal">
                    <i class="bi bi-calendar-check me-1"></i> Renew Package
                </button>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Current Package</span>
                <span class="fw-bold fs-6">{{ $school->package_name }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold">Starts</span>
                <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($school->subscription_start)->format('M d, Y') }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small fw-semibold">Expires</span>
                @if(now()->gt(\Carbon\Carbon::parse($school->subscription_end)))
                    <span class="fw-bold text-danger">{{ \Carbon\Carbon::parse($school->subscription_end)->format('M d, Y') }} (Expired)</span>
                @else
                    <span class="fw-bold text-success">{{ \Carbon\Carbon::parse($school->subscription_end)->format('M d, Y') }}</span>
                @endif
            </div>
        </div>

        <!-- Module Entitlements -->
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
                            <input class="form-check-input" type="checkbox" name="modules[]" value="{{ $key }}" {{ $isEnabled ? 'checked' : '' }} {{ !auth('provider')->user()->can('provider_manage_modules') ? 'disabled' : '' }}>
                        </label>
                    @endforeach
                </div>

                @if(auth('provider')->user()->can('provider_manage_modules'))
                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">Save Module Configuration</button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Renew Package Modal -->
<div class="modal fade" id="renewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Renew / Upgrade Subscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('provider.schools.renew', $school->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Target Package</label>
                        <select name="package_name" class="form-select">
                            <option value="Basic" {{ $school->package_name == 'Basic' ? 'selected' : '' }}>Basic</option>
                            <option value="Professional" {{ $school->package_name == 'Professional' ? 'selected' : '' }}>Professional</option>
                            <option value="Enterprise" {{ $school->package_name == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
                            <option value="Custom" {{ $school->package_name == 'Custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">New Expiry Date</label>
                        <input type="date" name="subscription_end" class="form-control" 
                            value="{{ \Carbon\Carbon::parse($school->subscription_end)->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-semibold">Billing Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">NPR</span>
                            <input type="number" name="billing_amount" class="form-control" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="alert alert-info py-2 small mb-0">
                        <i class="bi bi-info-circle me-1"></i> If the school is currently expired/suspended, extending the date will automatically reactivate them.
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-semibold">Confirm Renewal</button>
                </div>
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
                        <input type="text" name="new_password" id="resetPasswordInput" class="form-control font-monospace" placeholder="Enter or auto-generate password" value="Nsms{{ '@' . rand(1000, 9999) }}" required>
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
