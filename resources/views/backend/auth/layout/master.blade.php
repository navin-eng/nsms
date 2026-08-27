<!DOCTYPE html>
<html lang="en">

<head>
    @php($siteSettings = \App\Models\SiteSetting::current())
    <meta charset="utf-8" />
    @stack('user-title')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ \App\Models\SiteSetting::current()->site_favicon ? asset('storage/' . \App\Models\SiteSetting::current()->site_favicon) : asset('backend/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('backend/assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
    <style>
        body.authentication-bg {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(82, 183, 136, 0.18), transparent 24%),
                linear-gradient(135deg, #08130c 0%, {{ $siteSettings->primary_dark }} 46%, {{ $siteSettings->primary_color }} 100%);
        }
        .auth-shell {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            min-height: calc(100vh - 40px);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 80px rgba(0,0,0,0.24);
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
        }
        .auth-panel {
            padding: 48px;
            color: #fff;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
        }
        .auth-panel h1 {
            font-size: 2.4rem;
            font-weight: 800;
            line-height: 1.18;
            margin-bottom: 16px;
        }
        .auth-panel p {
            color: rgba(255,255,255,0.82);
            font-size: 1rem;
            line-height: 1.8;
        }
        .auth-kpis {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 28px;
        }
        .auth-kpi {
            padding: 18px;
            border-radius: 18px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .auth-kpi strong {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 6px;
        }
        .auth-card-wrap {
            background: #fff;
            padding: 22px;
        }
        .auth-card-wrap .card {
            border: 0;
            box-shadow: none;
        }
        .auth-badge {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.1);
            font-size: 0.9rem;
            margin-bottom: 18px;
        }
        @media (max-width: 991px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }
            .auth-panel {
                padding: 28px;
            }
        }
    </style>

</head>

<body class="loading authentication-bg"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-10 col-xl-11">
                    <div class="auth-shell">
                        <div class="auth-panel">
                            <div class="auth-badge">
                                <i class="bi bi-shield-lock-fill"></i>
                                Secure College Access
                            </div>
                            <h1>{{ $siteSettings->site_name }}</h1>
                            <p>{{ $siteSettings->site_tagline }}. This access point is for authorized administrators and editors managing academic programs, notices, campus updates, and website operations.</p>
                            <div class="auth-kpis">
                                <div class="auth-kpi">
                                    <strong>Programs</strong>
                                    <span>Manage courses, admissions content, and academic information.</span>
                                </div>
                                <div class="auth-kpi">
                                    <strong>Campus Updates</strong>
                                    <span>Publish notices, results, events, and public announcements.</span>
                                </div>
                                <div class="auth-kpi">
                                    <strong>Brand Control</strong>
                                    <span>Update color theme, site identity, and homepage layout.</span>
                                </div>
                                <div class="auth-kpi">
                                    <strong>Student Support</strong>
                                    <span>Track messages, FAQs, and outreach information from one place.</span>
                                </div>
                            </div>
                        </div>
                        <div class="auth-card-wrap">
                            <div class="card">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Success - </strong> {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Success - </strong> {{ session('success') }}
                            </div>
                        @endif
                        @if (session('oops'))
                            <div class="alert alert-warning alert-dismissible bg-warning text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Success - </strong> {{ session('oops') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <p><strong>Opps Something went wrong</strong></p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center">
                            <a href="{{ '#' }}">
                                <span><img src="{{ \App\Models\SiteSetting::current()->site_logo ? asset('storage/' . \App\Models\SiteSetting::current()->site_logo) : asset('backend/images/logo.png') }}" alt="" height="68"></span>
                            </a>
                        </div>
                        @yield('backend-auth-content')
                            </div>
                        </div>
                    </div>
                    <!-- end card -->

                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    <!-- bundle -->
    <script src="{{ asset('backend/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/app.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</body>

</html>
