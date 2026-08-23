<aside class="admin-sidebar" id="adminSidebar">

  {{-- Logo --}}
  <a href="{{ route('accounting.dashboard') }}" class="sb-logo">
    <img
      src="{{ \App\Models\SiteSetting::current()->site_logo ? asset('storage/' . \App\Models\SiteSetting::current()->site_logo) : asset('backend/images/logo.png') }}"
      alt="GPLC">
    <div class="sb-logo-text">
      <span class="sb-name">GPLC Accounting</span>
      <span class="sb-sub">Green Peace Lincoln College</span>
    </div>
  </a>

  {{-- Search Box --}}
  <div class="px-3 py-2 border-bottom border-secondary border-opacity-25 mb-2">
    <div class="input-group input-group-sm">
      <span class="input-group-text bg-transparent border-0 text-white-50 pe-1"><i class="bi bi-search"></i></span>
      <input type="text" class="form-control bg-transparent border-0 text-white shadow-none" id="sidebarSearch"
        placeholder="Search menu..." style="font-size: 0.85rem;">
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="sb-nav">
    <ul class="sb-menu accordion" id="sb-menu">

      {{-- Overview --}}
      <li class="sb-group-label"><span class="sb-text">Overview</span></li>
      <li class="sb-item">
        <a href="{{ route('accounting.dashboard') }}" class="sb-link {{ request()->routeIs('accounting.dashboard') ? 'active' : '' }}" title="Dashboard">
          <i class="bi bi-grid-1x2-fill"></i>
          <span class="sb-text">Dashboard</span>
        </a>
      </li>

      {{-- Transactions --}}
      <li class="sb-group-label"><span class="sb-text">Transactions</span></li>

      {{-- Fee Collections Dropdown --}}
      <li class="sb-item">
        <a class="sb-link {{ request()->routeIs('accounting.fees.*') ? 'active' : '' }}" data-bs-toggle="collapse"
          href="#sbFees" aria-expanded="{{ request()->routeIs('accounting.fees.*') ? 'true' : 'false' }}"
          title="Fee Collections">
          <i class="bi bi-cash-coin"></i>
          <span class="sb-text">Fee Collections</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('accounting.fees.*') ? 'show' : '' }}" id="sbFees"
          data-bs-parent="#sb-menu">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('accounting.fees.fee-types.index') }}"
                class="sb-link {{ request()->routeIs('accounting.fees.fee-types.*') ? 'active' : '' }}">
                <i class="bi bi-dot"></i>
                <span class="sb-text">Fee Types</span>
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('accounting.fees.fee-structures.index') }}"
                class="sb-link {{ request()->routeIs('accounting.fees.fee-structures.*') ? 'active' : '' }}">
                <i class="bi bi-dot"></i>
                <span class="sb-text">Fee Structures</span>
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('accounting.fees.invoices.index') }}"
                class="sb-link {{ request()->routeIs('accounting.fees.invoices.*') && !request()->routeIs('accounting.fees.invoices.generate') ? 'active' : '' }}">
                <i class="bi bi-dot"></i>
                <span class="sb-text">Invoices</span>
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('accounting.fees.invoices.generate') }}"
                class="sb-link {{ request()->routeIs('accounting.fees.invoices.generate') ? 'active' : '' }}">
                <i class="bi bi-dot"></i>
                <span class="sb-text">Generate Invoices</span>
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('accounting.fees.reports.outstanding') }}"
                class="sb-link {{ request()->routeIs('accounting.fees.reports.outstanding') ? 'active' : '' }}">
                <i class="bi bi-dot"></i>
                <span class="sb-text">Outstanding Report</span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      {{-- Expenses --}}
      <li class="sb-item">
        <a href="{{ route('accounting.expenses.index') }}" class="sb-link {{ request()->routeIs('accounting.expenses.*') ? 'active' : '' }}" title="Expenses">
          <i class="bi bi-cart-dash-fill"></i>
          <span class="sb-text">Expenses</span>
        </a>
      </li>

      {{-- Vendors --}}
      <li class="sb-item">
        <a href="{{ route('accounting.vendors.index') }}" class="sb-link {{ request()->routeIs('accounting.vendors.*') ? 'active' : '' }}" title="Vendors">
          <i class="bi bi-shop"></i>
          <span class="sb-text">Vendors</span>
        </a>
      </li>

      {{-- Banking & Management --}}
      <li class="sb-group-label"><span class="sb-text">Management</span></li>

      {{-- Bank Accounts --}}
      <li class="sb-item">
        <a href="{{ route('accounting.banks.index') }}" class="sb-link {{ request()->routeIs('accounting.banks.*') ? 'active' : '' }}" title="Bank Accounts">
          <i class="bi bi-bank2"></i>
          <span class="sb-text">Bank Accounts</span>
        </a>
      </li>

      {{-- Budgets --}}
      <li class="sb-item">
        <a href="{{ route('accounting.budgets.index') }}" class="sb-link {{ request()->routeIs('accounting.budgets.*') ? 'active' : '' }}" title="Budgets">
          <i class="bi bi-pie-chart-fill"></i>
          <span class="sb-text">Budgets</span>
        </a>
      </li>

      {{-- Financial Reports Dropdown --}}
      <li class="sb-group-label"><span class="sb-text">Reports</span></li>
      <li class="sb-item">
        <a class="sb-link {{ request()->routeIs('accounting.reports.*') ? 'active' : '' }}" data-bs-toggle="collapse"
          href="#sbReports" aria-expanded="{{ request()->routeIs('accounting.reports.*') ? 'true' : 'false' }}"
          title="Financial Reports">
          <i class="bi bi-file-earmark-bar-graph-fill"></i>
          <span class="sb-text">Financial Reports</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->routeIs('accounting.reports.*') ? 'show' : '' }}" id="sbReports"
          data-bs-parent="#sb-menu">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('accounting.reports.income-statement') }}"
                class="sb-link {{ request()->routeIs('accounting.reports.income-statement') ? 'active' : '' }}">
                <i class="bi bi-dot"></i>
                <span class="sb-text">Income Statement</span>
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('accounting.reports.balance-sheet') }}"
                class="sb-link {{ request()->routeIs('accounting.reports.balance-sheet') ? 'active' : '' }}">
                <i class="bi bi-dot"></i>
                <span class="sb-text">Balance Sheet</span>
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('accounting.reports.trial-balance') }}"
                class="sb-link {{ request()->routeIs('accounting.reports.trial-balance') ? 'active' : '' }}">
                <i class="bi bi-dot"></i>
                <span class="sb-text">Trial Balance</span>
              </a>
            </li>
          </ul>
        </div>
      </li>

    </ul>
  </nav>

