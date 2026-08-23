<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    @php($siteSettings = \App\Models\SiteSetting::current())
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSMS Cloud — Next-Gen School Management & Finance SaaS Platform</title>
    
    <script>
        (function() {
            var theme = localStorage.getItem('nsms_theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

    <link rel="icon" href="{{ $siteSettings->site_favicon ? asset('storage/' . $siteSettings->site_favicon) : asset('backend/images/favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            
            /* Dark Theme Tokens (Default) */
            --bg-base: #06090e;
            --bg-surface: #0d121c;
            --bg-elevated: #131b29;
            --bg-card: #0f1624;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-strong: rgba(255, 255, 255, 0.16);
            --border-focus: #10b981;
            
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-tertiary: #64748b;
            
            --emerald-500: #10b981;
            --emerald-400: #34d399;
            --emerald-glow: rgba(16, 185, 129, 0.18);
            
            --header-bg: rgba(6, 9, 14, 0.85);
        }

        [data-bs-theme="light"] {
            --bg-base: #f8fafc;
            --bg-surface: #ffffff;
            --bg-elevated: #f1f5f9;
            --bg-card: #ffffff;
            --border-subtle: rgba(0, 0, 0, 0.07);
            --border-strong: rgba(0, 0, 0, 0.14);
            --border-focus: #059669;
            
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #94a3b8;
            
            --emerald-500: #059669;
            --emerald-400: #10b981;
            --emerald-glow: rgba(5, 150, 105, 0.12);
            
            --header-bg: rgba(248, 250, 252, 0.88);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-base);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            letter-spacing: -0.01em;
        }

        /* Subtle Geometric Grid Background */
        .bg-grid {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 900px;
            background-image: 
                linear-gradient(to right, var(--border-subtle) 1px, transparent 1px),
                linear-gradient(to bottom, var(--border-subtle) 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 70%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 70%, transparent 100%);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow {
            position: absolute;
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            width: 850px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, rgba(59, 130, 246, 0.06) 40%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            filter: blur(50px);
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary);
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        .mono {
            font-family: var(--font-mono);
        }

        /* Top Navigation */
        .nav-scrolled {
            background: var(--header-bg);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border-subtle);
            transition: all 0.2s ease;
        }

        .brand-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
            background: rgba(16, 185, 129, 0.15);
            color: var(--emerald-400);
            border: 1px solid rgba(16, 185, 129, 0.25);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .nav-link-custom {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: color 0.15s, background-color 0.15s;
        }

        .nav-link-custom:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.04);
        }

        [data-bs-theme="light"] .nav-link-custom:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        /* Hero Badges */
        .pill-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 4px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-strong);
            color: var(--text-primary);
            font-size: 0.825rem;
            font-weight: 500;
        }

        .pulse-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--emerald-400);
            box-shadow: 0 0 10px var(--emerald-400);
            display: inline-block;
        }

        /* Buttons */
        .btn-saas-primary {
            background: var(--emerald-500);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.925rem;
            padding: 11px 22px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-saas-primary:hover {
            background: var(--emerald-400);
            color: #06090e;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
        }

        .btn-saas-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.925rem;
            padding: 11px 20px;
            border-radius: 10px;
            border: 1px solid var(--border-strong);
            transition: all 0.18s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-saas-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Portal Cards */
        .portal-entry-card {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 24px;
            text-decoration: none;
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .portal-entry-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            border: 1px solid transparent;
            transition: border-color 0.2s;
            pointer-events: none;
        }

        .portal-entry-card:hover {
            transform: translateY(-4px);
            border-color: var(--border-strong);
            box-shadow: 0 16px 36px -12px rgba(0, 0, 0, 0.4);
            color: var(--text-primary);
        }

        .portal-entry-card:hover::after {
            border-color: var(--emerald-400);
        }

        .icon-sq {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 18px;
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
        }

        /* Interactive Terminal Mockup */
        .preview-window {
            background: var(--bg-surface);
            border: 1px solid var(--border-strong);
            border-radius: 16px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6);
            overflow: hidden;
        }

        .preview-header {
            padding: 12px 18px;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .window-dots {
            display: flex;
            gap: 6px;
        }

        .window-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--border-strong);
        }

        /* Feature Grid */
        .feature-box {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 24px;
            height: 100%;
            transition: border-color 0.2s, transform 0.2s;
        }

        .feature-box:hover {
            border-color: var(--border-strong);
            transform: translateY(-2px);
        }

        .feature-icon-mini {
            font-size: 1.35rem;
            color: var(--emerald-400);
            margin-bottom: 14px;
            display: inline-block;
        }

        /* Step Timeline */
        .timeline-step {
            border-left: 2px solid var(--border-subtle);
            padding-left: 24px;
            position: relative;
            padding-bottom: 32px;
        }

        .timeline-step:last-child {
            padding-bottom: 0;
            border-left-color: transparent;
        }

        .timeline-node {
            position: absolute;
            left: -11px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--bg-base);
            border: 2px solid var(--emerald-400);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-node::after {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--emerald-400);
        }

        /* Form Controls */
        .saas-input {
            background: var(--bg-surface) !important;
            border: 1px solid var(--border-strong) !important;
            color: var(--text-primary) !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 0.9rem !important;
        }

        .saas-input:focus {
            border-color: var(--emerald-400) !important;
            box-shadow: 0 0 0 3px var(--emerald-glow) !important;
        }

        .saas-input::placeholder {
            color: var(--text-tertiary);
        }

        /* Modal Customization */
        .modal-content-custom {
            background: var(--bg-surface);
            border: 1px solid var(--border-strong);
            border-radius: 18px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
        }

        .login-role-btn {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            padding: 16px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.15s;
        }

        .login-role-btn:hover {
            border-color: var(--emerald-400);
            background: rgba(16, 185, 129, 0.04);
            color: var(--text-primary);
            transform: translateX(3px);
        }
    </style>
