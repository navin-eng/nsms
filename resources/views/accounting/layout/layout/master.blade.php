<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@stack('b-title') — GPLC Admin</title>
  <link rel="icon" href="{{ \App\Models\SiteSetting::current()->site_favicon ? asset('storage/' . \App\Models\SiteSetting::current()->site_favicon) : asset('backend/images/favicon.ico') }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
@include('backend.pages.layout.sidebar')

{{-- ── Main Wrapper ── --}}
<div class="admin-main" id="adminMain">

  @include('backend.pages.layout.header')

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
  </div>

  @include('backend.pages.layout.footer')
</div>

@include('sweetalert::alert')

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery@2.7.1/lightgallery.min.js"></script>
<script src="{{ asset('backend/js/logic.js') }}"></script>

<script>
/* Sidebar toggle */
const adminSidebar = document.getElementById('adminSidebar');
const sbOverlay    = document.getElementById('sbOverlay');

function openSidebar()  { adminSidebar.classList.add('open');  sbOverlay.classList.add('show'); }
function closeSidebar() { adminSidebar.classList.remove('open'); sbOverlay.classList.remove('show'); }

document.getElementById('sidebarToggle')?.addEventListener('click', function() {
  if (adminSidebar.classList.contains('open')) closeSidebar();
  else openSidebar();
});
sbOverlay.addEventListener('click', closeSidebar);

/* User dropdown */
document.getElementById('userMenuBtn')?.addEventListener('click', function(e) {
  e.stopPropagation();
  document.getElementById('userDropdown').classList.toggle('show');
});
document.addEventListener('click', function() {
  document.getElementById('userDropdown')?.classList.remove('show');
});

/* Delete confirm */
document.getElementById('cancelDeleteBtn')?.addEventListener('click', function() {
  document.getElementById('alertBox').classList.remove('open');
});

/* LightGallery */
const lgEl = document.getElementById('galleryRow');
if (lgEl && typeof lightGallery !== 'undefined') {
  lightGallery(lgEl, { speed: 500, download: false });
}
</script>

@stack('scripts')
</body>
</html>
