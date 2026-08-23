<header class="admin-topbar">

  {{-- Sidebar toggle --}}
  <button class="topbar-toggle" id="sidebarToggle" title="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>

  {{-- Breadcrumb / Page Info --}}
  <div class="topbar-breadcrumb">
    <p class="page-title">@yield('page-title', 'SaaS Provider Control Center')</p>
    <p class="breadcrumb-trail">
      <a href="{{ route('provider.dashboard') }}">Provider God Mode</a>
      @if(View::hasSection('breadcrumb'))
        &rsaquo; @yield('breadcrumb')
      @endif
    </p>
  </div>

  {{-- Actions --}}
  <div class="topbar-actions">

    {{-- View SaaS Landing --}}
    <a href="{{ route('secure.login') }}" target="_blank" class="topbar-btn" title="View Public Landing">
      <i class="bi bi-globe2"></i>
    </a>

    {{-- Toggle Theme --}}
    <button class="topbar-btn theme-toggle-btn" title="Toggle Theme" style="background: none; border: none; padding: 0;">
      <i class="bi bi-moon theme-toggle-icon"></i>
    </button>

    <div class="topbar-divider"></div>

    {{-- Provider User Menu --}}
    @if(Auth::guard('provider')->check())
      @php($user = Auth::guard('provider')->user())
      <div style="position:relative;">
        <div class="topbar-user" id="userMenuBtn">
          <div class="topbar-user-avatar-placeholder" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); color: #ffffff;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
          </div>
          <div class="topbar-user-info">
            <span class="u-name">{{ $user->name }}</span>
            <span class="u-role" style="color: #34d399; font-weight: 600;">{{ $user->role }} (God Mode)</span>
          </div>
          <i class="bi bi-chevron-down" style="font-size:11px;color:#718096;margin-left:4px;"></i>
        </div>

        <div class="user-dropdown" id="userDropdown">
          <div class="user-dropdown-header">
            <div class="ud-name">{{ $user->name }}</div>
            <div class="ud-role">{{ $user->email }}</div>
          </div>
          <a href="{{ route('provider.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Telemetry Dashboard
          </a>
          <a href="{{ route('provider.schools.index') }}">
            <i class="bi bi-buildings"></i> Partner Schools
          </a>
          <div class="ud-divider"></div>
          <form action="{{ route('provider.logout') }}" method="POST" id="providerLogoutForm">
            @csrf
            <a href="javascript:void(0)" onclick="document.getElementById('providerLogoutForm').submit();" class="ud-danger">
              <i class="bi bi-box-arrow-right"></i> Sign Out of God Mode
            </a>
          </form>
        </div>
      </div>
    @endif

  </div>
</header>
