import re

file_path = "resources/views/backend/pages/layout/sidebar.blade.php"
with open(file_path, "r") as f:
    c = f.read()

sidebar_html = """        @can('manage_inventory')
          <li class="sb-group-label"><span class="sb-text">Inventory &amp; Assets</span></li>
          
          <li class="sb-item">
            <a href="#collapseInventory" class="sb-link {{ request()->routeIs('admin.inventory.*') ? '' : 'collapsed' }}" data-bs-toggle="collapse"
              aria-expanded="{{ request()->routeIs('admin.inventory.*') ? 'true' : 'false' }}">
              <i class="bi bi-box-seam"></i><span class="sb-text">Inventory & Assets</span><i
                class="bi bi-chevron-down sb-caret"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.inventory.*') ? 'show' : '' }}" id="collapseInventory"
              data-bs-parent="#sidebarMenu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('admin.inventory.categories.index') }}"
                    class="sb-link {{ request()->routeIs('admin.inventory.categories.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Categories</span></a></li>
                <li class="sb-item"><a href="{{ route('admin.inventory.stores.index') }}"
                    class="sb-link {{ request()->routeIs('admin.inventory.stores.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Stores</span></a></li>
                <li class="sb-item"><a href="{{ route('admin.inventory.suppliers.index') }}"
                    class="sb-link {{ request()->routeIs('admin.inventory.suppliers.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Suppliers</span></a></li>
                <li class="sb-item"><a href="{{ route('admin.inventory.items.index') }}"
                    class="sb-link {{ request()->routeIs('admin.inventory.items.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Item Master</span></a></li>
                <li class="sb-item"><a href="{{ route('admin.inventory.purchases.index') }}"
                    class="sb-link {{ request()->routeIs('admin.inventory.purchases.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Purchases (Stock)</span></a></li>
                <li class="sb-item"><a href="{{ route('admin.inventory.issues.index') }}"
                    class="sb-link {{ request()->routeIs('admin.inventory.issues.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Issue & Return</span></a></li>
                <li class="sb-item"><a href="{{ route('admin.inventory.maintenance.index') }}"
                    class="sb-link {{ request()->routeIs('admin.inventory.maintenance.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Maintenance</span></a></li>
              </ul>
            </div>
          </li>
        @endcan

"""

c = c.replace('<li class="sb-group-label"><span class="sb-text">System</span></li>', sidebar_html + '          <li class="sb-group-label"><span class="sb-text">System</span></li>')

with open(file_path, "w") as f:
    f.write(c)

