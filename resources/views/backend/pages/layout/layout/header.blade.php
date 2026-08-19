<header class="admin-topbar">

  {{-- Sidebar toggle --}}
  <button class="topbar-toggle" id="sidebarToggle" title="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>

  {{-- Breadcrumb / page info --}}
  <div class="topbar-breadcrumb">
    <p class="page-title">@stack('b-title')</p>
    <p class="breadcrumb-trail">
      <a href="{{ url('admin/dashboard') }}">Dashboard</a>
      @hasSection('breadcrumb')
        &rsaquo; @yield('breadcrumb')
      @endif
    </p>
  </div>

  {{-- Actions --}}
  <div class="topbar-actions">

    {{-- View site --}}
    <a href="{{ url('/') }}" target="_blank" class="topbar-btn" title="View website">
      <i class="bi bi-box-arrow-up-right"></i>
    </a>

    <div class="topbar-divider"></div>

    {{-- User menu --}}
    @auth
    <div style="position:relative;">
      <div class="topbar-user" id="userMenuBtn">
        @if(Auth::user()->image && file_exists(public_path(Auth::user()->image)))
          <img src="{{ asset(Auth::user()->image) }}" alt="avatar" class="topbar-user-avatar">
        @else
          <div class="topbar-user-avatar-placeholder">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          </div>
        @endif
        <div class="topbar-user-info">
          <span class="u-name">{{ Auth::user()->name }}</span>
          <span class="u-role">{{ Auth::user()->a_type == 'A' ? 'Administrator' : 'Editor' }}</span>
        </div>
        <i class="bi bi-chevron-down" style="font-size:11px;color:#718096;margin-left:4px;"></i>
      </div>

      <div class="user-dropdown" id="userDropdown">
        <div class="user-dropdown-header">
          <div class="ud-name">{{ Auth::user()->name }}</div>
          <div class="ud-role">{{ Auth::user()->a_type == 'A' ? 'Administrator' : 'Editor' }}</div>
        </div>
        <a href="{{ route('admin.profile') }}">
          <i class="bi bi-person-circle"></i> My Profile
        </a>
        <a href="{{ url('/') }}" target="_blank">
          <i class="bi bi-globe"></i> View Website
        </a>
        <div class="ud-divider"></div>
        <a href="{{ url('admin/dashboard/logout') }}" class="ud-danger">
          <i class="bi bi-box-arrow-right"></i> Sign Out
        </a>
      </div>
    </div>
    @endauth

  </div>
</header>
