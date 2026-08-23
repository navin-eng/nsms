<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ \App\Models\SiteSetting::current()->site_name ?? 'Portal' }} - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ \App\Models\SiteSetting::current()->site_favicon ? asset('storage/' . \App\Models\SiteSetting::current()->site_favicon) : asset('backend/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <style>
        body {
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2rem;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .brand-logo img {
            height: 60px;
            object-fit: contain;
        }
        .title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 2rem;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
        }
        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        .btn-primary {
            background-color: #10b981;
            border-color: #10b981;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
        }
        .btn-primary:hover {
            background-color: #059669;
            border-color: #059669;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
        }
        .back-link a {
            color: #6b7280;
            text-decoration: none;
        }
        .back-link a:hover {
            color: #111827;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="brand-logo">
            <img src="{{ \App\Models\SiteSetting::current()->site_logo ? asset('storage/' . \App\Models\SiteSetting::current()->site_logo) : asset('backend/images/logo.png') }}" alt="Logo">
        </div>
        <h2 class="title">Admin Login</h2>
        <p class="subtitle">Enter your credentials to access the CMS & SMS</p>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 px-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.check') }}" method="POST">
            @csrf
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="school_code" class="form-label text-muted mb-0">School Code (Optional)</label>
                    <span class="badge bg-light text-secondary font-monospace" style="font-size:0.7rem;">e.g. SCH-000101</span>
                </div>
                <input class="form-control font-monospace" name="school_code" type="text" id="school_code" placeholder="SCH-XXXXXX" value="{{ old('school_code', request('school_code')) }}">
            </div>

            <div class="mb-3">
                <label for="emailaddress" class="form-label text-muted">Username / Email Address</label>
                <input class="form-control" name="email" type="text" id="emailaddress" required placeholder="name@example.com or admin" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label text-muted mb-0">Password</label>
                    <a href="{{ route('forgot.password') }}" class="text-muted" style="font-size: 0.8rem;">Forgot password?</a>
                </div>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button class="btn btn-primary" type="submit">Sign In</button>
        </form>

        <div class="back-link">
            <a href="{{ route('secure.login') }}"><i class="bi bi-arrow-left"></i> Back to Portal Selection</a>
        </div>
    </div>

</body>
</html>
