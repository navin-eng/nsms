@extends('provider.layout.master')

@section('title', 'Save Recovery Codes')
@section('page-title', 'Recovery Codes')
@section('breadcrumb', 'Settings > Security > Recovery')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3 border-warning border-top border-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-key-fill text-warning me-2"></i>Save Your Recovery Codes</h5>
                <p class="text-muted small mb-0 mt-1">If you lose your device, these codes are the ONLY way to access your account.</p>
            </div>
            
            <div class="card-body p-4">
                <div class="alert alert-warning d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4 mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Important Security Notice</h6>
                        <p class="small mb-0">Please copy these codes and store them in a secure place, such as a password manager. <strong>Each code can only be used once.</strong> Once you leave this page, you will not be able to see these codes again.</p>
                    </div>
                </div>

                <div class="bg-light p-4 rounded-3 border mb-4">
                    <div class="row text-center font-monospace" style="font-size: 1.1rem; letter-spacing: 2px;" id="recoveryCodesList">
                        @foreach(session('totp_recovery_codes_plain', []) as $code)
                            <div class="col-6 col-md-4 mb-3">
                                <span class="bg-white px-2 py-1 border rounded">{{ $code }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-flex gap-3 justify-content-between align-items-center">
                    <button class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Print Codes
                    </button>
                    
                    <form action="{{ route('provider.security.totp.confirm') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success fw-bold px-4">
                            <i class="bi bi-check2-circle me-1"></i> I have saved these codes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
