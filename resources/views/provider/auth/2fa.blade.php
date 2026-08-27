<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication - NSMS God Mode</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;700;900&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
        }
        .mfa-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #10b981;
            margin: 0 auto 1.5rem;
        }
        .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 24px;
            letter-spacing: 8px;
            text-align: center;
            font-weight: 600;
        }
        .form-control:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            color: white;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.2);
            letter-spacing: normal;
            font-size: 16px;
            font-weight: 400;
        }
        .btn-verify {
            background: linear-gradient(to right, #10b981, #059669);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.2s ease;
        }
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(16, 185, 129, 0.6);
        }
    </style>
</head>
<body>

<div class="mfa-card text-center">
    <div class="icon-circle">
        <i class="bi bi-shield-lock-fill"></i>
    </div>
    <h3 class="fw-bold mb-2">Two-Factor Auth</h3>
    
    @if($user->mfa_method === 'totp')
        <p class="text-white-50 small mb-4">Open your <strong>Authenticator App</strong> and enter the 6-digit code for your account.</p>
        <p class="text-white-50 small mb-4" style="font-size: 0.75rem;">(Lost your device? You can enter one of your 8-character backup codes instead)</p>
    @else
        <p class="text-white-50 small mb-4">We've sent a 6-digit code to <strong>{{ $user->email }}</strong>. It expires in 10 minutes.</p>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger bg-danger bg-opacity-25 border-danger text-danger small py-2 rounded-3 text-start">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success bg-success bg-opacity-25 border-success text-success small py-2 rounded-3 text-start">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('local_otp'))
        <div class="alert alert-info bg-info bg-opacity-25 border-info text-info small py-2 rounded-3 text-start mb-3">
            <i class="bi bi-code-slash me-1"></i> <strong>Dev Mode Active:</strong> Your OTP is <code>{{ session('local_otp') }}</code>
        </div>
    @endif

    <form action="{{ route('provider.2fa.verify') }}" method="POST">
        @csrf
        <div class="mb-3">
            <input type="text" name="code" class="form-control" placeholder="••••••" maxlength="6" autocomplete="one-time-code" autofocus required>
        </div>
        <button type="submit" class="btn btn-verify">
            Verify &amp; Continue
        </button>
    </form>

    @if($user->mfa_method === 'email')
    <form action="{{ route('provider.2fa.resend') }}" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-link text-white-50 text-decoration-none small p-0 m-0 border-0" style="font-size: 13px;">
            <i class="bi bi-arrow-repeat me-1"></i> Didn't receive code? Resend
        </button>
    </form>
    @endif
    
    <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
        <form action="{{ route('provider.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-danger text-decoration-none small p-0 m-0 border-0" style="font-size: 13px;">
                <i class="bi bi-box-arrow-left me-1"></i> Cancel &amp; Logout
            </button>
        </form>
    </div>
</div>

</body>
</html>
