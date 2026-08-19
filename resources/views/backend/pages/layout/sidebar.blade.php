<aside class="admin-sidebar" id="adminSidebar">

  {{-- Logo --}}
  <a href="{{ request()->is('admin/sms*') ? route('sms.dashboard') : url('admin/dashboard') }}" class="sb-logo">
    <img src="{{ \App\Models\SiteSetting::current()->site_logo ? asset('storage/' . \App\Models\SiteSetting::current()->site_logo) : asset('backend/images/logo.png') }}" alt="GPLC">
    <div class="sb-logo-text">
      <span class="sb-name">GPLC Admin</span>
      <span class="sb-sub">Green Peace Lincoln College</span>
    </div>
  </a>

  {{-- Navigation --}}
  <nav class="sb-nav">
    <ul class="sb-menu">

      @if(request()->is('admin/dashboard*'))
      {{-- ========================================= --}}
      {{-- WEBSITE MANAGEMENT SYSTEM (CMS) SIDEBAR   --}}
      {{-- ========================================= --}}

      {{-- Overview --}}
      <li class="sb-group-label"><span class="sb-text">Overview</span></li>
      <li class="sb-item">
        <a href="{{ url('admin/dashboard') }}" class="sb-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" title="Dashboard">
          <i class="bi bi-grid-1x2-fill"></i><span class="sb-text">Dashboard</span>
        </a>
      </li>

      {{-- Academic --}}
      <li class="sb-group-label"><span class="sb-text">Academic</span></li>
      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/dashboard/course*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbCourse"
           aria-expanded="{{ request()->is('admin/dashboard/course*') ? 'true' : 'false' }}" title="Courses">
          <i class="bi bi-mortarboard-fill"></i>
          <span class="sb-text">Courses</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/dashboard/course*') ? 'show' : '' }}" id="sbCourse">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('course.add') }}"   class="sb-link {{ request()->routeIs('course.add')   ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Add Course</span></a></li>
            <li class="sb-item"><a href="{{ route('course.table') }}" class="sb-link {{ request()->routeIs('course.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">All Courses</span></a></li>
          </ul>
        </div>
      </li>



      {{-- Website Content --}}
      <li class="sb-group-label"><span class="sb-text">Website Content</span></li>
      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/dashboard/banner*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbBanner"
           aria-expanded="{{ request()->is('admin/dashboard/banner*') ? 'true' : 'false' }}" title="Banners">
          <i class="bi bi-image-fill"></i>
          <span class="sb-text">Banners</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/dashboard/banner*') ? 'show' : '' }}" id="sbBanner">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('banner.add') }}"   class="sb-link {{ request()->routeIs('banner.add')   ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Add Banner</span></a></li>
            <li class="sb-item"><a href="{{ route('banner.table') }}" class="sb-link {{ request()->routeIs('banner.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">All Banners</span></a></li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/dashboard/notice*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbNotice"
           aria-expanded="{{ request()->is('admin/dashboard/notice*') ? 'true' : 'false' }}" title="Notices">
          <i class="bi bi-bell-fill"></i>
          <span class="sb-text">Notices</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/dashboard/notice*') ? 'show' : '' }}" id="sbNotice">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('notice.add') }}"   class="sb-link {{ request()->routeIs('notice.add')   ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Add Notice</span></a></li>
            <li class="sb-item"><a href="{{ route('notice.table') }}" class="sb-link {{ request()->routeIs('notice.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">All Notices</span></a></li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/dashboard/event*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbEvent"
           aria-expanded="{{ request()->is('admin/dashboard/event*') ? 'true' : 'false' }}" title="Events">
          <i class="bi bi-calendar2-event-fill"></i>
          <span class="sb-text">Events</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/dashboard/event*') ? 'show' : '' }}" id="sbEvent">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('event.add') }}"   class="sb-link {{ request()->routeIs('event.add')   ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Add Event</span></a></li>
            <li class="sb-item"><a href="{{ route('event.table') }}" class="sb-link {{ request()->routeIs('event.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">All Events</span></a></li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <a href="{{ route('campus.calendar.index') }}" class="sb-link {{ request()->routeIs('campus.calendar.*') ? 'active' : '' }}" title="Campus Calendar">
          <i class="bi bi-calendar3"></i><span class="sb-text">Campus Calendar</span>
        </a>
      </li>

      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/dashboard/testimonial*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbTesti"
           aria-expanded="{{ request()->is('admin/dashboard/testimonial*') ? 'true' : 'false' }}" title="Testimonials">
          <i class="bi bi-chat-quote-fill"></i>
          <span class="sb-text">Testimonials</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/dashboard/testimonial*') ? 'show' : '' }}" id="sbTesti">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('testimonial.add') }}"   class="sb-link {{ request()->routeIs('testimonial.add')   ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Add Testimonial</span></a></li>
            <li class="sb-item"><a href="{{ route('testimonial.table') }}" class="sb-link {{ request()->routeIs('testimonial.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">All Testimonials</span></a></li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <a href="{{ route('gallery.table') }}" class="sb-link {{ request()->routeIs('gallery.table') ? 'active' : '' }}" title="Gallery">
          <i class="bi bi-grid-3x3-gap-fill"></i><span class="sb-text">Gallery</span>
        </a>
      </li>

      {{-- Pages & Messages --}}
      <li class="sb-group-label"><span class="sb-text">Pages &amp; Messages</span></li>
      <li class="sb-item">
        <a href="{{ route('aboutus.add') }}" class="sb-link {{ request()->routeIs('aboutus.add') ? 'active' : '' }}" title="About Us">
          <i class="bi bi-building"></i><span class="sb-text">About Us</span>
        </a>
      </li>


      <li class="sb-item">
        <a href="{{ route('navbar.index') }}" class="sb-link {{ request()->routeIs('navbar.*') ? 'active' : '' }}">
          <i class="bi bi-menu-button-wide"></i><span class="sb-text">Navbar Builder</span>
        </a>
      </li>

      <li class="sb-item">
        <a href="{{ route('message.index') }}" class="sb-link {{ request()->routeIs('message.index') ? 'active' : '' }}" title="Visitor Messages">
          <i class="bi bi-envelope-open-fill"></i><span class="sb-text">Our Message</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('privacy.add') }}" class="sb-link {{ request()->routeIs('privacy.add') ? 'active' : '' }}" title="Privacy &amp; Policy">
          <i class="bi bi-shield-check"></i><span class="sb-text">Privacy &amp; Policy</span>
        </a>
      </li>

      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/dashboard/page*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbPages"
           aria-expanded="{{ request()->is('admin/dashboard/page*') ? 'true' : 'false' }}" title="HTML Pages">
          <i class="bi bi-file-earmark-code-fill"></i>
          <span class="sb-text">HTML Pages</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/dashboard/page*') ? 'show' : '' }}" id="sbPages">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('page.add') }}"   class="sb-link {{ request()->routeIs('page.add')   ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Add Page</span></a></li>
            <li class="sb-item"><a href="{{ route('page.table') }}" class="sb-link {{ request()->routeIs('page.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">All Pages</span></a></li>
          </ul>
        </div>
      </li>

      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/dashboard/college-message*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbColMsg"
           aria-expanded="{{ request()->is('admin/dashboard/college-message*') ? 'true' : 'false' }}" title="Messages">
          <i class="bi bi-person-lines-fill"></i>
          <span class="sb-text">Messages</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/dashboard/college-message*') ? 'show' : '' }}" id="sbColMsg">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('college_message.add') }}"   class="sb-link {{ request()->routeIs('college_message.add')   ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Add Message</span></a></li>
            <li class="sb-item"><a href="{{ route('college_message.table') }}" class="sb-link {{ request()->routeIs('college_message.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">All Messages</span></a></li>
          </ul>
        </div>
      </li>

      {{-- System --}}
      <li class="sb-group-label"><span class="sb-text">System</span></li>
      <li class="sb-item">
        <a href="{{ route('counter.table') }}" class="sb-link {{ request()->routeIs('counter.table') ? 'active' : '' }}" title="Stats Counter">
          <i class="bi bi-bar-chart-fill"></i><span class="sb-text">Stats Counter</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('site.settings.cms.edit') }}" class="sb-link {{ request()->routeIs('site.settings.cms.*') ? 'active' : '' }}">
          <i class="bi bi-sliders2"></i><span class="sb-text">Website Settings</span>
        </a>
      </li>

      @elseif(request()->is('admin/sms*'))
      {{-- ========================================= --}}
      {{-- SCHOOL MANAGEMENT SYSTEM (SMS) SIDEBAR    --}}
      {{-- ========================================= --}}

      {{-- Overview --}}
      <li class="sb-group-label"><span class="sb-text">Overview</span></li>
      <li class="sb-item">
        <a href="{{ route('sms.dashboard') }}" class="sb-link {{ request()->routeIs('sms.dashboard') ? 'active' : '' }}" title="Dashboard">
          <i class="bi bi-grid-1x2-fill"></i><span class="sb-text">Dashboard</span>
        </a>
      </li>

      {{-- Academic Structure --}}
      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/sms/academic-years*') || request()->is('admin/sms/streams*') || request()->is('admin/sms/academic-classes*') || request()->is('admin/sms/sections*') || request()->is('admin/sms/subjects*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbAcademic"
           aria-expanded="{{ request()->is('admin/sms/academic-years*') || request()->is('admin/sms/streams*') || request()->is('admin/sms/academic-classes*') || request()->is('admin/sms/sections*') || request()->is('admin/sms/subjects*') ? 'true' : 'false' }}">
          <i class="bi bi-book"></i>
          <span class="sb-text">Academic Structure</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/sms/academic-years*') || request()->is('admin/sms/streams*') || request()->is('admin/sms/academic-classes*') || request()->is('admin/sms/sections*') || request()->is('admin/sms/subjects*') ? 'show' : '' }}" id="sbAcademic">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('sms.academic-years.index') }}" class="sb-link {{ request()->routeIs('sms.academic-years.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Academic Years</span></a></li>
            <li class="sb-item"><a href="{{ route('sms.streams.index') }}" class="sb-link {{ request()->routeIs('sms.streams.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Streams</span></a></li>
            <li class="sb-item"><a href="{{ route('sms.academic-classes.index') }}" class="sb-link {{ request()->routeIs('sms.academic-classes.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Classes</span></a></li>
            <li class="sb-item"><a href="{{ route('sms.sections.index') }}" class="sb-link {{ request()->routeIs('sms.sections.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Sections</span></a></li>
            <li class="sb-item"><a href="{{ route('sms.subjects.index') }}" class="sb-link {{ request()->routeIs('sms.subjects.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Subjects</span></a></li>
          </ul>
        </div>
      </li>

      {{-- HR / Staff Management --}}
      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/sms/staff*') || request()->is('admin/sms/departments*') || request()->is('admin/sms/designations*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbStaff"
           aria-expanded="{{ request()->is('admin/sms/staff*') || request()->is('admin/sms/departments*') || request()->is('admin/sms/designations*') ? 'true' : 'false' }}">
          <i class="bi bi-person-badge"></i>
          <span class="sb-text">Staff Management</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/sms/staff*') || request()->is('admin/sms/departments*') || request()->is('admin/sms/designations*') ? 'show' : '' }}" id="sbStaff">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('sms.staff.index') }}" class="sb-link {{ request()->routeIs('sms.staff.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Staff Directory</span></a></li>
            <li class="sb-item"><a href="{{ route('sms.departments.index') }}" class="sb-link {{ request()->routeIs('sms.departments.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Departments</span></a></li>
            <li class="sb-item"><a href="{{ route('sms.designations.index') }}" class="sb-link {{ request()->routeIs('sms.designations.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Designations</span></a></li>
          </ul>
        </div>
      </li>

      {{-- Student Management --}}
      <li class="sb-item">
        <a class="sb-link {{ request()->is('admin/sms/students*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#sbStudents"
           aria-expanded="{{ request()->is('admin/sms/students*') ? 'true' : 'false' }}">
          <i class="bi bi-people"></i>
          <span class="sb-text">Student Management</span>
          <i class="bi bi-chevron-right sb-arrow"></i>
        </a>
        <div class="collapse {{ request()->is('admin/sms/students*') ? 'show' : '' }}" id="sbStudents">
          <ul class="sb-submenu">
            <li class="sb-item"><a href="{{ route('sms.students.create') }}" class="sb-link {{ request()->routeIs('sms.students.create') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Admit Student</span></a></li>
            <li class="sb-item"><a href="{{ route('sms.students.index') }}" class="sb-link {{ request()->routeIs('sms.students.index') && !request()->routeIs('sms.students.create') ? 'active' : '' }}"><i class="bi bi-dot"></i><span class="sb-text">Student Directory</span></a></li>
          </ul>
        </div>
      </li>

      {{-- Examination --}}
      <li class="sb-group-label"><span class="sb-text">Examination</span></li>
      <li class="sb-item">
        <a href="{{ route('exam.index') }}" class="sb-link {{ request()->routeIs('exam.*') ? 'active' : '' }}">
          <i class="bi bi-journal-check"></i><span class="sb-text">Exam Results</span>
        </a>
      </li>

      {{-- System --}}
      <li class="sb-group-label"><span class="sb-text">System</span></li>
      <li class="sb-item">
        <a href="{{ route('site.settings.sms.edit') }}" class="sb-link {{ request()->routeIs('site.settings.sms.*') ? 'active' : '' }}">
          <i class="bi bi-building-gear"></i><span class="sb-text">School Info</span>
        </a>
      </li>

      @endif

      {{-- Common Links --}}
      <li class="sb-item">
        <a href="{{ route('editor.table') }}" class="sb-link {{ request()->routeIs('editor.table') ? 'active' : '' }}" title="Editors / Users">
          <i class="bi bi-people-fill"></i><span class="sb-text">Editors / Users</span>
        </a>
      </li>
      <li class="sb-item">
        <a href="{{ route('admin.profile') }}" class="sb-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}" title="My Profile">
          <i class="bi bi-person-circle"></i><span class="sb-text">My Profile</span>
        </a>
      </li>

    </ul>
  </nav>

  {{-- Sidebar footer --}}
  <div class="sb-footer">
    <a href="{{ route('admin.portal') }}" class="sb-link" title="Change Portal" style="margin-bottom: 5px;">
      <i class="bi bi-arrow-left-right"></i><span class="sb-text">Change Portal</span>
    </a>
    <a href="{{ url('admin/dashboard/logout') }}" class="sb-link" title="Sign Out">
      <i class="bi bi-box-arrow-left"></i><span class="sb-text">Sign Out</span>
    </a>
  </div>

</aside>
