@extends('provider.layout.master')

@section('title', 'Global Platform Settings | NSMS Provider')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Global Platform Settings</h4>
            <p class="text-muted small mb-0">Manage system-wide configuration and billing defaults.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Billing & Company Settings</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('provider.settings.update') }}" method="POST">
                        @csrf
                        
                        <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2">Company Information (For Invoices)</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $settings['company_name'] ?? '') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Company Address</label>
                            <input type="text" name="company_address" class="form-control" value="{{ old('company_address', $settings['company_address'] ?? '') }}" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">PAN/VAT Number</label>
                            <input type="text" name="company_pan_vat" class="form-control" value="{{ old('company_pan_vat', $settings['company_pan_vat'] ?? '') }}" required>
                        </div>
                        
                        <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2">Tax Logic</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Tax Calculation Type</label>
                                <select name="tax_type" class="form-select" required>
                                    <option value="none" {{ (old('tax_type', $settings['tax_type'] ?? '') == 'none') ? 'selected' : '' }}>No Tax</option>
                                    <option value="exclusive" {{ (old('tax_type', $settings['tax_type'] ?? '') == 'exclusive') ? 'selected' : '' }}>Tax Exclusive (Add on top)</option>
                                    <option value="inclusive" {{ (old('tax_type', $settings['tax_type'] ?? '') == 'inclusive') ? 'selected' : '' }}>Tax Inclusive (Extract from total)</option>
                                </select>
                                <div class="form-text small">How should tax be calculated when entering renewal amounts?</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" class="form-control" step="0.01" min="0" max="100" value="{{ old('tax_rate', $settings['tax_rate'] ?? '13') }}" required>
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary fw-semibold px-4">
                                <i class="bi bi-save me-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Disable arrows/spinners on number input */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endsection
