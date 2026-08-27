@extends('provider.layout.master')

@section('title', 'Security Settings')
@section('page-title', 'Two-Factor Authentication')
@section('breadcrumb', 'Settings > Security')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock text-success me-2"></i>MFA Configuration</h5>
                <p class="text-muted small mb-0 mt-1">Manage how you securely authenticate to the God Mode console.</p>
            </div>
            
            <div class="card-body p-4">
                @if (session('success'))
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3 border mb-4">
                    <i class="bi bi-info-circle-fill text-primary fs-4 mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Current Active Method: 
                            @if($user->mfa_method === 'totp')
                                <span class="badge bg-success">Authenticator App (TOTP)</span>
                            @else
                                <span class="badge bg-info">Email OTP</span>
                            @endif
                        </h6>
                        <p class="small text-muted mb-0">God mode requires all provider staff to use 2FA. You cannot disable 2FA entirely.</p>
                    </div>
                </div>

                @if($user->mfa_method === 'email')
                    <h6 class="fw-bold mb-3">Upgrade to Authenticator App</h6>
                    <p class="text-muted small mb-4">Switching to an authenticator app (like Google Authenticator, Authy, or 1Password) is more secure than Email OTP. Scan the QR code below to set it up.</p>
                    
                    <div class="text-center mb-4 p-4 border rounded-3 bg-white d-inline-block shadow-sm">
                        {!! $qrCodeUrl !!}
                        <p class="small text-muted mt-3 mb-0 font-monospace">Secret: {{ $newSecret }}</p>
                    </div>

                    <form action="{{ route('provider.security.totp.enable') }}" method="POST" class="mx-auto" style="max-width: 300px;">
                        @csrf
                        <input type="hidden" name="secret" value="{{ $newSecret }}">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Enter 6-digit code from App to verify</label>
                            <input type="text" name="code" class="form-control text-center font-monospace" placeholder="••••••" maxlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            Verify &amp; Enable Authenticator
                        </button>
                    </form>
                @else
                    <h6 class="fw-bold mb-3">Revert to Email Authentication</h6>
                    <p class="text-muted small mb-4">If you have lost access to your authenticator app, or prefer receiving codes via email, you can revert back. This will instantly invalidate your current authenticator app connection.</p>
                    
                    <form action="{{ route('provider.security.revert_email') }}" method="POST" onsubmit="return confirm('Are you sure you want to revert to Email OTP? Your current authenticator app will no longer work.');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger fw-semibold">
                            <i class="bi bi-envelope me-1"></i> Switch back to Email OTP
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
