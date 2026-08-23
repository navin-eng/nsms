<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God Mode Authentication — NSMS Provider</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --bg-base: #05080e;
            --bg-card: #0c121e;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-strong: rgba(255, 255, 255, 0.16);
            --emerald-500: #10b981;
            --emerald-400: #34d399;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-base);
            color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        .ambient-mesh {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            height: 600px;
            background: radial-gradient(circle at 50% 20%, rgba(16, 185, 129, 0.12) 0%, rgba(59, 130, 246, 0.05) 50%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border-strong);
            border-radius: 20px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 440px;
            padding: 36px 32px;
            position: relative;
            z-index: 1;
        }

        .god-mode-badge {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 6px;
            background: rgba(16, 185, 129, 0.15);
            color: var(--emerald-400);
            border: 1px solid rgba(16, 185, 129, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid var(--border-strong) !important;
            color: #ffffff !important;
            border-radius: 10px !important;
            padding: 12px 14px !important;
            font-size: 0.95rem !important;
        }

        .form-control-custom:focus {
            border-color: var(--emerald-400) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
        }

        .btn-god-mode {
            background: var(--emerald-500);
            color: #05080e;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            transition: all 0.2s;
            width: 100%;
            font-size: 0.95rem;
        }

        .btn-god-mode:hover {
            background: var(--emerald-400);
            box-shadow: 0 8px 24px -4px rgba(16, 185, 129, 0.4);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="ambient-mesh"></div>

    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex mb-3">
                <span class="god-mode-badge">
                    <i class="bi bi-cpu-fill"></i> Central SaaS Provider
                </span>
            </div>
            <h3 class="fw-bold mb-1">God Mode Console</h3>
            <p class="text-secondary small mb-0">Platform control &amp; multi-tenant administration</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger py-2 small mb-3">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success py-2 small mb-3">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('provider.login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small text-secondary fw-semibold">Provider Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary border-opacity-25 text-secondary">
                        <i class="bi bi-envelope-fill"></i>
                    </span>
                    <input type="email" name="email" class="form-control form-control-custom border-start-0 ps-0" placeholder="admin@nsms.cloud" value="{{ old('email', 'godmode@nsms.cloud') }}" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small text-secondary fw-semibold">Master Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary border-opacity-25 text-secondary">
                        <i class="bi bi-key-fill"></i>
                    </span>
                    <input type="password" name="password" class="form-control form-control-custom border-start-0 ps-0" placeholder="••••••••" value="admin123" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input bg-transparent border-secondary border-opacity-50" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label small text-secondary" for="remember">
                        Remember Session
                    </label>
                </div>
                <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.7rem;">IP LOGGED</span>
            </div>

            <button type="submit" class="btn btn-god-mode">
                <i class="bi bi-box-arrow-in-right me-2"></i> Authenticate Into God Mode
            </button>
        </form>

        <div class="mt-4 pt-3 border-top border-secondary border-opacity-10 text-center">
            <a href="{{ route('secure.login') }}" class="text-secondary small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Back to Public Landing
            </a>
        </div>
    </div>
</body>
</html>
