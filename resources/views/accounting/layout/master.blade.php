<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@stack('b-title') — GPLC Accounting</title>

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

  <link rel="icon"
    href="{{ \App\Models\SiteSetting::current()->site_favicon ? asset('storage/' . \App\Models\SiteSetting::current()->site_favicon) : asset('backend/images/favicon.ico') }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Datatables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

  <!-- Nepali Datepicker CSS -->
  <link rel="stylesheet" href="{{ asset('backend/libs/nepali-datepicker/nepali.datepicker.v5.0.6.min.css') }}">

  <!-- Main Stylesheet -->
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Summernote -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
  <!-- LightGallery -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/css/lightgallery-bundle.min.css">
  <!-- Admin CSS -->
  <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">

  @stack('styles')
  @php($siteSettings = \App\Models\SiteSetting::current())
  <style>
    :root {
      --sb-active: {{ $siteSettings->primary_dark }};
      --sb-accent: {{ $siteSettings->accent_color }};
      --green: {{ $siteSettings->primary_dark }};
      --green-mid: {{ $siteSettings->primary_color }};
      --green-light: {{ $siteSettings->primary_light }};
      --green-pale: {{ $siteSettings->primary_light }}1a;
      --sb-width: 260px;
    }

    [data-bs-theme="dark"] {
      --green-pale: {{ $siteSettings->primary_light }}40;
    }

    /* Force Nepali Date Picker to be above modals */
    #ndp-nepali-box, .ndp-container, .ndp-datepicker, .ndp-panel {
      z-index: 99999 !important;
    }

    /* =====================================================================
       ACCOUNTING PORTAL - MOBILE & UNIVERSAL RESPONSIVE DESIGN SYSTEM
       ===================================================================== */

    /* Universal Box Sizing & Fluid Layout */
    *, *::before, *::after {
      box-sizing: border-box;
    }

    html, body {
      overflow-x: hidden;
      max-width: 100vw;
    }

    /* Sidebar Drawer on Mobile & Tablet */
    @media (max-width: 991.98px) {
      .admin-sidebar {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        bottom: 0 !important;
        width: min(300px, 85vw) !important;
        max-width: 320px !important;
        height: 100vh !important;
        z-index: 1060 !important;
        transform: translateX(-100%) !important;
        transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.28s ease !important;
        box-shadow: none;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
      }

      .admin-sidebar.open {
        transform: translateX(0) !important;
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.45) !important;
      }

      .admin-main {
        margin-left: 0 !important;
        width: 100% !important;
        min-width: 0 !important;
        transition: none !important;
      }

      .sb-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        z-index: 1055;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
      }

      .sb-overlay.show {
        opacity: 1;
        visibility: visible;
        display: block !important;
      }

      /* Mobile Close button */
      .sb-mobile-close {
        display: flex !important;
      }
    }

    @media (min-width: 992px) {
      .admin-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: var(--sb-width);
        z-index: 1030;
        transform: translateX(0) !important;
      }

      .admin-main {
        margin-left: var(--sb-width) !important;
        width: calc(100% - var(--sb-width)) !important;
      }

      .admin-main.sb-collapsed {
        margin-left: 70px !important;
        width: calc(100% - 70px) !important;
      }

      .sb-mobile-close {
        display: none !important;
      }
    }

    /* Topbar Mobile Ergonomics */
    .admin-topbar {
      position: sticky;
      top: 0;
      z-index: 1020;
      background: var(--bs-body-bg, #ffffff);
      min-height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.5rem 1rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    @media (max-width: 575.98px) {
      .admin-topbar {
        padding: 0.4rem 0.75rem;
      }
      .topbar-breadcrumb {
        display: none !important;
      }
      .topbar-actions {
        gap: 0.35rem !important;
      }
      .topbar-user {
        padding: 0.25rem 0.5rem !important;
      }
      .topbar-user-info {
        display: none !important;
      }
    }

    /* Responsive Content Area */
    .admin-content {
      padding: 1.25rem 1rem !important;
      min-height: calc(100vh - 120px);
    }

    @media (max-width: 767.98px) {
      .admin-content {
        padding: 0.875rem 0.625rem !important;
      }
    }

    @media (min-width: 992px) {
      .admin-content {
        padding: 1.75rem 2rem !important;
      }
    }

    /* Responsive Stat Numbers */
    .stat-num, .stat-value {
      font-size: clamp(1.35rem, 3.5vw, 1.85rem) !important;
      word-break: break-word;
    }

    /* Touch-friendly touch targets on mobile */
    @media (max-width: 767.98px) {
      .btn {
        min-height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
      }
      .sb-link {
        padding: 0.75rem 1rem !important;
        font-size: 0.925rem;
      }
    }

    /* Responsive Tables */
    .table-responsive {
      border-radius: 0.5rem;
      -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 767.98px) {
      .table > :not(caption) > * > * {
        padding: 0.55rem 0.65rem;
        font-size: 0.85rem;
      }
      .table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
      }
    }

    /* Form and Filter Mobile Optimization */
    @media (max-width: 575.98px) {
      .filter-form-mobile {
        flex-direction: column !important;
        align-items: stretch !important;
      }
      .filter-form-mobile input,
      .filter-form-mobile select,
      .filter-form-mobile button {
        width: 100% !important;
      }
    }

    /* Modals Mobile Friendly */
    @media (max-width: 575.98px) {
      .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
      }
      .modal-content {
        border-radius: 0.75rem;
      }
      .modal-header, .modal-body, .modal-footer {
        padding: 1rem;
      }
    }
  </style>
