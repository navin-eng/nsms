<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'God Mode Console') — NSMS SaaS Provider</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --bg-base: #06090e;
            --bg-surface: #0c121e;
            --bg-card: #101726;
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
            flex-direction: column;
            letter-spacing: -0.01em;
        }

        .mono {
            font-family: var(--font-mono);
        }

        /* Top Header */
        .provider-navbar {
            background: rgba(12, 18, 30, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-subtle);
        }

        .god-badge {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            background: rgba(16, 185, 129, 0.15);
            color: var(--emerald-400);
            border: 1px solid rgba(16, 185, 129, 0.3);
            text-transform: uppercase;
        }

        .provider-nav-link {
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s;
        }

        .provider-nav-link:hover, .provider-nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        /* Cards */
        .card-god {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
        }

        .stat-god-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 20px;
            transition: transform 0.2s, border-color 0.2s;
        }

        .stat-god-card:hover {
            border-color: var(--border-strong);
            transform: translateY(-2px);
        }

        .btn-emerald {
            background: var(--emerald-500);
            color: #06090e;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            padding: 8px 16px;
            transition: all 0.15s;
        }

        .btn-emerald:hover {
            background: var(--emerald-400);
            transform: translateY(-1px);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Provider Topbar -->
    <nav class="navbar navbar-expand-lg sticky-top provider-navbar py-2 px-3">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('provider.dashboard') }}">
                <div class="rounded-2 bg-success bg-opacity-15 p-1 text-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: 1px solid rgba(16, 185, 129, 0.3);">
                    <i class="bi bi-cpu-fill text-success" style="color: var(--emerald-400) !important;"></i>
                </div>
                <span class="fw-bold fs-6 text-white">NSMS Provider</span>
                <span class="god-badge ms-1">GOD MODE</span>
            </a>

            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#providerNav">
                <i class="bi bi-list fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="providerNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                    <li class="nav-item">
                        <a class="provider-nav-link {{ request()->routeIs('provider.dashboard') ? 'active' : '' }}" href="{{ route('provider.dashboard') }}">
                            <i class="bi bi-grid-1x2-fill me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="provider-nav-link {{ request()->routeIs('provider.schools.*') ? 'active' : '' }}" href="{{ route('provider.schools.index') }}">
                            <i class="bi bi-buildings-fill me-1"></i> Partner Schools
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-semibold small text-white">{{ auth('provider')->user()->name }}</div>
                        <small class="mono text-secondary" style="font-size: 0.72rem;">{{ auth('provider')->user()->role }}</small>
                    </div>

                    <form action="{{ route('provider.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary py-1 px-2 rounded-2" title="Sign Out">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content Area -->
    <main class="flex-grow-1 py-4">
        <div class="container-fluid px-lg-4">
            @if(session('success'))
                <div class="alert alert-success py-2 px-3 small mb-4 rounded-3 d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger py-2 px-3 small mb-4 rounded-3 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-3 border-top border-secondary border-opacity-10 text-center text-secondary small">
        <div class="container-fluid">
            &copy; {{ date('Y') }} <strong>NSMS SaaS Provider System</strong>. God Mode Control Center.
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
