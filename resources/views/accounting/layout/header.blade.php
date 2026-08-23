<header class="admin-topbar">

  {{-- Sidebar toggle (always visible, works on mobile & desktop) --}}
  <button class="topbar-toggle" id="sidebarToggle" type="button" title="Toggle navigation menu" aria-label="Toggle sidebar">
    <i class="bi bi-list fs-5"></i>
  </button>

  {{-- Breadcrumb / page info --}}
  <div class="topbar-breadcrumb">
    <p class="page-title text-truncate">
      @if(trim($__env->yieldPushContent('b-title')))
        @stack('b-title')
      @elseif(View::hasSection('title'))
        @yield('title')
      @else
        Accounting Portal
      @endif
    </p>
    <p class="breadcrumb-trail text-truncate">
      <a href="{{ route('accounting.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
      @if(View::hasSection('breadcrumb'))
        &rsaquo; @yield('breadcrumb')
      @endif
    </p>
  </div>

  {{-- Actions --}}
  <div class="topbar-actions d-flex align-items-center gap-2">

    {{-- View site --}}
    <a href="{{ url('/') }}" target="_blank" class="topbar-btn d-none d-sm-inline-flex" title="View website">
      <i class="bi bi-box-arrow-up-right"></i>
    </a>

    {{-- Toggle Theme --}}
    <button type="button" class="topbar-btn theme-toggle-btn" title="Toggle Dark/Light Theme">
      <i class="bi bi-moon theme-toggle-icon"></i>
    </button>

    <div class="topbar-divider"></div>

    {{-- User menu --}}
    @php
      $currentUser = Auth::guard('accounting')->user() ?? Auth::user();
      $isAccountantGuard = Auth::guard('accounting')->check();
      $roleTitle = $isAccountantGuard ? 'Accountant' : (Auth::user()?->a_type == 'A' ? 'Administrator' : 'Staff');
      $userName = $currentUser?->name ?? 'User';
      $firstLetter = strtoupper(substr($userName, 0, 1));
      $logoutUrl = $isAccountantGuard ? route('accounting.logout') : route('admin.logout');
    @endphp

    @if($currentUser)
    <div style="position:relative;">
      <div class="topbar-user" id="userMenuBtn" role="button" tabindex="0">
        @if(!empty($currentUser->image) && file_exists(public_path($currentUser->image)))
          <img src="{{ asset($currentUser->image) }}" alt="avatar" class="topbar-user-avatar">
        @else
          <div class="topbar-user-avatar-placeholder">
            {{ $firstLetter }}
          </div>
        @endif
        <div class="topbar-user-info d-none d-md-flex flex-column text-start ms-2">
          <span class="u-name text-truncate" style="max-width: 140px;">{{ $userName }}</span>
          <span class="u-role text-muted" style="font-size: 11px;">{{ $roleTitle }}</span>
        </div>
        <i class="bi bi-chevron-down ms-1" style="font-size:11px;color:#718096;"></i>
      </div>

      <div class="user-dropdown" id="userDropdown">
        <div class="user-dropdown-header px-3 py-2 border-bottom">
          <div class="ud-name fw-bold">{{ $userName }}</div>
          <div class="ud-role text-muted small">{{ $roleTitle }}</div>
          @if(!empty($currentUser->email))
            <div class="text-muted small text-truncate" style="font-size: 11px;">{{ $currentUser->email }}</div>
          @endif
        </div>
        <a href="{{ route('accounting.dashboard') }}" class="dropdown-item px-3 py-2 d-flex align-items-center">
          <i class="bi bi-grid-1x2 me-2"></i> Accounting Dashboard
        </a>
        <div class="ud-divider my-1 border-top"></div>
        <a href="{{ $logoutUrl }}" class="ud-danger dropdown-item px-3 py-2 text-danger d-flex align-items-center">
          <i class="bi bi-box-arrow-right me-2"></i> Sign Out
        </a>
      </div>
    </div>
    @endif

  </div>
</header>

