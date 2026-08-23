<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'God Mode Console') — NSMS Provider</title>

  <!-- Theme Initialization (Prevents FOUC) -->
  <script>
    (function () {
      var theme = localStorage.getItem('theme');
      if (!theme) {
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      }
      document.documentElement.setAttribute('data-bs-theme', theme);
    })();
  </script>

  <link rel="icon" href="{{ \App\Models\SiteSetting::current()->site_favicon ? asset('storage/' . \App\Models\SiteSetting::current()->site_favicon) : asset('backend/images/favicon.ico') }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Datatables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- SMS & Admin Main CSS -->
  <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">

  @stack('styles')
  <style>
    :root {
      --sb-active: #047857;
      --sb-accent: #34d399;
      --green: #047857;
      --green-mid: #10b981;
      --green-light: #34d399;
      --font-mono: 'JetBrains Mono', monospace;
    }

    .mono {
      font-family: var(--font-mono);
    }

    .card-god-metric {
      border: 1px solid var(--border-color, #e2e8f0);
      border-radius: 12px;
      padding: 1.25rem;
      background: var(--bg-card, #ffffff);
      transition: all 0.2s ease;
    }

    [data-bs-theme="dark"] .card-god-metric {
      border-color: #334155;
      background: #1e293b;
    }

    .card-god-metric:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1);
    }
  </style>
</head>

<body>

  {{-- Sidebar overlay (mobile) --}}
  <div class="sb-overlay" id="sbOverlay"></div>

  {{-- Provider Sidebar --}}
  @include('provider.layout.sidebar')

  {{-- Main Wrapper --}}
  <div class="admin-main" id="adminMain">

    @include('provider.layout.header')

    <div class="admin-content">

      {{-- Flash alerts --}}
      @if(session('success'))
        <div class="admin-alert admin-alert-success">
          <i class="bi bi-check-circle-fill"></i>
          <div class="alert-body"><strong>Success!</strong> {{ session('success') }}</div>
          <button class="alert-close" onclick="this.closest('.admin-alert').remove()">&times;</button>
        </div>
      @endif

      @if(session('error'))
        <div class="admin-alert admin-alert-danger">
          <i class="bi bi-exclamation-circle-fill"></i>
          <div class="alert-body"><strong>Error!</strong> {{ session('error') }}</div>
          <button class="alert-close" onclick="this.closest('.admin-alert').remove()">&times;</button>
        </div>
      @endif

      @if($errors->any())
        <div class="admin-alert admin-alert-danger">
          <i class="bi bi-exclamation-circle-fill"></i>
          <div class="alert-body">
            <strong>Please fix the following:</strong>
            <ul style="margin:6px 0 0;padding-left:18px;">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          <button class="alert-close" onclick="this.closest('.admin-alert').remove()">&times;</button>
        </div>
      @endif

      @yield('content')
    </div>

    @include('provider.layout.footer')
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const adminSidebar = document.getElementById('adminSidebar');
    const sbOverlay = document.getElementById('sbOverlay');

    function openSidebar() { 
      adminSidebar?.classList.add('open'); 
      sbOverlay?.classList.add('show'); 
    }
    function closeSidebar() { 
      adminSidebar?.classList.remove('open'); 
      sbOverlay?.classList.remove('show'); 
    }

    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
      if (window.innerWidth <= 767) {
        adminSidebar?.classList.contains('open') ? closeSidebar() : openSidebar();
      } else {
        adminSidebar?.classList.toggle('collapsed');
        document.getElementById('adminMain')?.classList.toggle('sb-collapsed');
        localStorage.setItem('sbCollapsed', adminSidebar?.classList.contains('collapsed') ? '1' : '0');
      }
    });

    sbOverlay?.addEventListener('click', closeSidebar);

    if (window.innerWidth > 767 && localStorage.getItem('sbCollapsed') === '1') {
      adminSidebar?.classList.add('collapsed');
      document.getElementById('adminMain')?.classList.add('sb-collapsed');
    }

    // User Profile Dropdown
    document.getElementById('userMenuBtn')?.addEventListener('click', function (e) {
      e.stopPropagation();
      document.getElementById('userDropdown')?.classList.toggle('show');
    });

    document.addEventListener('click', function () {
      document.getElementById('userDropdown')?.classList.remove('show');
    });

    // Theme Toggle Function
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
        icon.className = isDark ? 'bi bi-sun theme-toggle-icon' : 'bi bi-moon theme-toggle-icon';
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

  @stack('scripts')
</body>
</html>