</head>

<body>

  {{-- ── Delete Confirm Overlay ── --}}
  <div class="alertBox" id="alertBox">
    <div class="delete-alert">
      <div class="del-icon"><i class="bi bi-trash3"></i></div>
      <h2>Delete this item?</h2>
      <p>This action cannot be undone. The record will be permanently removed.</p>
      <div class="del-actions">
        <button class="btn-admin btn-admin-light cancel" id="cancelDeleteBtn">
          <i class="bi bi-x-lg"></i> Cancel
        </button>
        <a class="btn-admin btn-admin-danger DeletNow">
          <i class="bi bi-trash3"></i> Yes, Delete
        </a>
      </div>
    </div>
  </div>

  {{-- ── Sidebar overlay (mobile) ── --}}
  <div class="sb-overlay" id="sbOverlay"></div>

  {{-- ── Sidebar ── --}}
  @include('accounting.layout.sidebar')

  {{-- ── Main Wrapper ── --}}
  <div class="admin-main" id="adminMain">

    @include('accounting.layout.header')

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

      @if(session('oops'))
        <div class="admin-alert admin-alert-warning">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <div class="alert-body">{{ session('oops') }}</div>
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

      @yield('backend-content')
      @yield('content')
    </div>

    @include('backend.pages.layout.footer')
  </div>

  @include('sweetalert::alert')

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/lightgallery.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

  <!-- Nepali Datepicker JS -->
  <script src="{{ asset('backend/libs/nepali-datepicker/nepali.datepicker.v5.0.6.min.js') }}"></script>

  <!-- Template JS -->
  <script src="{{ asset('backend/js/logic.js') }}"></script>

  <script>
    /* Responsive Mobile Sidebar Controller */
    const adminSidebar = document.getElementById('adminSidebar');
    const sbOverlay = document.getElementById('sbOverlay');
    const adminMain = document.getElementById('adminMain');
    const mobileCloseBtn = document.getElementById('sbMobileCloseBtn');

    function openSidebar() {
      if (!adminSidebar) return;
      adminSidebar.classList.add('open');
      if (sbOverlay) sbOverlay.classList.add('show');
      document.body.style.overflow = window.innerWidth < 992 ? 'hidden' : '';
    }

    function closeSidebar() {
      if (!adminSidebar) return;
      adminSidebar.classList.remove('open');
      if (sbOverlay) sbOverlay.classList.remove('show');
      document.body.style.overflow = '';
    }

    document.getElementById('sidebarToggle')?.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      if (window.innerWidth < 992) {
        adminSidebar.classList.contains('open') ? closeSidebar() : openSidebar();
      } else {
        adminSidebar.classList.toggle('collapsed');
        if (adminMain) adminMain.classList.toggle('sb-collapsed');
        localStorage.setItem('sbCollapsed', adminSidebar.classList.contains('collapsed') ? '1' : '0');
      }
    });

    sbOverlay?.addEventListener('click', closeSidebar);
    mobileCloseBtn?.addEventListener('click', closeSidebar);

    // Auto-close mobile sidebar when clicking menu links on mobile
    if (window.innerWidth < 992) {
      document.querySelectorAll('.admin-sidebar .sb-link:not([data-bs-toggle="collapse"])').forEach(link => {
        link.addEventListener('click', () => {
          setTimeout(closeSidebar, 150);
        });
      });
    }

    // Handle resize
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 992) {
        closeSidebar();
      }
    });

    /* Restore collapse state on desktop load */
    if (window.innerWidth >= 992 && localStorage.getItem('sbCollapsed') === '1') {
      adminSidebar?.classList.add('collapsed');
      adminMain?.classList.add('sb-collapsed');
    }

    /* User dropdown */
    document.getElementById('userMenuBtn')?.addEventListener('click', function (e) {
      e.stopPropagation();
      document.getElementById('userDropdown').classList.toggle('show');
    });
    document.addEventListener('click', function () {
      document.getElementById('userDropdown')?.classList.remove('show');
    });

    /* Delete confirm */
    document.getElementById('cancelDeleteBtn')?.addEventListener('click', function () {
      document.getElementById('alertBox').classList.remove('open');
    });

    /* LightGallery */
    const lgEl = document.getElementById('galleryRow');
    if (lgEl && typeof lightGallery !== 'undefined') {
      lightGallery(lgEl, { speed: 500, download: false });
    }
  </script>

  @stack('scripts')
  <script>
    // Global Theme Toggle Function
    function toggleTheme() {
      const html = document.documentElement;
      const current = html.getAttribute('data-bs-theme');
      const next = current === 'dark' ? 'light' : 'dark';
      html.setAttribute('data-bs-theme', next);
      localStorage.setItem('theme', next);

      window.dispatchEvent(new CustomEvent('themeChanged', { detail: next }));
    }

    document.addEventListener('DOMContentLoaded', () => {
      const updateThemeIcon = () => {
        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        document.querySelectorAll('.theme-toggle-icon').forEach(icon => {
          icon.className = isDark ? 'bi bi-sun theme-toggle-icon' : 'bi bi-moon theme-toggle-icon';
        });
      };

      updateThemeIcon();
      window.addEventListener('themeChanged', updateThemeIcon);

      document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          toggleTheme();
        });
      });
    });
  </script>
</body>

</html>