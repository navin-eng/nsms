@extends('provider.layout.master')
@section('title', 'Edit School: ' . $school->name)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Edit School Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb fs-7 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('provider.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('provider.schools.index') }}">Schools</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('provider.schools.show', $school->id) }}">{{ $school->school_code }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('provider.schools.show', $school->id) }}" class="btn btn-outline-secondary fw-semibold btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Overview
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('provider.schools.update', $school->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="fw-bold mb-3 text-secondary">Basic Information</h6>
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-semibold">School Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $school->name) }}" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small text-muted fw-semibold">Custom Domain/Subdomain URL</label>
                            <input type="text" name="domain" class="form-control" placeholder="e.g. school.nsms.cloud or www.school.edu.np" value="{{ old('domain', $school->domain) }}">
                            <div class="form-text fs-7">If specified, the school can be accessed directly from this domain.</div>
                        </div>

                        <hr class="border-secondary opacity-10 my-4">

                        <h6 class="fw-bold mb-3 text-secondary">Contact Details</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Contact Email <span class="text-danger">*</span></label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $school->contact_email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $school->contact_phone) }}">
                            </div>
                        </div>

                        <hr class="border-secondary opacity-10 my-4">

                        <h6 class="fw-bold mb-3 text-secondary">Location / Address</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Province</label>
                                <select name="province" id="provinceSelect" class="form-select">
                                    <option value="">Select Province</option>
                                    @foreach($nepalLocations as $provName => $districts)
                                        <option value="{{ $provName }}" {{ (str_contains($school->address, $provName) || old('province') == $provName) ? 'selected' : '' }}>{{ $provName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">District</label>
                                <select name="district" id="districtSelect" class="form-select">
                                    <option value="">Select District</option>
                                    <!-- Populated via JS -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Municipality / City</label>
                                <select name="municipality" id="municipalitySelect" class="form-select">
                                    <option value="">Select Municipality</option>
                                    <!-- Populated via JS -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-semibold">Ward & Street Address</label>
                                <div class="input-group">
                                    <input type="text" name="ward_no" class="form-control" placeholder="Ward No" style="max-width: 90px;" value="{{ old('ward_no') }}">
                                    <input type="text" name="street_address" class="form-control" placeholder="Tole / Street Name" value="{{ old('street_address', $school->address) }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('provider.schools.show', $school->id) }}" class="btn btn-light fw-semibold">Cancel</a>
                            <button type="submit" class="btn btn-primary fw-semibold">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> Read-Only Attributes</h6>
                    <div class="mb-3">
                        <label class="small text-muted fw-semibold d-block">School Code</label>
                        <span class="fw-bold text-dark font-monospace">{{ $school->school_code }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-semibold d-block">Tenant Slug</label>
                        <span class="fw-bold text-dark">{{ $school->slug }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-semibold d-block">Current Package</label>
                        <span class="fw-bold text-dark">{{ $school->package_name }}</span>
                    </div>
                    <div class="mb-0">
                        <label class="small text-muted fw-semibold d-block">System Status</label>
                        <span class="fw-bold text-dark">{{ ucfirst($school->status) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const locationData = @json($nepalLocations);
    
    const provinceSelect = document.getElementById('provinceSelect');
    const districtSelect = document.getElementById('districtSelect');
    const municipalitySelect = document.getElementById('municipalitySelect');

    function populateDistricts() {
        districtSelect.innerHTML = '<option value="">Select District</option>';
        municipalitySelect.innerHTML = '<option value="">Select Municipality</option>';
        
        const province = provinceSelect.value;
        if (province && locationData[province]) {
            Object.keys(locationData[province]).forEach(district => {
                const opt = document.createElement('option');
                opt.value = district;
                opt.textContent = district;
                districtSelect.appendChild(opt);
            });
        }
    }

    function populateMunicipalities() {
        municipalitySelect.innerHTML = '<option value="">Select Municipality</option>';
        
        const province = provinceSelect.value;
        const district = districtSelect.value;
        
        if (province && district && locationData[province][district]) {
            locationData[province][district].forEach(mun => {
                const opt = document.createElement('option');
                opt.value = mun;
                opt.textContent = mun;
                municipalitySelect.appendChild(opt);
            });
        }
    }

    provinceSelect.addEventListener('change', populateDistricts);
    districtSelect.addEventListener('change', populateMunicipalities);
</script>
@endpush
@endsection