</head>
<body class="position-relative">

    <!-- Subtle Grid & Top Ambient Glow -->
    <div class="bg-grid"></div>
    <div class="ambient-glow"></div>

    <!-- Header / Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top nav-scrolled py-3">
        <div class="container position-relative z-2">
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('secure.login') }}">
                <div class="rounded-2 bg-success bg-opacity-10 p-1 text-success d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; border: 1px solid rgba(16, 185, 129, 0.25);">
                    <i class="bi bi-clouds-fill fs-5" style="color: var(--emerald-400);"></i>
                </div>
                <span class="fw-bold fs-5 tracking-tight">NSMS<span style="color:var(--emerald-400)">.cloud</span></span>
                <span class="brand-badge ms-1">SaaS Platform</span>
            </a>

            <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false">
                <i class="bi bi-list fs-3"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1">
                    <li class="nav-item"><a class="nav-link-custom" href="#portals">Workspaces</a></li>
                    <li class="nav-item"><a class="nav-link-custom" href="#mou-lifecycle">MoU Partnership</a></li>
                    <li class="nav-item"><a class="nav-link-custom" href="#modules">Architecture</a></li>
                    <li class="nav-item"><a class="nav-link-custom" href="#accounting">Financial Core</a></li>
                    <li class="nav-item"><a class="nav-link-custom" href="#onboard">Onboard School</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    {{-- Theme Switcher --}}
                    <button type="button" class="btn btn-sm btn-link text-secondary text-decoration-none p-2" id="themeToggleBtn" title="Toggle Theme">
                        <i class="bi bi-sun-fill fs-6" id="themeToggleIcon"></i>
                    </button>

                    {{-- Partner School Web Link --}}
                    <a href="{{ route('home') }}" class="btn-saas-secondary py-2 px-3 small" style="font-size:0.85rem">
                        <i class="bi bi-buildings"></i> Demo School
                    </a>

                    {{-- School Login Modal Trigger --}}
                    <button class="btn-saas-primary py-2 px-3 small" data-bs-toggle="modal" data-bs-target="#schoolLoginModal" style="font-size:0.85rem">
                        <i class="bi bi-box-arrow-in-right"></i> School Login
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Hero -->
    <section class="pt-5 pb-4 position-relative z-1">
        <div class="container">
            <div class="row align-items-center pt-3 pb-5">
                <div class="col-lg-7 text-start">
                    
                    {{-- Top Status Pill --}}
                    <div class="mb-3">
                        <span class="pill-tag">
                            <span class="pulse-indicator"></span>
                            <span>Multi-Tenant School ERP &amp; Finance Engine</span>
                            <span class="text-secondary">|</span>
                            <span class="mono small" style="color:var(--emerald-400)">v2.5 Release</span>
                        </span>
                    </div>

                    <h1 class="display-4 fw-extrabold mb-3 lh-11">
                        Institutional Governance &amp; Double-Entry Accounting
                    </h1>

                    <p class="text-secondary fs-5 mb-4" style="max-width: 600px; font-weight: 400;">
                        NSMS is an enterprise SaaS platform provided to educational institutions via institutional MoUs. We provision dedicated school environments with complete Nepali Bikram Sambat fee invoicing, academic grading, and financial ledgers.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <button class="btn-saas-primary" data-bs-toggle="modal" data-bs-target="#schoolLoginModal">
                            <i class="bi bi-shield-lock-fill"></i> Sign In to School Workspace
                        </button>
                        <a href="#onboard" class="btn-saas-secondary">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Request MoU Partnership
                        </a>
                    </div>

                    {{-- Micro Trust Tags --}}
                    <div class="d-flex flex-wrap align-items-center gap-4 pt-3 text-secondary small">
                        <span class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success"></i> Bikram Sambat 2083 Ready</span>
                        <span class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success"></i> Double-Entry Journal Core</span>
                        <span class="d-flex align-items-center gap-2"><i class="bi bi-check2 text-success"></i> Multi-Guard Auth</span>
                    </div>
                </div>

                {{-- Hero Live Preview Window --}}
                <div class="col-lg-5 mt-4 mt-lg-0">
                    <div class="preview-window">
                        <div class="preview-header">
                            <div class="window-dots">
                                <div class="window-dot"></div>
                                <div class="window-dot"></div>
                                <div class="window-dot"></div>
                            </div>
                            <span class="mono small text-secondary" style="font-size: 0.75rem;">nsms://tenant.demo/finance/overview</span>
                            <span class="badge bg-success bg-opacity-10 text-success mono" style="font-size: 0.7rem;">LIVE SYNC</span>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="text-secondary small">Monthly Revenue (Bhadra 2083)</div>
                                    <div class="fs-4 fw-bold mono text-emerald-400" style="color:var(--emerald-400)">Rs. 428,000.00</div>
                                </div>
                                <span class="badge bg-success bg-opacity-20 text-success p-2"><i class="bi bi-graph-up-arrow"></i> +14.2%</span>
                            </div>

                            <div class="p-3 rounded-3 mb-3" style="background: var(--bg-card); border: 1px solid var(--border-subtle);">
                                <div class="d-flex justify-content-between text-secondary small mb-2">
                                    <span>Fee Collection Efficiency</span>
                                    <span class="mono text-white">88.4%</span>
                                </div>
                                <div class="progress" style="height: 6px; background: rgba(255,255,255,0.06);">
                                    <div class="progress-bar bg-success" style="width: 88.4%"></div>
                                </div>
                            </div>

                            <div class="row g-2 text-start">
                                <div class="col-6">
                                    <div class="p-2 rounded-2" style="background: var(--bg-card); border: 1px solid var(--border-subtle);">
                                        <div class="text-secondary" style="font-size: 0.75rem;">Active Enrolments</div>
                                        <div class="fw-bold mono">{{ number_format($totalStudents ?? 189) }} Students</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 rounded-2" style="background: var(--bg-card); border: 1px solid var(--border-subtle);">
                                        <div class="text-secondary" style="font-size: 0.75rem;">Ledger Balance</div>
                                        <div class="fw-bold mono">Rs. 1.42M</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="pulse-indicator"></span>
                                    <span class="text-secondary small">Cloud Tenant Active</span>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#schoolLoginModal">
                                    Enter Dashboard &rarr;
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Role-Based Workspaces Section -->
    <section class="py-5 position-relative z-1" id="portals">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
                <div>
                    <span class="mono text-emerald-400 small" style="color:var(--emerald-400)">WORKSPACES</span>
                    <h3 class="fw-bold mb-1">Select Your Institutional Portal</h3>
                    <p class="text-secondary mb-0">Sign in to your provisioned school environment based on your operational role.</p>
                </div>
                <div class="text-secondary small mono mt-2 mt-md-0">
                    <i class="bi bi-shield-check text-success"></i> 256-Bit Encrypted Sessions
                </div>
            </div>

            <div class="row g-3">
                {{-- Admin / Staff --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('admin.login') }}" class="portal-entry-card">
                        <div>
                            <div class="icon-sq text-primary">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div class="fw-bold fs-6 mb-1">Administrative Staff</div>
                            <p class="text-secondary small mb-3">
                                Enrolments, academic sessions, teacher rosters, class schedules, and system configurations.
                            </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-10">
                            <span class="small mono fw-semibold text-primary">admin.auth &rarr;</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.7rem;">SMS Core</span>
                        </div>
                    </a>
                </div>

                {{-- Accounting / Bursar --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('accounting.login') }}" class="portal-entry-card">
                        <div>
                            <div class="icon-sq text-success">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <div class="fw-bold fs-6 mb-1">Finance &amp; Bursar</div>
                            <p class="text-secondary small mb-3">
                                Bikram Sambat fee invoicing, payment receipts, expense ledgers, trial balance, and P&amp;L reports.
                            </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-10">
                            <span class="small mono fw-semibold text-success">finance.auth &rarr;</span>
                            <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 0.7rem;">Ledger Guard</span>
                        </div>
                    </a>
                </div>

                {{-- Parent Portal --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('admin.login') }}" class="portal-entry-card">
                        <div>
                            <div class="icon-sq text-info">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="fw-bold fs-6 mb-1">Parents &amp; Guardians</div>
                            <p class="text-secondary small mb-3">
                                Attendance tracking, digital fee receipts, outstanding balance alerts, and student progress reports.
                            </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-10">
                            <span class="small mono fw-semibold text-info">parent.auth &rarr;</span>
                            <span class="badge bg-info bg-opacity-10 text-info" style="font-size: 0.7rem;">Family App</span>
                        </div>
                    </a>
                </div>

                {{-- Student Portal --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('admin.login') }}" class="portal-entry-card">
                        <div>
                            <div class="icon-sq text-warning">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div class="fw-bold fs-6 mb-1">Student Workspace</div>
                            <p class="text-secondary small mb-3">
                                Download homework, review course materials, check examination schedules, and view grade cards.
                            </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-10">
                            <span class="small mono fw-semibold text-warning">student.auth &rarr;</span>
                            <span class="badge bg-warning bg-opacity-10 text-warning" style="font-size: 0.7rem;">Student App</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- The MoU Lifecycle: How Institutions Partner with Us -->
    <section class="py-5 bg-surface border-top border-bottom border-secondary border-opacity-10" id="mou-lifecycle">
        <div class="container py-3">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <span class="mono text-emerald-400 small" style="color:var(--emerald-400)">SaaS PARTNERSHIP MODEL</span>
                    <h2 class="display-6 fw-bold mb-3">How Schools Partner &amp; Deploy NSMS</h2>
                    <p class="text-secondary mb-4">
                        We don't just sell software; we enter into institutional partnerships (MoUs) to provide end-to-end digitisation, continuous cloud hosting, and compliance updates.
                    </p>

                    <div class="p-3 rounded-3 mb-4" style="background: var(--bg-card); border: 1px solid var(--border-subtle);">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-shield-check text-success fs-3"></i>
                            <div>
                                <div class="fw-bold small">Enterprise SLA &amp; Support</div>
                                <div class="text-secondary small">24/7 dedicated support line with 99.9% uptime guarantee.</div>
                            </div>
                        </div>
                    </div>

                    <a href="#onboard" class="btn-saas-primary">
                        <i class="bi bi-pen-fill"></i> Partner With Us
                    </a>
                </div>

                <div class="col-lg-7">
                    <div class="ps-lg-4">
                        <div class="timeline-step">
                            <div class="timeline-node"></div>
                            <h5 class="fw-bold mb-1">01. Institutional MoU &amp; Scope Alignment</h5>
                            <p class="text-secondary small mb-0">
                                We establish an MoU defining your academic grading schemes, class tiers, and fee structures (Bikram Sambat 12-month calendar).
                            </p>
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-node"></div>
                            <h5 class="fw-bold mb-1">02. Tenant Provisioning &amp; Data Migration</h5>
                            <p class="text-secondary small mb-0">
                                Our data engineers import your existing student records, faculty rosters, and opening chart of accounts into an isolated cloud database.
                            </p>
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-node"></div>
                            <h5 class="fw-bold mb-1">03. Staff Onboarding &amp; Role Handover</h5>
                            <p class="text-secondary small mb-0">
                                Principal, bursar, and administrative credentials are created. Hands-on training is provided for invoice generation and day books.
                            </p>
                        </div>

                        <div class="timeline-step">
                            <div class="timeline-node"></div>
                            <h5 class="fw-bold mb-1">04. Live Operations &amp; Managed Cloud</h5>
                            <p class="text-secondary small mb-0">
                                Daily attendance, billing, exam marksheets, and ledger reconciliations run on zero-maintenance cloud infrastructure.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Architecture & Modules Grid -->
    <section class="py-5 position-relative z-1" id="modules">
        <div class="container py-3">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="mono text-emerald-400 small" style="color:var(--emerald-400)">SYSTEM ARCHITECTURE</span>
                <h2 class="display-6 fw-bold mb-2">Designed for Total Operational Command</h2>
                <p class="text-secondary">Every component is engineered for data integrity, speed, and audit compliance.</p>
            </div>

            <div class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-box">
                        <i class="bi bi-mortarboard feature-icon-mini"></i>
                        <h6 class="fw-bold mb-2">Student Lifecycle &amp; Enrolment</h6>
                        <p class="text-secondary small mb-0">
                            Digital admission forms, roll allocation algorithms, class promotion workflows, and automated ID badge printing.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-box">
                        <i class="bi bi-calendar2-range feature-icon-mini"></i>
                        <h6 class="fw-bold mb-2">Bikram Sambat Billing Engine</h6>
                        <p class="text-secondary small mb-0">
                            Class-wise dynamic fee structures with 12 Nepali months batch invoicing, arrears carryover, and A4/A5 receipt printing.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-box">
                        <i class="bi bi-journal-bookmark feature-icon-mini"></i>
                        <h6 class="fw-bold mb-2">Double-Entry Financial Suite</h6>
                        <p class="text-secondary small mb-0">
                            Real-time day books, general ledgers, trial balance calculation, automated profit &amp; loss, and bank reconciliations.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-box">
                        <i class="bi bi-clipboard2-data feature-icon-mini"></i>
                        <h6 class="fw-bold mb-2">Examination &amp; GPA Engine</h6>
                        <p class="text-secondary small mb-0">
                            Custom grading criteria, terminal marks entry, tabulations, automated GPA conversion, and printable marksheets.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-box">
                        <i class="bi bi-fingerprint feature-icon-mini"></i>
                        <h6 class="fw-bold mb-2">Attendance &amp; Log Analytics</h6>
                        <p class="text-secondary small mb-0">
                            Daily student &amp; staff attendance logs, absent SMS alerts, monthly percentage trends, and printable registers.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-box">
                        <i class="bi bi-qr-code-scan feature-icon-mini"></i>
                        <h6 class="fw-bold mb-2">QR Document Verification</h6>
                        <p class="text-secondary small mb-0">
                            Tamper-proof cryptographic QR tokens on student certificates and report cards with public instant authenticity check.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Onboard School MoU Consultation Form -->
    <section class="py-5 bg-surface border-top border-secondary border-opacity-10" id="onboard">
        <div class="container py-3">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <span class="mono text-emerald-400 small" style="color:var(--emerald-400)">INSTITUTIONAL ONBOARDING</span>
                    <h2 class="display-6 fw-bold mb-3">Deploy NSMS In Your Institution</h2>
                    <p class="text-secondary mb-4">
                        Schedule a live presentation with our educational technology specialists. We provide transparent MoU agreements and zero-downtime migration.
                    </p>

                    <div class="d-flex flex-column gap-3 text-secondary small">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle text-success"></i> Custom Nepali Calendar (BS) and Grading Scales configured
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle text-success"></i> On-site &amp; remote hands-on workshops for staff &amp; accountants
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle text-success"></i> Dedicated server provisioning with encrypted cloud backups
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 p-md-5 rounded-4" style="background: var(--bg-card); border: 1px solid var(--border-strong);">
                        <h5 class="fw-bold mb-1">Request MoU Consultation</h5>
                        <p class="text-secondary small mb-4">Fill out your school details to schedule a live demo.</p>

                        <form action="{{ route('contact') }}" method="GET">
                            <div class="mb-3">
                                <label class="form-label small text-secondary">Institution / College Name</label>
                                <input type="text" class="form-control saas-input" placeholder="e.g. Model Academy" required>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-secondary">Contact Person</label>
                                    <input type="text" class="form-control saas-input" placeholder="Principal / Admin" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-secondary">Phone Number</label>
                                    <input type="tel" class="form-control saas-input" placeholder="+977 98XXXXXXXX" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-secondary">Official Email</label>
                                <input type="email" class="form-control saas-input" placeholder="principal@school.edu.np" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small text-secondary">Student Body Capacity</label>
                                <select class="form-select saas-input">
                                    <option value="100-500">100 – 500 Students</option>
                                    <option value="500-1500" selected>500 – 1,500 Students</option>
                                    <option value="1500+">1,500+ Students (Campus / Multi-Branch)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-saas-primary w-100 py-3">
                                <i class="bi bi-send-fill"></i> Submit Partnership Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- School Login Modal -->
    <div class="modal fade" id="schoolLoginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <div>
                        <h5 class="modal-title fw-bold">Sign In to Your School Portal</h5>
                        <p class="text-secondary small mb-0">Select your institutional role to continue</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('admin.login') }}" class="login-role-btn">
                            <div class="icon-sq text-primary mb-0">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">Administrator &amp; Staff</div>
                                <div class="text-secondary" style="font-size: 0.78rem;">Student management, exams &amp; routines</div>
                            </div>
                            <i class="bi bi-chevron-right text-secondary"></i>
                        </a>

                        <a href="{{ route('accounting.login') }}" class="login-role-btn">
                            <div class="icon-sq text-success mb-0">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">Bursar &amp; Finance Accountant</div>
                                <div class="text-secondary" style="font-size: 0.78rem;">Fee invoicing, ledgers &amp; balance sheet</div>
                            </div>
                            <i class="bi bi-chevron-right text-secondary"></i>
                        </a>

                        <a href="{{ route('admin.login') }}" class="login-role-btn">
                            <div class="icon-sq text-info mb-0">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">Parent &amp; Student Portal</div>
                                <div class="text-secondary" style="font-size: 0.78rem;">Attendance, due fees &amp; digital grade cards</div>
                            </div>
                            <i class="bi bi-chevron-right text-secondary"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                    <small class="text-secondary">New school? <a href="#onboard" data-bs-dismiss="modal" class="text-emerald-400 text-decoration-none fw-semibold" style="color:var(--emerald-400)">Request an MoU Partnership</a></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 border-top border-secondary border-opacity-10 bg-surface">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="text-secondary small">
                    &copy; {{ date('Y') }} <strong>NSMS SaaS Platform</strong>. Institutional Education Technologies.
                </div>
                <div class="d-flex gap-3 small">
                    <a href="{{ route('home') }}" class="text-decoration-none text-secondary">Demo School</a>
                    <a href="{{ route('accounting.login') }}" class="text-decoration-none text-secondary">Finance Login</a>
                    <a href="{{ route('admin.login') }}" class="text-decoration-none text-secondary">Staff Login</a>
                    <a href="#onboard" class="text-decoration-none text-secondary">MoU Inquiries</a>
                    <a href="{{ route('privacy.policy') }}" class="text-decoration-none text-secondary">Privacy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS & Theme Toggle Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('themeToggleBtn');
        const toggleIcon = document.getElementById('themeToggleIcon');

        function updateThemeDisplay(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('nsms_theme', theme);
            if (theme === 'dark') {
                toggleIcon.className = 'bi bi-sun-fill fs-6 text-warning';
            } else {
                toggleIcon.className = 'bi bi-moon-stars-fill fs-6 text-secondary';
            }
        }

        const currentTheme = localStorage.getItem('nsms_theme') || 'dark';
        updateThemeDisplay(currentTheme);

        toggleBtn?.addEventListener('click', () => {
            const active = document.documentElement.getAttribute('data-bs-theme');
            const next = active === 'dark' ? 'light' : 'dark';
            updateThemeDisplay(next);
        });
    </script>
</body>
</html>