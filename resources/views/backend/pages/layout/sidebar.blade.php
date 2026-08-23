<aside class="admin-sidebar" id="adminSidebar">

  {{-- Logo --}}
  <a href="{{ request()->is('admin/sms*') ? route('sms.dashboard') : url('admin/dashboard') }}" class="sb-logo">
    <img
      src="{{ \App\Models\SiteSetting::current()->site_logo ? asset('storage/' . \App\Models\SiteSetting::current()->site_logo) : asset('backend/images/logo.png') }}"
      alt="GPLC">
    <div class="sb-logo-text">
      <span class="sb-name">GPLC Admin</span>
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

      @if(request()->is('admin/dashboard*'))
        {{-- ========================================= --}}
        {{-- WEBSITE MANAGEMENT SYSTEM (CMS) SIDEBAR --}}
        {{-- ========================================= --}}

        {{-- Overview --}}
        <li class="sb-group-label"><span class="sb-text">Overview</span></li>
        <li class="sb-item">
          <a href="{{ url('admin/dashboard') }}" class="sb-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
            title="Dashboard">
            <i class="bi bi-grid-1x2-fill"></i><span class="sb-text">Dashboard</span>
          </a>
        </li>

        @can('manage_cms_content')
          {{-- Academic --}}
          <li class="sb-group-label"><span class="sb-text">Academic</span></li>
          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/dashboard/course*') ? 'active' : '' }}" data-bs-toggle="collapse"
              href="#sbCourse" aria-expanded="{{ request()->is('admin/dashboard/course*') ? 'true' : 'false' }}"
              title="Courses">
              <i class="bi bi-mortarboard-fill"></i>
              <span class="sb-text">Courses</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/dashboard/course*') ? 'show' : '' }}" id="sbCourse"
              data-bs-parent="#sb-menu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('course.add') }}"
                    class="sb-link {{ request()->routeIs('course.add') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Add Course</span></a></li>
                <li class="sb-item"><a href="{{ route('course.table') }}"
                    class="sb-link {{ request()->routeIs('course.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">All Courses</span></a></li>
              </ul>
            </div>
          </li>



          {{-- Website Content --}}
          <li class="sb-group-label"><span class="sb-text">Website Content</span></li>
          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/dashboard/banner*') ? 'active' : '' }}" data-bs-toggle="collapse"
              href="#sbBanner" aria-expanded="{{ request()->is('admin/dashboard/banner*') ? 'true' : 'false' }}"
              title="Banners">
              <i class="bi bi-image-fill"></i>
              <span class="sb-text">Banners</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/dashboard/banner*') ? 'show' : '' }}" id="sbBanner"
              data-bs-parent="#sb-menu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('banner.add') }}"
                    class="sb-link {{ request()->routeIs('banner.add') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Add Banner</span></a></li>
                <li class="sb-item"><a href="{{ route('banner.table') }}"
                    class="sb-link {{ request()->routeIs('banner.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">All Banners</span></a></li>
              </ul>
            </div>
          </li>

          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/dashboard/notice*') ? 'active' : '' }}" data-bs-toggle="collapse"
              href="#sbNotice" aria-expanded="{{ request()->is('admin/dashboard/notice*') ? 'true' : 'false' }}"
              title="Notices">
              <i class="bi bi-bell-fill"></i>
              <span class="sb-text">Notices</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/dashboard/notice*') ? 'show' : '' }}" id="sbNotice"
              data-bs-parent="#sb-menu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('notice.add') }}"
                    class="sb-link {{ request()->routeIs('notice.add') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Add Notice</span></a></li>
                <li class="sb-item"><a href="{{ route('notice.table') }}"
                    class="sb-link {{ request()->routeIs('notice.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">All Notices</span></a></li>
              </ul>
            </div>
          </li>

          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/dashboard/event*') ? 'active' : '' }}" data-bs-toggle="collapse"
              href="#sbEvent" aria-expanded="{{ request()->is('admin/dashboard/event*') ? 'true' : 'false' }}"
              title="Events">
              <i class="bi bi-calendar2-event-fill"></i>
              <span class="sb-text">Events</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/dashboard/event*') ? 'show' : '' }}" id="sbEvent"
              data-bs-parent="#sb-menu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('event.add') }}"
                    class="sb-link {{ request()->routeIs('event.add') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Add Event</span></a></li>
                <li class="sb-item"><a href="{{ route('event.table') }}"
                    class="sb-link {{ request()->routeIs('event.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">All Events</span></a></li>
              </ul>
            </div>
          </li>

          <li class="sb-item">
            <a href="{{ route('campus.calendar.index') }}"
              class="sb-link {{ request()->routeIs('campus.calendar.*') ? 'active' : '' }}" title="Campus Calendar">
              <i class="bi bi-calendar3"></i><span class="sb-text">Campus Calendar</span>
            </a>
          </li>

          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/dashboard/testimonial*') ? 'active' : '' }}" data-bs-toggle="collapse"
              href="#sbTesti" aria-expanded="{{ request()->is('admin/dashboard/testimonial*') ? 'true' : 'false' }}"
              title="Testimonials">
              <i class="bi bi-chat-quote-fill"></i>
              <span class="sb-text">Testimonials</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/dashboard/testimonial*') ? 'show' : '' }}" id="sbTesti"
              data-bs-parent="#sb-menu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('testimonial.add') }}"
                    class="sb-link {{ request()->routeIs('testimonial.add') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Add Testimonial</span></a></li>
                <li class="sb-item"><a href="{{ route('testimonial.table') }}"
                    class="sb-link {{ request()->routeIs('testimonial.table') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">All Testimonials</span></a></li>
              </ul>
            </div>
          </li>

          <li class="sb-item">
            <a href="{{ route('gallery.table') }}" class="sb-link {{ request()->routeIs('gallery.table') ? 'active' : '' }}"
              title="Gallery">
              <i class="bi bi-grid-3x3-gap-fill"></i><span class="sb-text">Gallery</span>
            </a>
          </li>

          {{-- Pages & Messages --}}
          <li class="sb-group-label"><span class="sb-text">Pages &amp; Messages</span></li>
          <li class="sb-item">
            <a href="{{ route('aboutus.add') }}" class="sb-link {{ request()->routeIs('aboutus.add') ? 'active' : '' }}"
              title="About Us">
              <i class="bi bi-building"></i><span class="sb-text">About Us</span>
            </a>
          </li>


          <li class="sb-item">
            <a href="{{ route('navbar.index') }}" class="sb-link {{ request()->routeIs('navbar.*') ? 'active' : '' }}">
              <i class="bi bi-menu-button-wide"></i><span class="sb-text">Navbar Builder</span>
            </a>
          </li>

          <li class="sb-item">
            <a href="{{ route('message.index') }}" class="sb-link {{ request()->routeIs('message.index') ? 'active' : '' }}"
              title="Visitor Messages">
              <i class="bi bi-envelope-open-fill"></i><span class="sb-text">Our Message</span>
            </a>
          </li>
          <li class="sb-item">
            <a href="{{ route('privacy.add') }}" class="sb-link {{ request()->routeIs('privacy.add') ? 'active' : '' }}"
              title="Privacy &amp; Policy">
              <i class="bi bi-shield-check"></i><span class="sb-text">Privacy &amp; Policy</span>
            </a>
          </li>

          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/dashboard/page*') ? 'active' : '' }}" data-bs-toggle="collapse"
              href="#sbPages" aria-expanded="{{ request()->is('admin/dashboard/page*') ? 'true' : 'false' }}"
              title="HTML Pages">
              <i class="bi bi-file-earmark-code-fill"></i>
              <span class="sb-text">HTML Pages</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/dashboard/page*') ? 'show' : '' }}" id="sbPages"
              data-bs-parent="#sb-menu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('page.add') }}"
                    class="sb-link {{ request()->routeIs('page.add') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Add Page</span></a></li>
                <li class="sb-item"><a href="{{ route('page.table') }}"
                    class="sb-link {{ request()->routeIs('page.table') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">All Pages</span></a></li>
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
            <div class="collapse {{ request()->is('admin/dashboard/college-message*') ? 'show' : '' }}" id="sbColMsg"
              data-bs-parent="#sb-menu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('college_message.add') }}"
                    class="sb-link {{ request()->routeIs('college_message.add') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Add Message</span></a></li>
                <li class="sb-item"><a href="{{ route('college_message.table') }}"
                    class="sb-link {{ request()->routeIs('college_message.table') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">All Messages</span></a></li>
              </ul>
            </div>
          </li>
        @endcan

        {{-- System --}}
        <li class="sb-group-label"><span class="sb-text">System</span></li>
        <li class="sb-item">
          <a href="{{ route('counter.table') }}" class="sb-link {{ request()->routeIs('counter.table') ? 'active' : '' }}"
            title="Stats Counter">
            <i class="bi bi-bar-chart-fill"></i><span class="sb-text">Stats Counter</span>
          </a>
        </li>
        @can('manage_website_settings')
          <li class="sb-item">
            <a href="{{ route('site.settings.cms.edit') }}"
              class="sb-link {{ request()->routeIs('site.settings.cms.*') ? 'active' : '' }}">
              <i class="bi bi-sliders2"></i><span class="sb-text">Website Settings</span>
            </a>
          </li>
        @endcan

      @elseif(request()->is('admin/sms*') || request()->is('admin/inventory*') || request()->is('admin/analytics*'))
        {{-- ========================================= --}}
        {{-- SCHOOL MANAGEMENT SYSTEM (SMS) SIDEBAR --}}
        {{-- ========================================= --}}

        {{-- Overview --}}
        <li class="sb-group-label"><span class="sb-text">Overview</span></li>
        <li class="sb-item">
          <a href="{{ route('sms.dashboard') }}" class="sb-link {{ request()->routeIs('sms.dashboard') ? 'active' : '' }}"
            title="Dashboard">
            <i class="bi bi-grid-1x2-fill"></i><span class="sb-text">Dashboard</span>
          </a>
        </li>



        @can('manage_admission')
          {{-- Admissions Management --}}
          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/sms/admissions*') ? 'active' : '' }}" data-bs-toggle="collapse"
              href="#sbAdmissions" aria-expanded="{{ request()->is('admin/sms/admissions*') ? 'true' : 'false' }}">
              <i class="bi bi-person-lines-fill"></i>
              <span class="sb-text">Admissions</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse sb-submenu {{ request()->is('admin/sms/admissions*') ? 'show' : '' }}" id="sbAdmissions"
              data-bs-parent="#sb-menu">
              <ul class="sb-list">
                <li class="sb-item"><a href="{{ route('sms.admissions.enquiries.index') }}"
                    class="sb-link {{ request()->routeIs('sms.admissions.enquiries.*') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Enquiries</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.admissions.applications.index') }}"
                    class="sb-link {{ request()->routeIs('sms.admissions.applications.*') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Applications</span></a></li>
              </ul>
            </div>
          </li>
        @endcan

        @can('manage_academic_structure')
          {{-- Academic Structure --}}
          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/sms/academic-years*') || request()->is('admin/sms/streams*') || request()->is('admin/sms/academic-classes*') || request()->is('admin/sms/sections*') || request()->is('admin/sms/subjects*') || request()->is('admin/sms/assignments*') ? 'active' : '' }}"
              data-bs-toggle="collapse" href="#sbAcademic"
              aria-expanded="{{ request()->is('admin/sms/academic-years*') || request()->is('admin/sms/streams*') || request()->is('admin/sms/academic-classes*') || request()->is('admin/sms/sections*') || request()->is('admin/sms/subjects*') || request()->is('admin/sms/assignments*') ? 'true' : 'false' }}">
              <i class="bi bi-book"></i>
              <span class="sb-text">Academic Structure</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div
              class="collapse {{ request()->is('admin/sms/academic-years*') || request()->is('admin/sms/streams*') || request()->is('admin/sms/academic-classes*') || request()->is('admin/sms/sections*') || request()->is('admin/sms/subjects*') ? 'show' : '' }}"
              id="sbAcademic">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('sms.academic-years.index') }}"
                    class="sb-link {{ request()->routeIs('sms.academic-years.*') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Academic Years</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.streams.index') }}"
                    class="sb-link {{ request()->routeIs('sms.streams.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Streams</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.academic-classes.index') }}"
                    class="sb-link {{ request()->routeIs('sms.academic-classes.*') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Classes</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.sections.index') }}"
                    class="sb-link {{ request()->routeIs('sms.sections.*') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Sections</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.subjects.index') }}"
                    class="sb-link {{ request()->routeIs('sms.subjects.*') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Subjects</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.assignments.index') }}"
                    class="sb-link {{ request()->routeIs('sms.assignments.*') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Teacher Assignment</span></a></li>
              </ul>
            </div>
          </li>
        @endcan

        @can('manage_staff')
          {{-- HR / Staff Management --}}
          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/sms/staff*') || request()->is('admin/sms/departments*') || request()->is('admin/sms/designations*') ? 'active' : '' }}"
              data-bs-toggle="collapse" href="#sbStaff"
              aria-expanded="{{ request()->is('admin/sms/staff*') || request()->is('admin/sms/departments*') || request()->is('admin/sms/designations*') ? 'true' : 'false' }}">
              <i class="bi bi-person-badge"></i>
              <span class="sb-text">Staff Management</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div
              class="collapse {{ request()->is('admin/sms/staff*') || request()->is('admin/sms/departments*') || request()->is('admin/sms/designations*') ? 'show' : '' }}"
              id="sbStaff">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('sms.staff.index') }}"
                    class="sb-link {{ request()->routeIs('sms.staff.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                      class="sb-text">Staff Directory</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.departments.index') }}"
                    class="sb-link {{ request()->routeIs('sms.departments.*') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Departments</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.designations.index') }}"
                    class="sb-link {{ request()->routeIs('sms.designations.*') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Designations</span></a></li>
              </ul>
            </div>
          </li>
        @endcan

        @can('manage_students')
          {{-- Student Management --}}
          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/sms/students*') ? 'active' : '' }}" data-bs-toggle="collapse"
              href="#sbStudents" aria-expanded="{{ request()->is('admin/sms/students*') ? 'true' : 'false' }}">
              <i class="bi bi-people"></i>
              <span class="sb-text">Student Management</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/sms/students*') ? 'show' : '' }}" id="sbStudents"
              data-bs-parent="#sb-menu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('sms.students.create') }}"
                    class="sb-link {{ request()->routeIs('sms.students.create') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Admit Student</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.students.index') }}"
                    class="sb-link {{ request()->routeIs('sms.students.index') && !request()->routeIs('sms.students.create') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Student Directory</span></a></li>
              </ul>
            </div>
          </li>
        @endcan

        @can('manage_attendance')
          {{-- Attendance Management --}}
          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/sms/attendance*') || request()->is('admin/sms/staff-attendance*') || request()->is('admin/sms/leave-requests*') ? 'active' : '' }}"
              data-bs-toggle="collapse" href="#sbAttendance"
              aria-expanded="{{ request()->is('admin/sms/attendance*') || request()->is('admin/sms/staff-attendance*') || request()->is('admin/sms/leave-requests*') ? 'true' : 'false' }}">
              <i class="bi bi-calendar-check"></i>
              <span class="sb-text">Attendance & Leaves</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div
              class="collapse {{ request()->is('admin/sms/attendance*') || request()->is('admin/sms/staff-attendance*') || request()->is('admin/sms/leave-requests*') ? 'show' : '' }}"
              id="sbAttendance">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('sms.attendance.index') }}"
                    class="sb-link {{ request()->routeIs('sms.attendance.index') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Student Attendance</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.attendance.report') }}"
                    class="sb-link {{ request()->routeIs('sms.attendance.report') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Student Reports</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.staff-attendance.index') }}"
                    class="sb-link {{ request()->routeIs('sms.staff-attendance.index') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Staff Attendance</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.staff-attendance.report') }}"
                    class="sb-link {{ request()->routeIs('sms.staff-attendance.report') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Staff Reports</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.leave-requests.index') }}"
                    class="sb-link {{ request()->routeIs('sms.leave-requests.index') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Leave Requests</span></a></li>
              </ul>
            </div>
          </li>
        @endcan

        @can('manage_academic_structure')
          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/sms/timetable*') ? 'active' : '' }}" data-bs-toggle="collapse"
              href="#sbTimetable" aria-expanded="{{ request()->is('admin/sms/timetable*') ? 'true' : 'false' }}"
              title="Timetable">
              <i class="bi bi-calendar3"></i>
              <span class="sb-text">Timetable</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ request()->is('admin/sms/timetable*') ? 'show' : '' }}" id="sbTimetable"
              data-bs-parent="#sb-menu">
              <ul class="sb-submenu">
                <li class="sb-item"><a href="{{ route('sms.timetable.index') }}"
                    class="sb-link {{ request()->routeIs('sms.timetable.index') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Manage Timetable</span></a></li>
                <li class="sb-item"><a href="{{ route('sms.timetable.teacher') }}"
                    class="sb-link {{ request()->routeIs('sms.timetable.teacher') ? 'active' : '' }}"><i
                      class="bi bi-dot"></i><span class="sb-text">Teacher View</span></a></li>
              </ul>
            </div>
        @endcan

          {{-- Finance & Fees --}}
        {{-- Finance & Fees --}}
        <li class="sb-item">
          <a class="sb-link {{ request()->is('admin/sms/finance*') || request()->is('admin/sms/accounting*') || request()->is('accounting*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#sbFinance"
            aria-expanded="{{ request()->is('admin/sms/finance*') || request()->is('admin/sms/accounting*') || request()->is('accounting*') ? 'true' : 'false' }}">
            <i class="bi bi-cash-coin"></i>
            <span class="sb-text">Finance &amp; Accounting</span>
            <i class="bi bi-chevron-right sb-arrow"></i>
          </a>
          <div
            class="collapse {{ request()->is('admin/sms/finance*') || request()->is('admin/sms/accounting*') || request()->is('accounting*') ? 'show' : '' }}"
            id="sbFinance">
            <ul class="sb-submenu">
              <li class="sb-item">
                <a href="{{ route('accounting.dashboard') }}"
                  class="sb-link {{ request()->routeIs('accounting.dashboard') ? 'active' : '' }}">
                  <i class="bi bi-speedometer2"></i><span class="sb-text">Finance Portal</span>
                </a>
              </li>

              <li class="sb-group-label"><span class="sb-text ps-3 text-muted" style="font-size: 0.75rem">Fees & Invoicing</span></li>
              <li class="sb-item">
                <a href="{{ route('accounting.fees.fee-types.index') }}"
                  class="sb-link {{ request()->routeIs('accounting.fees.fee-types.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Fee Types</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('accounting.fees.fee-structures.index') }}"
                  class="sb-link {{ request()->routeIs('accounting.fees.fee-structures.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Fee Structures</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('accounting.fees.invoices.generate') }}"
                  class="sb-link {{ request()->routeIs('accounting.fees.invoices.generate*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Generate Invoices</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('accounting.fees.invoices.index') }}"
                  class="sb-link {{ request()->routeIs('accounting.fees.invoices.index') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">All Invoices</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('accounting.fees.reports.outstanding') }}"
                  class="sb-link {{ request()->routeIs('accounting.fees.reports.outstanding') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Outstanding Report</span>
                </a>
              </li>

              <li class="sb-group-label mt-2"><span class="sb-text ps-3 text-muted" style="font-size: 0.75rem">Accounting</span></li>
              <li class="sb-item">
                <a href="{{ route('accounting.accounts.index') }}"
                  class="sb-link {{ request()->routeIs('accounting.accounts.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Chart of Accounts</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('accounting.daybook') }}"
                  class="sb-link {{ request()->routeIs('accounting.daybook') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Day Book</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('accounting.ledger') }}"
                  class="sb-link {{ request()->routeIs('accounting.ledger') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">General Ledger</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('accounting.balance-sheet') }}"
                  class="sb-link {{ request()->routeIs('accounting.balance-sheet') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Balance Sheet</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- Homework & Materials --}}
        <li class="sb-group-label"><span class="sb-text">Academics & Tasks</span></li>
        <li class="sb-item">
          <a class="sb-link {{ request()->routeIs('sms.homework.*') || request()->routeIs('sms.materials.*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#sbAcademicsTasks"
            aria-expanded="{{ request()->routeIs('sms.homework.*') || request()->routeIs('sms.materials.*') ? 'true' : 'false' }}"
            title="Homework & Materials">
            <i class="bi bi-book"></i>
            <span class="sb-text">Homework & Materials</span>
            <i class="bi bi-chevron-right sb-arrow"></i>
          </a>
          <div
            class="collapse {{ request()->routeIs('sms.homework.*') || request()->routeIs('sms.materials.*') ? 'show' : '' }}"
            id="sbAcademicsTasks" data-bs-parent="#sb-menu">
            <ul class="sb-submenu">
              <li class="sb-item">
                <a href="{{ route('sms.homework.index') }}"
                  class="sb-link {{ request()->routeIs('sms.homework.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Homework</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.materials.index') }}"
                  class="sb-link {{ request()->routeIs('sms.materials.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Study Materials</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- Hostel Management --}}
        <li class="sb-item">
          <a class="sb-link {{ request()->routeIs('sms.hostel.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#sbHostel" aria-expanded="{{ request()->routeIs('sms.hostel.*') ? 'true' : 'false' }}"
            title="Hostel Management">
            <i class="bi bi-building"></i>
            <span class="sb-text">Hostel Management</span>
            <i class="bi bi-chevron-right sb-arrow"></i>
          </a>
          <div class="collapse {{ request()->routeIs('sms.hostel.*') ? 'show' : '' }}" id="sbHostel"
            data-bs-parent="#sb-menu">
            <ul class="sb-submenu">
              <li class="sb-item">
                <a href="{{ route('sms.hostel.hostels.index') }}"
                  class="sb-link {{ request()->routeIs('sms.hostel.hostels.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Hostel Buildings</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.hostel.rooms.index') }}"
                  class="sb-link {{ request()->routeIs('sms.hostel.rooms.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Rooms & Beds</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.hostel.allocations.index') }}"
                  class="sb-link {{ request()->routeIs('sms.hostel.allocations.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Allocations</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.hostel.attendance.index') }}"
                  class="sb-link {{ request()->routeIs('sms.hostel.attendance.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Hostel Attendance</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- Library Management --}}
        <li class="sb-item">
          <a class="sb-link {{ request()->routeIs('sms.library.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#sbLibrary" aria-expanded="{{ request()->routeIs('sms.library.*') ? 'true' : 'false' }}"
            title="Library Management">
            <i class="bi bi-collection"></i>
            <span class="sb-text">Library Management</span>
            <i class="bi bi-chevron-right sb-arrow"></i>
          </a>
          <div class="collapse {{ request()->routeIs('sms.library.*') ? 'show' : '' }}" id="sbLibrary"
            data-bs-parent="#sb-menu">
            <ul class="sb-submenu">
              <li class="sb-item">
                <a href="{{ route('sms.library.issues.create') }}"
                  class="sb-link {{ request()->routeIs('sms.library.issues.create') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Issue / Return Book</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.library.issues.index') }}"
                  class="sb-link {{ request()->routeIs('sms.library.issues.index') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Issue History</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.library.books.index') }}"
                  class="sb-link {{ request()->routeIs('sms.library.books.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Books Inventory</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.library.categories.index') }}"
                  class="sb-link {{ request()->routeIs('sms.library.categories.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Categories</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.library.settings.index') }}"
                  class="sb-link {{ request()->routeIs('sms.library.settings.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Settings</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- Events & Activities --}}
        <li class="sb-item">
          <a class="sb-link {{ request()->routeIs('sms.events.*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#sbSmsEvents" aria-expanded="{{ request()->routeIs('sms.events.*') ? 'true' : 'false' }}"
            title="Events & Activities">
            <i class="bi bi-calendar-event-fill"></i>
            <span class="sb-text">Events & Activities</span>
            <i class="bi bi-chevron-right sb-arrow"></i>
          </a>
          <div class="collapse {{ request()->routeIs('sms.events.*') ? 'show' : '' }}" id="sbSmsEvents"
            data-bs-parent="#sb-menu">
            <ul class="sb-submenu">
              <li class="sb-item">
                <a href="{{ route('sms.events.index') }}"
                  class="sb-link {{ request()->routeIs('sms.events.index') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">All Events</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.events.create') }}"
                  class="sb-link {{ request()->routeIs('sms.events.create') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Create Event</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        @can('manage_exams')
          {{-- Examination --}}
          <li class="sb-group-label"><span class="sb-text">Examination</span></li>
          <li class="sb-item">
            <a class="sb-link {{ request()->routeIs('sms.exams.*') || request()->routeIs('sms.exam-schedules.*') || request()->routeIs('sms.exam-marks.*') || request()->routeIs('sms.exam-results.*') || request()->routeIs('sms.grading-rules.*') ? 'active' : '' }}"
              data-bs-toggle="collapse" href="#sbExam"
              aria-expanded="{{ request()->routeIs('sms.exams.*') || request()->routeIs('sms.exam-schedules.*') || request()->routeIs('sms.exam-marks.*') || request()->routeIs('sms.exam-results.*') || request()->routeIs('sms.grading-rules.*') ? 'true' : 'false' }}"
              title="Examinations">
              <i class="bi bi-journal-check"></i>
              <span class="sb-text">Examinations</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div
              class="collapse {{ request()->routeIs('sms.exams.*') || request()->routeIs('sms.exam-schedules.*') || request()->routeIs('sms.exam-marks.*') || request()->routeIs('sms.exam-results.*') || request()->routeIs('sms.grading-rules.*') ? 'show' : '' }}"
              id="sbExam" data-bs-parent="#sb-menu">
              <ul class="sb-submenu">

                <li class="sb-item">
                  <a href="{{ route('sms.exams.index') }}"
                    class="sb-link {{ request()->routeIs('sms.exams.*') ? 'active' : '' }}">
                    <i class="bi bi-dot"></i><span class="sb-text">Manage Exams</span>
                  </a>
                </li>
                <li class="sb-item">
                  <a href="{{ route('sms.exam-schedules.index') }}"
                    class="sb-link {{ request()->routeIs('sms.exam-schedules.*') ? 'active' : '' }}">
                    <i class="bi bi-dot"></i><span class="sb-text">Exam Schedules</span>
                  </a>
                </li>
                <li class="sb-item">
                  <a href="{{ route('sms.exam-marks.index') }}"
                    class="sb-link {{ request()->routeIs('sms.exam-marks.*') ? 'active' : '' }}">
                    <i class="bi bi-dot"></i><span class="sb-text">Marks Entry</span>
                  </a>
                </li>
                <li class="sb-item">
                  <a href="{{ route('sms.exam-results.index') }}"
                    class="sb-link {{ request()->routeIs('sms.exam-results.*') ? 'active' : '' }}">
                    <i class="bi bi-dot"></i><span class="sb-text">Exam Results</span>
                  </a>
                </li>
                <li class="sb-item">
                  <a href="{{ route('sms.transcripts.index') }}"
                    class="sb-link {{ request()->routeIs('sms.transcripts.*') ? 'active' : '' }}">
                    <i class="bi bi-dot"></i><span class="sb-text">Annual Transcripts</span>
                  </a>
                </li>
                <li class="sb-item">
                  <a href="{{ route('sms.grading-rules.index') }}"
                    class="sb-link {{ request()->routeIs('sms.grading-rules.*') ? 'active' : '' }}">
                    <i class="bi bi-dot"></i><span class="sb-text">Grading Rules</span>
                  </a>
                </li>
                <li class="sb-item">
                  <a href="{{ route('sms.admit-cards.index') }}"
                    class="sb-link {{ request()->routeIs('sms.admit-cards.*') ? 'active' : '' }}">
                    <i class="bi bi-dot"></i><span class="sb-text">Admit Cards</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endcan

        {{-- ID Cards & Certificates --}}
        <li class="sb-group-label"><span class="sb-text">ID Cards &amp; Certificates</span></li>
        <li class="sb-item">
          <a class="sb-link {{ request()->is('admin/sms/id-cards*') || request()->is('admin/sms/certificates*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#sbIdCert"
            aria-expanded="{{ request()->is('admin/sms/id-cards*') || request()->is('admin/sms/certificates*') ? 'true' : 'false' }}"
            title="ID Cards & Certificates">
            <i class="bi bi-person-vcard-fill"></i>
            <span class="sb-text">ID Cards &amp; Certificates</span>
            <i class="bi bi-chevron-right sb-arrow"></i>
          </a>
          <div
            class="collapse {{ request()->is('admin/sms/id-cards*') || request()->is('admin/sms/certificates*') ? 'show' : '' }}"
            id="sbIdCert" data-bs-parent="#sb-menu">
            <ul class="sb-submenu">
              <li class="sb-item">
                <a href="{{ route('sms.id-cards.students') }}"
                  class="sb-link {{ request()->routeIs('sms.id-cards.students') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Student ID Cards</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.id-cards.staff') }}"
                  class="sb-link {{ request()->routeIs('sms.id-cards.staff') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Staff ID Cards</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.id-cards.templates.index') }}"
                  class="sb-link {{ request()->routeIs('sms.id-cards.templates.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">ID Card Templates</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.certificates.create') }}"
                  class="sb-link {{ request()->routeIs('sms.certificates.create') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Issue Certificate</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.certificates.index') }}"
                  class="sb-link {{ request()->routeIs('sms.certificates.index') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Certificate History</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- Communication --}}
        <li class="sb-group-label"><span class="sb-text">Communication</span></li>
        <li class="sb-item">
          <a class="sb-link {{ request()->is('admin/sms/communications*') ? 'active' : '' }}" data-bs-toggle="collapse"
            href="#sbCommunication" aria-expanded="{{ request()->is('admin/sms/communications*') ? 'true' : 'false' }}"
            title="Communication Hub">
            <i class="bi bi-chat-left-dots"></i>
            <span class="sb-text">Communication Hub</span>
            <i class="bi bi-chevron-right sb-arrow"></i>
          </a>
          <div class="collapse {{ request()->is('admin/sms/communications*') ? 'show' : '' }}" id="sbCommunication"
            data-bs-parent="#sb-menu">
            <ul class="sb-submenu">
              <li class="sb-item">
                <a href="{{ route('admin.communications.compose') }}"
                  class="sb-link {{ request()->routeIs('admin.communications.compose') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Compose Message</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('admin.communications.logs') }}"
                  class="sb-link {{ request()->routeIs('admin.communications.logs') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Message Logs</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('admin.communications.templates') }}"
                  class="sb-link {{ request()->routeIs('admin.communications.templates') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Templates</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('sms.school-notices.index') }}"
                  class="sb-link {{ request()->routeIs('sms.school-notices.*') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">School Notices</span>
                </a>
              </li>
              <li class="sb-item">
                <a href="{{ route('admin.communications.settings') }}"
                  class="sb-link {{ request()->routeIs('admin.communications.settings') ? 'active' : '' }}">
                  <i class="bi bi-dot"></i><span class="sb-text">Gateway Settings</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="sb-group-label"><span class="sb-text">Inventory &amp; Assets</span></li>

        <li class="sb-item">
          <a href="#collapseInventory" class="sb-link {{ request()->routeIs('admin.inventory.*') ? '' : 'collapsed' }}"
            data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.inventory.*') ? 'true' : 'false' }}">
            <i class="bi bi-box-seam"></i><span class="sb-text">Inventory & Assets</span><i
              class="bi bi-chevron-down sb-caret"></i>
          </a>
          <div class="collapse {{ request()->routeIs('admin.inventory.*') ? 'show' : '' }}" id="collapseInventory"
            data-bs-parent="#sidebarMenu">
            <ul class="sb-submenu">
              <li class="sb-item"><a href="{{ route('admin.inventory.categories.index') }}"
                  class="sb-link {{ request()->routeIs('admin.inventory.categories.*') ? 'active' : '' }}"><i
                    class="bi bi-dot"></i><span class="sb-text">Categories</span></a></li>
              <li class="sb-item"><a href="{{ route('admin.inventory.stores.index') }}"
                  class="sb-link {{ request()->routeIs('admin.inventory.stores.*') ? 'active' : '' }}"><i
                    class="bi bi-dot"></i><span class="sb-text">Stores</span></a></li>
              <li class="sb-item"><a href="{{ route('admin.inventory.suppliers.index') }}"
                  class="sb-link {{ request()->routeIs('admin.inventory.suppliers.*') ? 'active' : '' }}"><i
                    class="bi bi-dot"></i><span class="sb-text">Suppliers</span></a></li>
              <li class="sb-item"><a href="{{ route('admin.inventory.items.index') }}"
                  class="sb-link {{ request()->routeIs('admin.inventory.items.*') ? 'active' : '' }}"><i
                    class="bi bi-dot"></i><span class="sb-text">Item Master</span></a></li>
              <li class="sb-item"><a href="{{ route('admin.inventory.purchases.index') }}"
                  class="sb-link {{ request()->routeIs('admin.inventory.purchases.*') ? 'active' : '' }}"><i
                    class="bi bi-dot"></i><span class="sb-text">Purchases (Stock)</span></a></li>
              <li class="sb-item"><a href="{{ route('admin.inventory.issues.index') }}"
                  class="sb-link {{ request()->routeIs('admin.inventory.issues.*') ? 'active' : '' }}"><i
                    class="bi bi-dot"></i><span class="sb-text">Issue & Return</span></a></li>
              <li class="sb-item"><a href="{{ route('admin.inventory.maintenance.index') }}"
                  class="sb-link {{ request()->routeIs('admin.inventory.maintenance.*') ? 'active' : '' }}"><i
                    class="bi bi-dot"></i><span class="sb-text">Maintenance</span></a></li>
            </ul>
          </div>
        </li>

        @can('manage_school_info')
          {{-- System --}}
          <li class="sb-group-label"><span class="sb-text">System</span></li>
          <li class="sb-item">
            <a href="{{ route('site.settings.sms.edit') }}"
              class="sb-link {{ request()->routeIs('site.settings.sms.*') ? 'active' : '' }}">
              <i class="bi bi-building-gear"></i><span class="sb-text">School Info</span>
            </a>
          </li>
        @endcan

        @if(auth()->user()->can('manage_users') || auth()->user()->can('manage_roles') || auth()->user()->can('manage_audit_logs'))
          {{-- Security & Access --}}
          <li class="sb-group-label"><span class="sb-text">Security &amp; Access</span></li>
          <li class="sb-item">
            <a class="sb-link {{ request()->is('admin/sms/users*') || request()->is('roles*') || request()->is('activity-logs*') ? 'active' : '' }}"
              data-bs-toggle="collapse" href="#sbSecurity"
              aria-expanded="{{ request()->is('admin/sms/users*') || request()->is('roles*') || request()->is('activity-logs*') ? 'true' : 'false' }}">
              <i class="bi bi-shield-lock"></i>
              <span class="sb-text">Security Management</span>
              <i class="bi bi-chevron-right sb-arrow"></i>
            </a>
            <div
              class="collapse {{ request()->is('admin/sms/users*') || request()->is('roles*') || request()->is('activity-logs*') ? 'show' : '' }}"
              id="sbSecurity">
              <ul class="sb-submenu">
                @can('manage_users')
                  <li class="sb-item"><a href="{{ route('sms.users.index') }}"
                      class="sb-link {{ request()->routeIs('sms.users.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                        class="sb-text">Users List</span></a></li>
                @endcan
                @can('manage_roles')
                  <li class="sb-item"><a href="{{ route('admin.roles.index') }}"
                      class="sb-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"><i class="bi bi-dot"></i><span
                        class="sb-text">Roles & Permissions</span></a></li>
                @endcan
                @can('manage_audit_logs')
                  <li class="sb-item"><a href="{{ route('admin.activity-logs.index') }}"
                      class="sb-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}"><i
                        class="bi bi-dot"></i><span class="sb-text">Audit Logs</span></a></li>
                @endcan
              </ul>
            </div>
          </li>
        @endif

      @endif


      <li class="sb-item">
        <a href="{{ route('admin.profile') }}" class="sb-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}"
          title="My Profile">
          <i class="bi bi-person-circle"></i><span class="sb-text">My Profile</span>
        </a>
      </li>

    </ul>
  </nav>

  {{-- Sidebar footer --}}
  <div class="sb-footer">
    @if(auth()->user()->a_type !== 'S')
      <a href="{{ route('admin.portal') }}" class="sb-link" title="Change Portal" style="margin-bottom: 5px;">
        <i class="bi bi-arrow-left-right"></i><span class="sb-text">Change Portal</span>
      </a>
    @endif
    <a href="{{ url('admin/dashboard/logout') }}" class="sb-link" title="Sign Out">
      <i class="bi bi-box-arrow-left"></i><span class="sb-text">Sign Out</span>
    </a>
  </div>

</aside>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('sidebarSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function (e) {
      const searchTerm = e.target.value.toLowerCase().trim();
      const sidebar = document.getElementById('adminSidebar');
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