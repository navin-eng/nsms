<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    @php($siteSettings = \App\Models\SiteSetting::current())
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Portal — {{ $siteSettings->site_name }}</title>
    
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: {{ $siteSettings->primary_color }};
            --primary-dark: {{ $siteSettings->primary_dark }};
        }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
        }
        [data-bs-theme="dark"] body {
            background: #0f172a;
        }
        .portal-container {
            width: 100%;
            max-width: 900px;
        }
        .portal-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .portal-header img {
            height: 70px;
            margin-bottom: 20px;
        }
        .portal-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
        }
        [data-bs-theme="dark"] .portal-header h1 { color: #f8fafc; }
        .portal-header p {
            color: #64748b;
            font-size: 16px;
        }
        [data-bs-theme="dark"] .portal-header p { color: #94a3b8; }
        
        .portal-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .portal-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        [data-bs-theme="dark"] .portal-card {
            background: #1e293b;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .portal-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 12px 30px rgba(var(--primary), 0.15);
        }
        .portal-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(82, 183, 136, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }
        .portal-card:hover .portal-icon {
            background: var(--primary);
            color: #ffffff;
        }
        .portal-card h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }
        [data-bs-theme="dark"] .portal-card h3 { color: #f8fafc; }
        .portal-card p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 0;
        }
        [data-bs-theme="dark"] .portal-card p { color: #94a3b8; }
        
        .portal-footer {
            text-align: center;
            margin-top: 40px;
            color: #94a3b8;
            font-size: 13px;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        [data-bs-theme="dark"] .logout-btn {
            background: #1e293b;
            color: #cbd5e1;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .logout-btn:hover {
            color: #ef4444;
        }
    </style>
</head>
<body>

    <a href="{{ route('admin.logout') }}" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>

    <div class="portal-container">
        <div class="portal-header">
            <img src="{{ $siteSettings->site_logo ? asset('storage/' . $siteSettings->site_logo) : asset('backend/images/logo.png') }}" alt="Logo">
            <h1>Welcome, {{ Auth::user()->name }}!</h1>
            <p>Please select the system you wish to manage.</p>
        </div>
        
        <div class="portal-cards">
            <!-- Website Management System -->
            <a href="{{ route('admin.dashboard') }}" class="portal-card">
                <div class="portal-icon">
                    <i class="bi bi-globe"></i>
                </div>
                <h3>Website Management System</h3>
                <p>Manage the public-facing college website. Update banners, notices, events, galleries, and layout settings.</p>
            </a>

            <!-- School Management System -->
            <a href="{{ route('sms.dashboard') }}" class="portal-card">
                <div class="portal-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h3>School Management System</h3>
                <p>Manage internal academic operations. Access student records, exam results, gradebooks, and attendance.</p>
            </a>
        </div>
        
        <div class="portal-footer">
            &copy; {{ date('Y') }} {{ $siteSettings->site_name }}. All Rights Reserved.
        </div>
    </div>

</body>
</html>
