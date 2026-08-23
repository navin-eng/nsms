<aside class="admin-sidebar" id="adminSidebar">

  {{-- Provider Logo / Branding --}}
  <a href="{{ route('provider.dashboard') }}" class="sb-logo">
    <div class="rounded-2 p-2 d-flex align-items-center justify-content-center me-2" style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); width: 38px; height: 38px;">
      <i class="bi bi-cpu-fill text-success" style="font-size: 1.25rem;"></i>
    </div>
    <div class="sb-logo-text">
      <span class="sb-name">NSMS Provider</span>
      <span class="sb-sub" style="letter-spacing: 0.5px; color: #34d399;">GOD MODE CONSOLE</span>
    </div>
  </a>

  {{-- Live Menu Search Box --}}
  <div class="px-3 py-2 border-bottom border-secondary border-opacity-25 mb-2">
    <div class="input-group input-group-sm">
      <span class="input-group-text bg-transparent border-0 text-white-50 pe-1"><i class="bi bi-search"></i></span>
      <input type="text" class="form-control bg-transparent border-0 text-white shadow-none" id="providerSidebarSearch"
        placeholder="Search provider menu..." style="font-size: 0.85rem;">
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="sb-nav">
    <ul class="sb-menu accordion" id="sb-menu">

      {{-- Overview Section --}}
      <li class="sb-group-label"><span class="sb-text">Core Platform</span></li>
      <li class="sb-item">
        <a href="{{ route('provider.dashboard') }}" class="sb-link {{ request()->routeIs('provider.dashboard') ? 'active' : '' }}" title="Platform Telemetry">
          <i class="bi bi-grid-1x2-fill"></i><span class="sb-text">Platform Dashboard</span>
        </a>
      </li>

      {{-- Multi-Tenant Management Section --}}
      <li class="sb-group-label"><span class="sb-text">School Tenants</span></li>
      <li class="sb-item">
        <a class="sb-link {{ request()->routeIs('provider.schools.*') ? 'active' : '' }}" data-bs-toggle="collapse"
          href="#sbProviderSchools" aria-expanded="{{ request()->routeIs('provider.schools.*') ? 'true' : 'false' }}"
          title="School Management">
          <i class="bi bi-buildings-fill"></i>
          <span class="sb-text">School Tenants</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('provider.schools.*') ? 'show' : '' }}" id="sbProviderSchools" data-bs-parent="#sb-menu">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('provider.schools.index') }}" class="sb-link {{ request()->routeIs('provider.schools.index') ? 'active' : '' }}">
                <i class="bi bi-dot"></i><span class="sb-text">All Partner Schools</span>
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('provider.schools.create') }}" class="sb-link {{ request()->routeIs('provider.schools.create') ? 'active' : '' }}">
                <i class="bi bi-dot"></i><span class="sb-text">Onboard New School</span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      {{-- Public Links & Settings --}}
      <li class="sb-group-label"><span class="sb-text">Quick Actions</span></li>
      <li class="sb-item">
        <a href="{{ route('secure.login') }}" target="_blank" class="sb-link" title="SaaS Public Landing">
          <i class="bi bi-globe2"></i><span class="sb-text">SaaS Public Portal</span>
          <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 0.75rem; opacity: 0.7;"></i>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('home') }}" target="_blank" class="sb-link" title="Demo School Site">
          <i class="bi bi-mortarboard"></i><span class="sb-text">Demo School Site</span>
          <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 0.75rem; opacity: 0.7;"></i>
        </a>
      </li>

    </ul>
  </nav>

</aside>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('providerSidebarSearch');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const items = document.querySelectorAll('.sb-nav .sb-item');
        const groups = document.querySelectorAll('.sb-nav .sb-group-label');

        if (!query) {
          items.forEach(el => el.style.display = '');
          groups.forEach(el => el.style.display = '');
          return;
        }

        items.forEach(el => {
          const text = el.textContent.toLowerCase();
          if (text.includes(query)) {
            el.style.display = '';
            const parentCollapse = el.closest('.collapse');
            if (parentCollapse) {
              parentCollapse.classList.add('show');
            }
          } else {
            el.style.display = 'none';
          }
        });

        groups.forEach(el => {
          el.style.display = 'none';
        });
      });
    }
  });
</script>
