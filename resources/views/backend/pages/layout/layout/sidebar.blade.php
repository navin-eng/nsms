<aside class="admin-sidebar" id="adminSidebar">

  {{-- Logo --}}
  <a href="{{ url('admin/dashboard') }}" class="sb-logo">
    <img src="{{ \App\Models\SiteSetting::current()->site_logo ? asset('storage/' . \App\Models\SiteSetting::current()->site_logo) : asset('backend/images/logo.png') }}" alt="GPLC">
    <div class="sb-logo-text">
      <span class="sb-name">GPLC Admin</span>
      <span class="sb-sub">Green Peace Lincoln College</span>
    </div>
  </a>

  {{-- Navigation --}}
  <nav class="sb-nav" id="sbNav">
    <ul style="list-style:none;padding:0;margin:0;">

      {{-- Overview --}}
      <p class="sb-group-label">Overview</p>
      <li class="sb-item">
        <a href="{{ url('admin/dashboard') }}" class="sb-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
          <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
      </li>

      {{-- Academic --}}
      <p class="sb-group-label">Academic</p>

      <li class="sb-item">
        <button class="sb-link" data-bs-toggle="collapse" data-bs-target="#sbCourse" aria-expanded="{{ request()->is('admin/dashboard/course*') ? 'true' : 'false' }}">
          <i class="bi bi-mortarboard-fill"></i> Courses
          <i class="bi bi-chevron-right sb-arrow"></i>
        </button>
        <div class="collapse {{ request()->is('admin/dashboard/course*') ? 'show' : '' }}" id="sbCourse">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('course.add') }}" class="sb-link {{ request()->routeIs('course.add') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> Add Course
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('course.table') }}" class="sb-link {{ request()->routeIs('course.table') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> All Courses
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <button class="sb-link" data-bs-toggle="collapse" data-bs-target="#sbTeacher" aria-expanded="{{ request()->is('admin/dashboard/teacher*') ? 'true' : 'false' }}">
          <i class="bi bi-person-badge-fill"></i> Faculty
          <i class="bi bi-chevron-right sb-arrow"></i>
        </button>
        <div class="collapse {{ request()->is('admin/dashboard/teacher*') ? 'show' : '' }}" id="sbTeacher">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('teacher.add') }}" class="sb-link {{ request()->routeIs('teacher.add') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> Add Faculty
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('teacher.table') }}" class="sb-link {{ request()->routeIs('teacher.table') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> All Faculty
              </a>
            </li>
          </ul>
        </div>
      </li>

      {{-- Website Content --}}
      <p class="sb-group-label">Website Content</p>

      <li class="sb-item">
        <button class="sb-link" data-bs-toggle="collapse" data-bs-target="#sbBanner" aria-expanded="{{ request()->is('admin/dashboard/banner*') ? 'true' : 'false' }}">
          <i class="bi bi-images"></i> Banners
          <i class="bi bi-chevron-right sb-arrow"></i>
        </button>
        <div class="collapse {{ request()->is('admin/dashboard/banner*') ? 'show' : '' }}" id="sbBanner">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('banner.add') }}" class="sb-link {{ request()->routeIs('banner.add') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> Add Banner
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('banner.table') }}" class="sb-link {{ request()->routeIs('banner.table') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> All Banners
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <button class="sb-link" data-bs-toggle="collapse" data-bs-target="#sbNotice" aria-expanded="{{ request()->is('admin/dashboard/notice*') ? 'true' : 'false' }}">
          <i class="bi bi-bell-fill"></i> Notices
          <i class="bi bi-chevron-right sb-arrow"></i>
        </button>
        <div class="collapse {{ request()->is('admin/dashboard/notice*') ? 'show' : '' }}" id="sbNotice">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('notice.add') }}" class="sb-link {{ request()->routeIs('notice.add') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> Add Notice
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('notice.table') }}" class="sb-link {{ request()->routeIs('notice.table') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> All Notices
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <button class="sb-link" data-bs-toggle="collapse" data-bs-target="#sbEvent" aria-expanded="{{ request()->is('admin/dashboard/event*') ? 'true' : 'false' }}">
          <i class="bi bi-calendar2-event-fill"></i> Events
          <i class="bi bi-chevron-right sb-arrow"></i>
        </button>
        <div class="collapse {{ request()->is('admin/dashboard/event*') ? 'show' : '' }}" id="sbEvent">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('event.add') }}" class="sb-link {{ request()->routeIs('event.add') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> Add Event
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('event.table') }}" class="sb-link {{ request()->routeIs('event.table') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> All Events
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <button class="sb-link" data-bs-toggle="collapse" data-bs-target="#sbTesti" aria-expanded="{{ request()->is('admin/dashboard/testimonial*') ? 'true' : 'false' }}">
          <i class="bi bi-chat-quote-fill"></i> Testimonials
          <i class="bi bi-chevron-right sb-arrow"></i>
        </button>
        <div class="collapse {{ request()->is('admin/dashboard/testimonial*') ? 'show' : '' }}" id="sbTesti">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('testimonial.add') }}" class="sb-link {{ request()->routeIs('testimonial.add') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> Add Testimonial
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('testimonial.table') }}" class="sb-link {{ request()->routeIs('testimonial.table') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> All Testimonials
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <a href="{{ route('gallery.table') }}" class="sb-link {{ request()->routeIs('gallery.table') ? 'active' : '' }}">
          <i class="bi bi-grid-3x3-gap-fill"></i> Gallery
        </a>
      </li>

      {{-- Pages & Messages --}}
      <p class="sb-group-label">Pages & Messages</p>

      <li class="sb-item">
        <a href="{{ route('aboutus.add') }}" class="sb-link {{ request()->routeIs('aboutus.add') ? 'active' : '' }}">
          <i class="bi bi-building"></i> About Us
        </a>
      </li>
      <li class="sb-item">
        <a href="/admin/dashboard/message" class="sb-link {{ request()->is('admin/dashboard/message*') ? 'active' : '' }}">
          <i class="bi bi-envelope-open-fill"></i> Our Message
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('privacy.add') }}" class="sb-link {{ request()->routeIs('privacy.add') ? 'active' : '' }}">
          <i class="bi bi-shield-check"></i> Privacy & Policy
        </a>
      </li>

      <li class="sb-item">
        <button class="sb-link" data-bs-toggle="collapse" data-bs-target="#sbPages" aria-expanded="{{ request()->is('admin/dashboard/page*') ? 'true' : 'false' }}">
          <i class="bi bi-file-earmark-code-fill"></i> HTML Pages
          <i class="bi bi-chevron-right sb-arrow"></i>
        </button>
        <div class="collapse {{ request()->is('admin/dashboard/page*') ? 'show' : '' }}" id="sbPages">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('page.add') }}" class="sb-link {{ request()->routeIs('page.add') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> Add Page
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('page.table') }}" class="sb-link {{ request()->routeIs('page.table') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> All Pages
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <button class="sb-link" data-bs-toggle="collapse" data-bs-target="#sbColMsg" aria-expanded="{{ request()->is('admin/dashboard/college-message*') ? 'true' : 'false' }}">
          <i class="bi bi-person-lines-fill"></i> Messages
          <i class="bi bi-chevron-right sb-arrow"></i>
        </button>
        <div class="collapse {{ request()->is('admin/dashboard/college-message*') ? 'show' : '' }}" id="sbColMsg">
          <ul class="sb-submenu">
            <li class="sb-item">
              <a href="{{ route('college_message.add') }}" class="sb-link {{ request()->routeIs('college_message.add') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> Add Message
              </a>
            </li>
            <li class="sb-item">
              <a href="{{ route('college_message.table') }}" class="sb-link {{ request()->routeIs('college_message.table') ? 'active' : '' }}">
                <i class="bi bi-circle-fill"></i> All Messages
              </a>
            </li>
          </ul>
        </div>
      </li>

      {{-- System --}}
      <p class="sb-group-label">System</p>

      <li class="sb-item">
        <a href="{{ route('counter.table') }}" class="sb-link {{ request()->routeIs('counter.table') ? 'active' : '' }}">
          <i class="bi bi-bar-chart-fill"></i> Stats Counter
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('site.settings.edit') }}" class="sb-link {{ request()->routeIs('site.settings.*') ? 'active' : '' }}">
          <i class="bi bi-sliders2"></i><span class="sb-text">Site Settings</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('course.table') }}" class="sb-link {{ request()->routeIs('course.*') ? 'active' : '' }}">
          <i class="bi bi-mortarboard"></i><span class="sb-text">Courses</span>
        </a>
      </li>

      <li class="sb-item">
        <a href="{{ route('exam.index') }}" class="sb-link {{ request()->routeIs('exam.*') ? 'active' : '' }}">
          <i class="bi bi-journal-check"></i><span class="sb-text">Exam Results</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('navbar.index') }}" class="sb-link {{ request()->routeIs('navbar.*') ? 'active' : '' }}">
          <i class="bi bi-menu-button-wide"></i><span class="sb-text">Navbar Builder</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('editor.table') }}" class="sb-link {{ request()->routeIs('editor.table') ? 'active' : '' }}">
          <i class="bi bi-people-fill"></i> Editors / Users
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('admin.profile') }}" class="sb-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
          <i class="bi bi-person-circle"></i> My Profile
        </a>
      </li>

    </ul>
  </nav>

  {{-- Sidebar footer --}}
  <div class="sb-footer">
    <a href="{{ url('admin/dashboard/logout') }}" class="sb-link">
      <i class="bi bi-box-arrow-left"></i> Sign Out
    </a>
  </div>

</aside>
