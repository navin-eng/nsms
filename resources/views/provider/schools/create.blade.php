@extends('provider.layout.master')

@section('title', 'Onboard New School')
@section('page-title', 'Onboard New School Tenant')
@section('breadcrumb', 'Schools > Onboard')

@section('content')
<div class="mb-4">
    <a href="{{ route('provider.schools.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="bi bi-arrow-left me-1"></i> Back to School Directory
    </a>
    <h4 class="fw-bold mb-1">Provision New School Tenant</h4>
    <p class="text-muted small mb-0">Provision an isolated school environment, generate School Code &amp; create initial Super Admin.</p>
</div>

<form action="{{ route('provider.schools.store') }}" method="POST">
    @csrf

    <div class="row g-4">
        <!-- School Information -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">1. Institution Details</h5>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-semibold">Institution / School Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Kathmandu Model Academy" value="{{ old('name') }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-semibold">School Code (Unique Tenant ID) <span class="text-danger">*</span></label>
                        <input type="text" name="school_code" class="form-control font-monospace bg-light" value="{{ old('school_code', $generatedCode) }}" required>
                        <small class="text-muted" style="font-size: 0.72rem;">Unique tenant identifier used for multi-tenant login.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-semibold">Subscription Package <span class="text-danger">*</span></label>
                        <select name="package_name" class="form-select" required>
                            <option value="Basic">Basic Edition</option>
                            <option value="Professional" selected>Professional Edition</option>
                            <option value="Enterprise">Enterprise Edition (All Modules)</option>
                            <option value="Custom">Custom MoU Package</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-semibold">Official Contact Email <span class="text-danger">*</span></label>
                        <input type="email" name="contact_email" class="form-control" placeholder="admin@school.edu.np" value="{{ old('contact_email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-semibold">Contact Phone Number</label>
                        <input type="text" name="contact_phone" class="form-control" placeholder="+977 98XXXXXXXX" value="{{ old('contact_phone') }}">
                    </div>
                </div>

                <!-- Dynamic Nepali Administrative Location Selectors -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-semibold">Province (प्रदेश)</label>
                        <select name="province" id="provinceSelect" class="form-select">
                            <option value="">-- Select Province --</option>
                            @foreach($nepalLocations as $provinceName => $districts)
                                <option value="{{ $provinceName }}" {{ old('province') == $provinceName ? 'selected' : '' }}>{{ $provinceName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-semibold">District (जिल्ला)</label>
                        <select name="district" id="districtSelect" class="form-select" disabled>
                            <option value="">-- Select District --</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-semibold">Municipality (पालिका)</label>
                        <select name="municipality" id="municipalitySelect" class="form-select" disabled>
                            <option value="">-- Select Municipality --</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted fw-semibold">Ward No. (वडा नं.)</label>
                        <input type="number" name="ward_no" class="form-control" placeholder="e.g. 5" min="1" max="35" value="{{ old('ward_no') }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small text-muted fw-semibold">Tole / Street Address (टोल / सडक)</label>
                        <input type="text" name="street_address" class="form-control" placeholder="e.g. Main Chowk, School Road" value="{{ old('street_address') }}">
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label small text-muted fw-semibold">Initial Status</label>
                    <select name="status" class="form-select">
                        <option value="active" selected>Active (Normal Operation)</option>
                        <option value="trial">Trial Mode</option>
                        <option value="pending">Pending MoU Activation</option>
                    </select>
                </div>
            </div>

            <!-- Initial Super Admin Credentials -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">2. Initial School Super Admin Account</h5>
                <p class="text-muted small mb-3">This administrator account will be handed over to the school upon onboarding.</p>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-semibold">Administrator Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="admin_name" class="form-control" placeholder="e.g. Principal Ram Sharma" value="{{ old('admin_name', 'Principal / Super Admin') }}" required>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-semibold">Admin Login Email <span class="text-danger">*</span></label>
                        <input type="email" name="admin_email" class="form-control" placeholder="principal@school.edu.np" value="{{ old('admin_email') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-muted fw-semibold">Admin Initial Password <span class="text-danger">*</span></label>
                        <input type="password" name="admin_password" class="form-control" placeholder="Minimum 6 characters" value="password123" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Entitlements Checklist -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="fw-bold mb-0 text-dark">3. Enabled Modules</h5>
                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 0.72rem;">ENTITLEMENTS</span>
                </div>
                <p class="text-muted small mb-3">Select the modules authorized under this institution's MoU agreement:</p>

                <div class="d-flex flex-column gap-2">
                    @foreach($allModules as $key => $label)
                        <label class="d-flex align-items-center justify-content-between p-2 rounded-2 border bg-light bg-opacity-50" style="cursor: pointer;">
                            <div>
                                <span class="fw-semibold small text-dark">{{ $label }}</span>
                                <div class="mono text-muted" style="font-size: 0.7rem;">module: {{ $key }}</div>
                            </div>
                            <input class="form-check-input" type="checkbox" name="modules[]" value="{{ $key }}" checked>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Notes & Submit -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <div class="mb-3">
                    <label class="form-label small text-muted fw-semibold">Internal Provider Notes</label>
                    <textarea name="admin_notes" rows="3" class="form-control small" placeholder="MoU reference number, sales rep details, special terms..."></textarea>
                </div>

                <button type="submit" class="btn btn-success fw-bold w-100 py-3">
                    <i class="bi bi-shield-check me-1"></i> Provision &amp; Onboard School Tenant
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    const nepalHierarchy = @json($nepalLocations);
    const provinceSelect = document.getElementById('provinceSelect');
    const districtSelect = document.getElementById('districtSelect');
    const municipalitySelect = document.getElementById('municipalitySelect');

    provinceSelect?.addEventListener('change', function() {
        const province = this.value;
        districtSelect.innerHTML = '<option value="">-- Select District --</option>';
        municipalitySelect.innerHTML = '<option value="">-- Select Municipality --</option>';
        municipalitySelect.disabled = true;

        if (province && nepalHierarchy[province]) {
            districtSelect.disabled = false;
            Object.keys(nepalHierarchy[province]).sort().forEach(dist => {
                const opt = document.createElement('option');
                opt.value = dist;
                opt.textContent = dist;
                districtSelect.appendChild(opt);
            });
        } else {
            districtSelect.disabled = true;
        }
    });

    districtSelect?.addEventListener('change', function() {
        const province = provinceSelect.value;
        const district = this.value;
        municipalitySelect.innerHTML = '<option value="">-- Select Municipality --</option>';

        if (province && district && nepalHierarchy[province] && nepalHierarchy[province][district]) {
            municipalitySelect.disabled = false;
            nepalHierarchy[province][district].sort().forEach(mun => {
                const opt = document.createElement('option');
                opt.value = mun;
                opt.textContent = mun;
                municipalitySelect.appendChild(opt);
            });
        } else {
            municipalitySelect.disabled = true;
        }
    });
</script>
@endpush
@endsection
