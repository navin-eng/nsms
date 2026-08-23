<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    @php($siteSettings = \App\Models\SiteSetting::current())
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSMS Cloud — Next-Gen School Management & Finance SaaS Platform</title>
    
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
            --brand-primary: #005f1a;
            --brand-dark: #093811;
            --brand-accent: #10b981;
            --brand-gradient: linear-gradient(135deg, #005f1a 0%, #059669 50%, #10b981 100%);
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
            background-color: #090e17;
            color: #f1f5f9;
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: var(--font-display);
        }

        /* Ambient Glow & Mesh */
        .hero-mesh {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            height: 750px;
            background: radial-gradient(circle at 50% 15%, rgba(16, 185, 129, 0.15) 0%, rgba(0, 95, 26, 0.08) 45%, transparent 75%);
            pointer-events: none;
            z-index: 0;
        }

        [data-bs-theme="dark"] .hero-mesh {
            background: radial-gradient(circle at 50% 15%, rgba(16, 185, 129, 0.22) 0%, rgba(0, 95, 26, 0.12) 45%, transparent 75%);
        }

        /* Glassmorphism Header */
        .glass-nav {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .glass-nav {
            background: rgba(9, 14, 23, 0.88);
            border-bottom: 1px solid rgba(30, 41, 59, 0.8);
        }

        /* Card Styles */
        .saas-card {
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

        [data-bs-theme="dark"] .saas-card {
            background: #111a2e;
            border-color: rgba(51, 65, 85, 0.7);
        }

        .saas-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(0, 95, 26, 0.18);
            border-color: var(--brand-accent);
        }

        [data-bs-theme="dark"] .saas-card:hover {
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.6);
            border-color: var(--brand-accent);
        }

        .portal-icon-box {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            transition: transform 0.3s ease;
        }

        .saas-card:hover .portal-icon-box {
            transform: scale(1.1) rotate(4deg);
        }

        /* Step Card */
        .step-card {
            border-radius: 18px;
            border: 1px dashed rgba(16, 185, 129, 0.35);
            background: rgba(16, 185, 129, 0.03);
            padding: 28px 24px;
            position: relative;
            height: 100%;
            transition: all 0.3s ease;
        }
        .step-card:hover {
            border-style: solid;
            border-color: var(--brand-accent);
            background: #ffffff;
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.08);
        }
        [data-bs-theme="dark"] .step-card:hover {
            background: #111a2e;
        }
        .step-number {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--brand-primary);
            color: #ffffff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            margin-bottom: 16px;
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
            background: #111a2e;
            border-color: rgba(51, 65, 85, 0.6);
        }

        .feature-card:hover {
            border-color: rgba(16, 185, 129, 0.5);
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -10px rgba(0, 0, 0, 0.08);
        }

        /* Custom Buttons */
        .btn-brand-primary {
            background: var(--brand-primary);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 26px;
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

        /* Pulse Dot */
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse 1.8s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>
</head>
<body class="position-relative">

    <!-- Ambient Mesh Effect -->
    <div class="hero-mesh"></div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top glass-nav py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3 text-decoration-none" href="{{ route('secure.login') }}">
                @if($siteSettings->site_logo && file_exists(public_path('storage/' . $siteSettings->site_logo)))
                    <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="NSMS Logo" height="42" class="rounded">
                @else
                    <div class="rounded-3 bg-success bg-opacity-10 p-2 text-success d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="bi bi-clouds-fill fs-4 text-success"></i>
                    </div>
                @endif
                <div>
                    <div class="fw-bold fs-5 lh-1 text-dark text-theme-light">NSMS Cloud</div>
                    <small class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">ENTERPRISE SCHOOL &amp; FINANCE SAAS</small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false">
                <i class="bi bi-list fs-3"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
                    <li class="nav-item"><a class="nav-link fw-medium" href="#login-portals">School Login</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#how-it-works">MoU Partnership</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#modules">SaaS Modules</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#finance-core">Finance Engine</a></li>
                    <li class="nav-item"><a class="nav-link fw-medium" href="#partner-mou">Onboard School</a></li>
                </ul>

                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    {{-- Theme Switcher --}}
                    <button type="button" class="btn btn-sm btn-light rounded-circle shadow-sm p-2 theme-toggle-btn" title="Toggle Dark/Light Mode">
                        <i class="bi bi-moon fs-5 theme-toggle-icon"></i>
                    </button>

                    {{-- Link to GPLC Demo School Website --}}
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-buildings me-1"></i> Partner School Portal
                    </a>

                    {{-- Instant School Login CTA --}}
                    <button class="btn btn-brand-primary btn-sm rounded-pill px-4 py-2" data-bs-toggle="modal" data-bs-target="#schoolLoginModal">
                        <i class="bi bi-box-arrow-in-right me-1"></i> School Login
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section: SaaS Proposition -->
    <section class="py-5 py-lg-6 position-relative">
        <div class="container text-center py-4">
            
            {{-- SaaS Model Badge --}}
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-success bg-opacity-10 border border-success border-opacity-25 text-success mb-4">
                <span class="pulse-dot"></span>
                <span class="small fw-bold">Cloud SaaS Platform for Schools &amp; Colleges</span>
                <i class="bi bi-patch-check-fill"></i>
            </div>

            {{-- Main Title --}}
            <h1 class="display-4 fw-extrabold mb-3 mx-auto" style="max-width: 920px; letter-spacing: -0.5px;">
                Empower Your Educational Institution With Unified Cloud Intelligence
            </h1>
            
            <p class="lead text-muted mx-auto mb-4" style="max-width: 760px; font-size: 1.15rem;">
                Partner with <strong>NSMS</strong> through an institutional MoU to onboard your school onto our zero-friction cloud operating system — featuring student admissions, attendance, Bikram Sambat fee invoicing, and double-entry institutional accounting.
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
                <button class="btn btn-brand-primary rounded-pill px-4 py-3 fw-bold" data-bs-toggle="modal" data-bs-target="#schoolLoginModal">
                    <i class="bi bi-door-open-fill me-2"></i> Log In To Your School Portal
                </button>
                <a href="#partner-mou" class="btn btn-brand-outline rounded-pill px-4 py-3 fw-bold">
                    <i class="bi bi-file-earmark-text-fill me-2"></i> Partner With Us (MoU Inquiries)
                </a>
            </div>

            {{-- Flash Messages --}}
            @if(session('error'))
                <div class="alert alert-danger max-w-xl mx-auto mb-4">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success max-w-xl mx-auto mb-4">{{ session('success') }}</div>
            @endif

            {{-- Login Gateways for Partner Schools --}}
            <div class="row g-4 text-start justify-content-center mt-2" id="login-portals">
                
                {{-- 1. School Admin & Staff --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('admin.login') }}" class="saas-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="portal-icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small">Admin / Staff</span>
                        </div>
                        <h5 class="fw-bold mb-2">School Administration</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Principal &amp; administrative workspace for admissions, teacher rosters, classes, grading, and routine scheduling.
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10">
                            <span class="fw-semibold text-primary small">Admin Sign In</span>
                            <i class="bi bi-arrow-right text-primary"></i>
                        </div>
                    </a>
                </div>

                {{-- 2. Finance & Accounting Bursar --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('accounting.login') }}" class="saas-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="portal-icon-box bg-success bg-opacity-10 text-success">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small">Bursar &amp; Finance</span>
                        </div>
                        <h5 class="fw-bold mb-2">Finance &amp; Accounting</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Bikram Sambat monthly fee invoicing, receipt generation, vendor expenses, bank accounts, and balance sheets.
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10">
                            <span class="fw-semibold text-success small">Accountant Sign In</span>
                            <i class="bi bi-arrow-right text-success"></i>
                        </div>
                    </a>
                </div>

                {{-- 3. Parent & Guardian Portal --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('admin.login') }}" class="saas-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="portal-icon-box bg-info bg-opacity-10 text-info">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1 small">Parent Gateway</span>
                        </div>
                        <h5 class="fw-bold mb-2">Parents &amp; Guardians</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Real-time attendance logs, fee dues notifications, downloadable fee receipts, and marksheet tracking.
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10">
                            <span class="fw-semibold text-info small">Parent Sign In</span>
                            <i class="bi bi-arrow-right text-info"></i>
                        </div>
                    </a>
                </div>

                {{-- 4. Student Portal --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('admin.login') }}" class="saas-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="portal-icon-box bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1 small">Student Access</span>
                        </div>
                        <h5 class="fw-bold mb-2">Student Workspace</h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            Access homework, class routines, download study materials, and review terminal examination performance.
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10">
                            <span class="fw-semibold text-warning small">Student Sign In</span>
                            <i class="bi bi-arrow-right text-warning"></i>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- SaaS Onboarding & MoU Lifecycle Section -->
    <section class="py-5 bg-white border-top border-bottom" id="how-it-works">
        <div class="container">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold text-uppercase small mb-2">The SaaS Partner Journey</span>
                <h2 class="display-6 fw-bold">How Institutions Partner &amp; Onboard With NSMS</h2>
                <p class="text-muted">A streamlined, 4-step deployment process tailored for educational institutions.</p>
            </div>

            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <h5 class="fw-bold mb-2">MoU &amp; Partnership</h5>
                        <p class="text-muted small mb-0">
                            We sign a Memorandum of Understanding (MoU) and configure your institution's custom branding, grading scheme, and fee structures.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <h5 class="fw-bold mb-2">Cloud Provisioning</h5>
                        <p class="text-muted small mb-0">
                            Our team generates your dedicated institution account and provides automated data migration for student rosters and ledger accounts.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <h5 class="fw-bold mb-2">Role Activation</h5>
                        <p class="text-muted small mb-0">
                            Principals, bursars, teachers, and parents receive secure credentials to log in directly via this central public landing portal.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="step-card">
                        <div class="step-number">04</div>
                        <h5 class="fw-bold mb-2">Continuous Innovation</h5>
                        <p class="text-muted small mb-0">
                            Enjoy zero-maintenance software updates, cloud backups, Bikram Sambat compliance, and 24/7 dedicated support.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comprehensive SaaS Modules Showcase -->
    <section class="py-5 py-lg-6" id="modules">
        <div class="container">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold text-uppercase small mb-2">Feature Suite</span>
                <h2 class="display-6 fw-bold">Built for Total Institutional Governance</h2>
                <p class="text-muted">Everything a school administration needs in a unified cloud ecosystem.</p>
            </div>

            <div class="row g-4">
                {{-- Module 1 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary me-3">
                                <i class="bi bi-mortarboard-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Student Lifecycle &amp; Admissions</h5>
                                <small class="text-muted">Digital Enrolment</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Streamline admission forms, roll allocation, class promotions, section distribution, guardian contacts, and student ID cards.
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
                                <h5 class="fw-bold mb-0">Bikram Sambat Fee Engine</h5>
                                <small class="text-muted">12 Nepali Months Billing</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Dynamic fee structures by class, batch invoicing, partial payments tracking, and instant thermal/A4/A5 receipt printing.
                        </p>
                    </div>
                </div>

                {{-- Module 3 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-info bg-opacity-10 text-info me-3">
                                <i class="bi bi-file-earmark-bar-graph-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Double-Entry Financial Suite</h5>
                                <small class="text-muted">Auditable Accounting</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Day books, general ledgers, trial balance, automated profit &amp; loss / income statements, balance sheets, and bank reconciliations.
                        </p>
                    </div>
                </div>

                {{-- Module 4 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning me-3">
                                <i class="bi bi-journal-bookmark-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Examinations &amp; GPA Marksheets</h5>
                                <small class="text-muted">Academic Grading</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Custom grading criteria, terminal marks entry, tabulations, automated GPA conversion, and printable report cards.
                        </p>
                    </div>
                </div>

                {{-- Module 5 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-danger bg-opacity-10 text-danger me-3">
                                <i class="bi bi-calendar2-check-fill fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">Attendance &amp; Bio-Logs</h5>
                                <small class="text-muted">Real-Time Tracking</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Daily student and staff attendance registers, absent notifications, monthly rate analytics, and printable attendance sheets.
                        </p>
                    </div>
                </div>

                {{-- Module 6 --}}
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-3 p-3 bg-secondary bg-opacity-10 text-secondary me-3">
                                <i class="bi bi-qr-code-scan fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">QR Document Verification</h5>
                                <small class="text-muted">Tamper-Proof Certificates</small>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">
                            Automated QR code embedding on certificates, character documents, and marksheets with instant online authenticity verification.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Institutional MoU Partnership Inquiry Section -->
    <section class="py-5 py-lg-6 bg-light" id="partner-mou">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold text-uppercase small mb-3">Institutional Partnerships</span>
                    <h2 class="display-6 fw-bold mb-3">Sign an MoU &amp; Transform Your School Today</h2>
                    <p class="text-muted mb-4">
                        Join our growing network of progressive schools, high schools, and colleges. Our deployment engineers handle complete data migration, staff training, and ongoing cloud hosting.
                    </p>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Tailored Onboarding for Your School</h6>
                                <p class="text-muted small mb-0">Custom grade scales, semester/terminal exam systems, and Bikram Sambat fee calendars.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Dedicated Account Management &amp; Training</h6>
                                <p class="text-muted small mb-0">Hands-on workshops for administrators, teachers, and accounting bursars.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success fs-5 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-1">99.9% Uptime &amp; Bank-Grade Data Security</h6>
                                <p class="text-muted small mb-0">Isolated institutional database backups and encrypted session management.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold mb-2">Request an MoU Presentation</h4>
                        <p class="text-muted small mb-4">Submit your school details to schedule a live demonstration and partnership consultation.</p>

                        <form action="{{ route('contact') }}" method="GET">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">School / College Name</label>
                                <input type="text" class="form-control rounded-3" placeholder="e.g. Green Peace Lincoln College" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Contact Person</label>
                                    <input type="text" class="form-control rounded-3" placeholder="Principal / Administrator" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Phone Number</label>
                                    <input type="tel" class="form-control rounded-3" placeholder="+977 98XXXXXXXX" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Official Email Address</label>
                                <input type="email" class="form-control rounded-3" placeholder="info@school.edu.np" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Approximate Student Count</label>
                                <select class="form-select rounded-3">
                                    <option value="100-500">100 – 500 Students</option>
                                    <option value="500-1500" selected>500 – 1,500 Students</option>
                                    <option value="1500+">1,500+ Students (Campus / Multi-Branch)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-brand-primary w-100 rounded-pill py-3 fw-bold">
                                <i class="bi bi-send-fill me-2"></i> Submit MoU Inquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- School Login Modal (Role-Based Workspace Selection) -->
    <div class="modal fade" id="schoolLoginModal" tabindex="-1" aria-labelledby="schoolLoginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <div>
                        <h5 class="modal-title fw-bold" id="schoolLoginModalLabel">Sign In to Your School Workspace</h5>
                        <p class="text-muted small mb-0">Select your institutional role to continue</p>
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
                                <div class="fw-bold">School Administrator &amp; Staff</div>
                                <small class="text-muted">Student directory, exams &amp; attendance</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>

                        <a href="{{ route('accounting.login') }}" class="btn btn-outline-success d-flex align-items-center p-3 rounded-3 text-start">
                            <div class="rounded-3 p-2 bg-success bg-opacity-10 text-success me-3">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">Bursar &amp; Finance Accountant</div>
                                <small class="text-muted">Fee invoices, expenses &amp; ledger</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>

                        <a href="{{ route('admin.login') }}" class="btn btn-outline-info d-flex align-items-center p-3 rounded-3 text-start">
                            <div class="rounded-3 p-2 bg-info bg-opacity-10 text-info me-3">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">Parent &amp; Student Portal</div>
                                <small class="text-muted">Attendance, due fees &amp; report cards</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                    <small class="text-muted text-center">New institution? <a href="#partner-mou" data-bs-dismiss="modal" class="text-success fw-bold">Sign an MoU with us</a></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 border-top">
        <div class="container text-center">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    &copy; {{ date('Y') }} <strong>NSMS SaaS Platform</strong>. Empowering Educational Institutions.
                </div>
                <div class="d-flex gap-3 small">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">Partner School Site</a>
                    <a href="{{ route('accounting.login') }}" class="text-decoration-none text-muted">Finance Login</a>
                    <a href="{{ route('admin.login') }}" class="text-decoration-none text-muted">Staff Login</a>
                    <a href="#partner-mou" class="text-decoration-none text-muted">MoU Inquiries</a>
                    <a href="{{ route('privacy.policy') }}" class="text-decoration-none text-muted">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
