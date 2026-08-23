<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    @php($siteSettings = \App\Models\SiteSetting::current())
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSMS — Next-Gen School Management & Finance System | {{ $siteSettings->site_name }}</title>
    
    <script>
        (function() {
            var theme = localStorage.getItem('theme');
            if (!theme) {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

    <link rel="icon" href="{{ $siteSettings->site_favicon ? asset('storage/' . $siteSettings->site_favicon) : asset('backend/images/favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --brand-primary: {{ $siteSettings->primary_color ?? '#005f1a' }};
            --brand-dark: {{ $siteSettings->primary_dark ?? '#0f1923' }};
            --brand-accent: {{ $siteSettings->accent_color ?? '#92cb6c' }};
            --brand-gradient: linear-gradient(135deg, #005f1a 0%, #008f28 50%, #92cb6c 100%);
            --font-display: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: #f8fafc;
            color: #1e293b;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        [data-bs-theme="dark"] body {
            background-color: #0b1120;
            color: #f1f5f9;
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: var(--font-display);
        }

        /* Gradient Accents & Backgrounds */
        .hero-mesh {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            height: 650px;
            background: radial-gradient(circle at 50% 20%, rgba(0, 95, 26, 0.12) 0%, rgba(146, 203, 108, 0.05) 50%, transparent 80%);
            pointer-events: none;
            z-index: 0;
        }

        [data-bs-theme="dark"] .hero-mesh {
            background: radial-gradient(circle at 50% 20%, rgba(0, 95, 26, 0.25) 0%, rgba(146, 203, 108, 0.08) 50%, transparent 80%);
        }

        /* Glassmorphism Header */
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .glass-nav {
            background: rgba(11, 17, 32, 0.85);
            border-bottom: 1px solid rgba(30, 41, 59, 0.8);
        }

        /* Portal Cards */
        .portal-card {
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            background: #ffffff;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-decoration: none !important;
            color: inherit;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        [data-bs-theme="dark"] .portal-card {
            background: #131d33;
            border-color: rgba(51, 65, 85, 0.7);
        }

        .portal-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(0, 95, 26, 0.18);
            border-color: var(--brand-accent);
        }

        [data-bs-theme="dark"] .portal-card:hover {
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            border-color: var(--brand-accent);
        }

        .portal-icon-box {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            transition: transform 0.3s ease;
        }

        .portal-card:hover .portal-icon-box {
            transform: scale(1.1) rotate(4deg);
        }

        /* Pill Badges */
        .badge-pill-soft {
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Feature Cards */
        .feature-card {
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.7);
            background: #ffffff;
            padding: 24px;
            height: 100%;
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .feature-card {
            background: #131d33;
            border-color: rgba(51, 65, 85, 0.6);
        }

        .feature-card:hover {
            border-color: rgba(0, 95, 26, 0.4);
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -10px rgba(0, 0, 0, 0.08);
        }

        /* Stat Counter Card */
        .stat-counter-card {
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            padding: 24px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        [data-bs-theme="dark"] .stat-counter-card {
            background: #131d33;
            border-color: rgba(51, 65, 85, 0.7);
        }

        .stat-counter-card:hover {
            transform: translateY(-4px);
        }

        .stat-number {
            font-size: 2.25rem;
            font-weight: 800;
            background: var(--brand-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: var(--font-display);
        }

        /* Tabbed Quick Login in Modal */
        .nav-pills-custom .nav-link {
            border-radius: 10px;
            padding: 10px 18px;
            color: #64748b;
            font-weight: 600;
            transition: all 0.2s;
        }
        .nav-pills-custom .nav-link.active {
            background-color: var(--brand-primary);
            color: #ffffff;
        }

        /* Custom Buttons */
        .btn-brand-primary {
            background: var(--brand-primary);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        .btn-brand-primary:hover {
            background: var(--brand-dark);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -6px rgba(0, 95, 26, 0.4);
        }

        .btn-brand-outline {
            border: 1.5px solid rgba(0, 95, 26, 0.4);
            color: var(--brand-primary);
            background: transparent;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        [data-bs-theme="dark"] .btn-brand-outline {
            border-color: var(--brand-accent);
            color: var(--brand-accent);
        }
        .btn-brand-outline:hover {
            background: var(--brand-primary);
            border-color: var(--brand-primary);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Glowing Pulse Dot */
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse 1.8s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }
    </style>
</head>
<body class="position-relative">

    <!-- Glowing Background Mesh -->
    <div class="hero-mesh"></div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top glass-nav py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none" href="{{ route('secure.login') }}">
                @if($siteSettings->site_logo && file_exists(public_path('storage/' . $siteSettings->site_logo)))
                    <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="{{ $siteSettings->site_name }}" height="42" class="rounded">
                @else
                    <div class="rounded-3 bg-success bg-opacity-10 p-2 text-success d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="bi bi-mortarboard-fill fs-4"></i>
                    </div>
                @endif
                <div>
                    <div class="fw-bold fs-5 lh-1 text-dark text-theme-light">{{ $siteSettings->site_name }}</div>
                    <small class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">NSMS &amp; FINANCE SUITE</small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false">
                <i class="bi bi-list fs-3"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item"><a class="nav-link fw-medium" href="#portals">Portals</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#modules">System Modules</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#accounting">Financial Core</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#stats">Live Insights</a></li>
                </ul>

                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    {{-- Theme Switcher --}}
                    <button type="button" class="btn btn-sm btn-light rounded-circle shadow-sm p-2 theme-toggle-btn" title="Toggle Dark/Light Mode">
                        <i class="bi bi-moon fs-5 theme-toggle-icon"></i>
                    </button>

                    {{-- Back to School Site --}}
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-globe2 me-1"></i> School Website
                    </a>

                    {{-- Quick Login Trigger --}}
                    <button class="btn btn-brand-primary btn-sm rounded-pill px-4 py-2" data-bs-toggle="modal" data-bs-target="#quickLoginModal">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Instant Login
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-5 py-lg-6 position-relative">
        <div class="container text-center py-4">
            
            {{-- Enterprise Badge --}}
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-success bg-opacity-10 border border-success border-opacity-25 text-success mb-4">
                <span class="pulse-dot"></span>
                <span class="small fw-bold">NSMS Enterprise Platform v2.5 Active</span>
                <i class="bi bi-shield-check"></i>
            </div>

            {{-- Main Title --}}
            <h1 class="display-4 fw-extrabold mb-3 mx-auto" style="max-width: 860px; letter-spacing: -0.5px;">
                Unified School Intelligence &amp; Dedicated Financial Governance
            </h1>
            
            <p class="lead text-muted mx-auto mb-5" style="max-width: 720px; font-size: 1.15rem;">
                A single digital ecosystem integrating student lifecycle, class enrollments, examination grading, Nepali Bikram Sambat fee invoicing, and double-entry accounting.
            </p>

            {{-- Flash messages if any --}}
            @if(session('error'))
                <div class="alert alert-danger max-w-xl mx-auto mb-4">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success max-w-xl mx-auto mb-4">{{ session('success') }}</div>
            @endif

            {{-- 4 Primary Portal Cards --}}
            <div class="row g-4 text-start justify-content-center" id="portals">
                
                {{-- 1. Super Admin & Staff Portal --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('admin.login') }}" class="portal-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="portal-icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small">Admin</span>
                        </div>
                        <h4 class="fw-bold mb-2">Admin &amp; Staff</h4>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Complete campus governance, student admissions, attendance, exams, grading &amp; staff directory.
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10">
                            <span class="fw-semibold text-primary small">Access SMS</span>
                            <i class="bi bi-arrow-right text-primary"></i>
                        </div>
                    </a>
                </div>

                {{-- 2. Finance & Accounting Portal --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('accounting.login') }}" class="portal-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="portal-icon-box bg-success bg-opacity-10 text-success">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small">Finance</span>
                        </div>
                        <h4 class="fw-bold mb-2">Accounting Portal</h4>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Fee collection, Bikram Sambat month invoicing, expense tracking, bank accounts &amp; P&amp;L reports.
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10">
                            <span class="fw-semibold text-success small">Access Finance</span>
                            <i class="bi bi-arrow-right text-success"></i>
                        </div>
                    </a>
                </div>

                {{-- 3. Parent & Guardian Portal --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('admin.login') }}" class="portal-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="portal-icon-box bg-info bg-opacity-10 text-info">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1 small">Family</span>
                        </div>
                        <h4 class="fw-bold mb-2">Parent Portal</h4>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Monitor student attendance records, fee payment history, outstanding balances &amp; academic progress.
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10">
                            <span class="fw-semibold text-info small">Parent Login</span>
                            <i class="bi bi-arrow-right text-info"></i>
                        </div>
                    </a>
                </div>

                {{-- 4. Student & Academic Portal --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('admin.login') }}" class="portal-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="portal-icon-box bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1 small">Student</span>
                        </div>
                        <h4 class="fw-bold mb-2">Student Portal</h4>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Access homework, class routines, course study material, download report cards &amp; announcements.
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10">
                            <span class="fw-semibold text-warning small">Student Login</span>
                            <i class="bi bi-arrow-right text-warning"></i>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Live Metrics Counter Section -->
    <section class="py-5 bg-white border-top border-bottom" id="stats">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-lg-3">
                    <div class="stat-counter-card">
                        <div class="stat-number">{{ number_format($totalStudents ?? 189) }}+</div>
                        <div class="text-muted fw-semibold small text-uppercase mt-1">Enrolled Students</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-counter-card">
                        <div class="stat-number">{{ number_format($totalStaff ?? 52) }}+</div>
                        <div class="text-muted fw-semibold small text-uppercase mt-1">Faculty &amp; Staff</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-counter-card">
                        <div class="stat-number">{{ number_format($totalClasses ?? 14) }}</div>
                        <div class="text-muted fw-semibold small text-uppercase mt-1">Academic Programs</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-counter-card">
                        <div class="stat-number">99.9%</div>
                        <div class="text-muted fw-semibold small text-uppercase mt-1">System Reliability</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core System Modules -->
    <section class="py-5 py-lg-6" id="modules">
        <div class="container">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold text-uppercase small mb-2">Comprehensive Capabilities</span>
                <h2 class="display-6 fw-bold">Engineered for Academic &amp; Operational Excellence</h2>
                <p class="text-muted">Explore the specialized sub-systems powering daily academic and financial operations.</p>
            </div>

            <div class="row g-4">
                {{-- Module 1 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary me-3">
                                <i class="bi bi-person-badge fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Student Lifecycle</h5>
                                <small class="text-muted">Admissions &amp; Registrations</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Digital enrollment forms, automated roll number generator, class-section distribution, document archives, and student ID cards.
                        </p>
                    </div>
                </div>

                {{-- Module 2 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success me-3">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Nepali Fee Billing</h5>
                                <small class="text-muted">Bikram Sambat Support</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Class-wise dynamic fee structures, 12 Nepali months bulk invoice generation, partial collections, and printable A4/A5 receipts.
                        </p>
                    </div>
                </div>

                {{-- Module 3 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-info bg-opacity-10 text-info me-3">
                                <i class="bi bi-journal-check fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Examinations &amp; GPA</h5>
                                <small class="text-muted">Grading &amp; Marksheets</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Custom grading scales, terminal marks entry, tabulations, automated GPA conversion, and printable performance report cards.
                        </p>
                    </div>
                </div>

                {{-- Module 4 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning me-3">
                                <i class="bi bi-calendar2-check fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Attendance Tracking</h5>
                                <small class="text-muted">Daily Log &amp; Analysis</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Instant class-wise student &amp; staff attendance logs, absent tracking, rate analytics, and printable daily attendance registers.
                        </p>
                    </div>
                </div>

                {{-- Module 5 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-danger bg-opacity-10 text-danger me-3">
                                <i class="bi bi-building fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Hostel &amp; Logistics</h5>
                                <small class="text-muted">Rooms &amp; Allocations</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Hostel room management, capacity tracking, bed allocations, automated hostel fee billing, and occupant registers.
                        </p>
                    </div>
                </div>

                {{-- Module 6 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-secondary bg-opacity-10 text-secondary me-3">
                                <i class="bi bi-qr-code fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Security &amp; QR Verify</h5>
                                <small class="text-muted">Instant Document Check</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Cryptographically generated QR codes on certificates and marksheets with a public instant authenticity verification portal.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dedicated Finance & Accounting Highlight -->
    <section class="py-5 py-lg-6 bg-light" id="accounting">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold text-uppercase small mb-3">Dedicated Finance Portal</span>
                    <h2 class="display-6 fw-bold mb-3">Auditable, Double-Entry Institutional Accounting</h2>
                    <p class="text-muted mb-4">
                        Built specifically for school bursars and accountants, providing full separation of duties, bank reconciliations, vendor expense ledgers, and live financial statements.
                    </p>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check2-circle text-success fs-5 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Trial Balance &amp; Balance Sheets</h6>
                                <p class="text-muted small mb-0">Real-time debit-credit reconciliation with instant balance sheet generation.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check2-circle text-success fs-5 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Expense &amp; Vendor Management</h6>
                                <p class="text-muted small mb-0">Categorized operational expenditure with vendor tracking and receipt attachments.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check2-circle text-success fs-5 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Outstanding Arrears &amp; Defaulter Reports</h6>
                                <p class="text-muted small mb-0">Multi-parameter queries by Class, Month, and Academic Session to monitor uncollected fees.</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('accounting.login') }}" class="btn btn-brand-primary rounded-pill px-4">
                        <i class="bi bi-lock-fill me-1"></i> Open Accounting Portal
                    </a>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-header bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success rounded-circle p-1"></span>
                                <span class="fw-semibold small">GPLC Accounting Ledger</span>
                            </div>
                            <span class="badge bg-secondary font-monospace">Bikram Sambat 2083</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <span class="text-muted small">Income (Monthly)</span>
                                <span class="fw-bold text-success font-monospace">Rs. 428,000.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <span class="text-muted small">Operational Expenses</span>
                                <span class="fw-bold text-danger font-monospace">Rs. 185,450.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <span class="text-muted small">Total Bank Balance</span>
                                <span class="fw-bold text-primary font-monospace">Rs. 1,420,500.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Outstanding Dues</span>
                                <span class="fw-bold text-warning font-monospace">Rs. 199,900.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Login Modal (Tabbed for All Roles) -->
    <div class="modal fade" id="quickLoginModal" tabindex="-1" aria-labelledby="quickLoginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <div>
                        <h5 class="modal-title fw-bold" id="quickLoginModalLabel">Select Your Portal</h5>
                        <p class="text-muted small mb-0">Choose your role to sign into the system</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-grid gap-3">
                        <a href="{{ route('admin.login') }}" class="btn btn-outline-primary d-flex align-items-center p-3 rounded-3 text-start">
                            <div class="rounded-3 p-2 bg-primary bg-opacity-10 text-primary me-3">
                                <i class="bi bi-shield-lock-fill fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">Super Admin &amp; Staff</div>
                                <small class="text-muted">SMS Administrative System</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>

                        <a href="{{ route('accounting.login') }}" class="btn btn-outline-success d-flex align-items-center p-3 rounded-3 text-start">
                            <div class="rounded-3 p-2 bg-success bg-opacity-10 text-success me-3">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">Finance &amp; Accounting</div>
                                <small class="text-muted">accountant@school.com</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>

                        <a href="{{ route('admin.login') }}" class="btn btn-outline-info d-flex align-items-center p-3 rounded-3 text-start">
                            <div class="rounded-3 p-2 bg-info bg-opacity-10 text-info me-3">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">Parent / Student Portal</div>
                                <small class="text-muted">Attendance, dues &amp; marksheets</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                    <small class="text-muted">Need help with credentials? Contact College Administration.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 border-top">
        <div class="container text-center">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    &copy; {{ date('Y') }} <strong>{{ $siteSettings->site_name }}</strong>. All rights reserved.
                </div>
                <div class="d-flex gap-3 small">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">College Website</a>
                    <a href="{{ route('accounting.login') }}" class="text-decoration-none text-muted">Accounting Login</a>
                    <a href="{{ route('admin.login') }}" class="text-decoration-none text-muted">Staff Login</a>
                    <a href="{{ route('privacy.policy') }}" class="text-decoration-none text-muted">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global Theme Toggle Function
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-bs-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('theme', next);
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            document.querySelectorAll('.theme-toggle-icon').forEach(icon => {
                icon.className = isDark ? 'bi bi-sun fs-5 theme-toggle-icon' : 'bi bi-moon fs-5 theme-toggle-icon';
            });
        }

        document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                toggleTheme();
            });
        });

        document.addEventListener('DOMContentLoaded', updateThemeIcon);
    </script>
</body>
</html>
