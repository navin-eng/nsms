@extends('provider.layout.master')

@section('title', 'Onboard New School')

@section('content')
<div class="mb-4">
    <a href="{{ route('provider.schools.index') }}" class="text-secondary small text-decoration-none mb-2 d-inline-block">
        &larr; Back to School List
    </a>
    <h4 class="fw-bold mb-1">Onboard New School Tenant</h4>
    <p class="text-secondary small mb-0">Provision an isolated school environment, generate School Code &amp; create initial Super Admin.</p>
</div>

<form action="{{ route('provider.schools.store') }}" method="POST">
    @csrf

    <div class="row g-4">
        <!-- School Information -->
        <div class="col-lg-7">
            <div class="card-god p-4 mb-4">
                <h5 class="fw-bold mb-3 text-white">1. Institution Details</h5>

                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold">Institution / School Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-transparent border-secondary border-opacity-25 text-white" placeholder="e.g. Kathmandu Model Academy" value="{{ old('name') }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-semibold">School Code (Unique Identifier) <span class="text-danger">*</span></label>
                        <input type="text" name="school_code" class="form-control bg-transparent border-secondary border-opacity-25 text-white font-monospace" value="{{ old('school_code', $generatedCode) }}" required>
                        <small class="text-secondary" style="font-size: 0.72rem;">Auto-generated unique tenant ID used for login.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-semibold">Subscription Package <span class="text-danger">*</span></label>
                        <select name="package_name" class="form-select bg-transparent border-secondary border-opacity-25 text-white" required>
                            <option value="Basic" class="bg-dark">Basic Edition</option>
                            <option value="Professional" class="bg-dark" selected>Professional Edition</option>
                            <option value="Enterprise" class="bg-dark">Enterprise Edition (All Modules)</option>
                            <option value="Custom" class="bg-dark">Custom MoU Package</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-semibold">Official Contact Email <span class="text-danger">*</span></label>
                        <input type="email" name="contact_email" class="form-control bg-transparent border-secondary border-opacity-25 text-white" placeholder="admin@school.edu.np" value="{{ old('contact_email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-semibold">Contact Phone Number</label>
                        <input type="text" name="contact_phone" class="form-control bg-transparent border-secondary border-opacity-25 text-white" placeholder="+977 98XXXXXXXX" value="{{ old('contact_phone') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold">School Physical Address</label>
                    <input type="text" name="address" class="form-control bg-transparent border-secondary border-opacity-25 text-white" placeholder="City, District, Province" value="{{ old('address') }}">
                </div>

                <div class="mb-0">
                    <label class="form-label small text-secondary fw-semibold">Initial Status</label>
                    <select name="status" class="form-select bg-transparent border-secondary border-opacity-25 text-white">
                        <option value="active" class="bg-dark" selected>Active (Normal Operation)</option>
                        <option value="trial" class="bg-dark">Trial Mode</option>
                        <option value="pending" class="bg-dark">Pending MoU Activation</option>
                    </select>
                </div>
            </div>

            <!-- Initial Super Admin Credentials -->
            <div class="card-god p-4">
                <h5 class="fw-bold mb-3 text-white">2. Initial School Super Admin Account</h5>
                <p class="text-secondary small mb-3">This administrator account will be handed over to the school upon onboarding.</p>

                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold">Administrator Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="admin_name" class="form-control bg-transparent border-secondary border-opacity-25 text-white" placeholder="e.g. Principal Ram Sharma" value="{{ old('admin_name', 'Principal / Super Admin') }}" required>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-semibold">Admin Login Email <span class="text-danger">*</span></label>
                        <input type="email" name="admin_email" class="form-control bg-transparent border-secondary border-opacity-25 text-white" placeholder="principal@school.edu.np" value="{{ old('admin_email') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-semibold">Admin Initial Password <span class="text-danger">*</span></label>
                        <input type="password" name="admin_password" class="form-control bg-transparent border-secondary border-opacity-25 text-white" placeholder="Minimum 6 characters" value="password123" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Entitlements Checklist -->
        <div class="col-lg-5">
            <div class="card-god p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-white">3. Enabled Modules</h5>
                    <span class="badge bg-secondary bg-opacity-25 text-secondary" style="font-size: 0.72rem;">ENTITLEMENTS</span>
                </div>
                <p class="text-secondary small mb-3">Check the modules authorized under this institution's MoU agreement:</p>

                <div class="d-flex flex-column gap-2">
                    @foreach($allModules as $key => $label)
                        <label class="d-flex align-items-center justify-content-between p-2 rounded-2" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle); cursor: pointer;">
                            <div>
                                <span class="fw-semibold small text-white">{{ $label }}</span>
                                <div class="mono text-secondary" style="font-size: 0.7rem;">module: {{ $key }}</div>
                            </div>
                            <input class="form-check-input" type="checkbox" name="modules[]" value="{{ $key }}" checked>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Notes & Submit -->
            <div class="card-god p-4">
                <div class="mb-3">
                    <label class="form-label small text-secondary fw-semibold">Internal Provider Notes</label>
                    <textarea name="admin_notes" rows="3" class="form-control bg-transparent border-secondary border-opacity-25 text-white small" placeholder="MoU reference number, sales rep details, special terms..."></textarea>
                </div>

                <button type="submit" class="btn btn-emerald w-100 py-3">
                    <i class="bi bi-shield-check me-1"></i> Provision &amp; Onboard School Tenant
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