</aside>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('sidebarSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function (e) {
      const searchTerm = e.target.value.toLowerCase().trim();
      const sidebar = document.getElementById('adminSidebar');
      if (!sidebar) return;
      const items = sidebar.querySelectorAll('.sb-item');
      const groups = sidebar.querySelectorAll('.sb-group-label');

      if (searchTerm === '') {
        items.forEach(item => item.style.display = '');
        groups.forEach(group => group.style.display = '');
        return;
      }

      items.forEach(item => {
        const link = item.querySelector('.sb-link');
        if (!link) return;
        const text = link.textContent.toLowerCase();

        if (text.includes(searchTerm)) {
          item.style.display = '';
          const parentCollapse = item.closest('.collapse');
          if (parentCollapse) {
            parentCollapse.classList.add('show');
            const parentItem = parentCollapse.closest('.sb-item');
            if (parentItem) parentItem.style.display = '';
          }
        } else {
          const childSubmenu = item.querySelector('.sb-submenu');
          if (childSubmenu) {
            const childLinks = Array.from(childSubmenu.querySelectorAll('.sb-link'));
            const hasMatchingChild = childLinks.some(cl => cl.textContent.toLowerCase().includes(searchTerm));
            item.style.display = hasMatchingChild ? '' : 'none';
          } else {
            item.style.display = 'none';
          }
        }
      });

      groups.forEach(group => {
        let next = group.nextElementSibling;
        let hasVisibleItem = false;
        while (next && !next.classList.contains('sb-group-label')) {
          if (next.style.display !== 'none' && next.classList.contains('sb-item')) {
            hasVisibleItem = true;
            break;
          }
          next = next.nextElementSibling;
        }
        group.style.display = hasVisibleItem ? '' : 'none';
      });
    });
  });
</script>