@extends('backend.pages.layout.master')
@push('b-title', 'Dashboard')
@push('styles')
<style>
  .qa-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
  }
  .qa-grid .quick-link {
    border-bottom: 1px solid var(--admin-border);
    border-radius: 6px;
    border: 1px solid var(--admin-border);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 15px 10px;
    height: 100%;
  }
  .qa-grid .quick-link i {
    margin: 0 0 8px 0;
    font-size: 24px;
  }
  .sortable-ghost {
    opacity: 0.4;
    background-color: var(--green-pale);
  }
</style>
@endpush

@section('backend-content')
@include('sweetalert::alert')

@php
  $courses   = App\Models\Course::count();
  $teachers  = App\Models\Teacher::count();
  $events    = App\Models\Event::count();
  $notices   = App\Models\Notice::count();
  $banners   = App\Models\Banner::count();
  $galleries = App\Models\Gallery::count();
@endphp

{{-- Page Header --}}
<div class="admin-page-header">
  <div>
    <h1 class="aph-title">Welcome back, {{ Auth::user()->name }} 👋</h1>
    <p class="aph-sub">Here's what's happening with your site today.</p>
  </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('course.table') }}" class="stat-card">
      <div class="stat-icon green"><i class="bi bi-mortarboard-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $courses }}</div>
        <div class="stat-label">Courses</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('teacher.table') }}" class="stat-card">
      <div class="stat-icon blue"><i class="bi bi-person-badge-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $teachers }}</div>
        <div class="stat-label">Faculty</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('event.table') }}" class="stat-card">
      <div class="stat-icon amber"><i class="bi bi-calendar2-event-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $events }}</div>
        <div class="stat-label">Events</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('notice.table') }}" class="stat-card">
      <div class="stat-icon rose"><i class="bi bi-bell-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $notices }}</div>
        <div class="stat-label">Notices</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('banner.table') }}" class="stat-card">
      <div class="stat-icon purple"><i class="bi bi-images"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $banners }}</div>
        <div class="stat-label">Banners</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('gallery.table') }}" class="stat-card">
      <div class="stat-icon teal"><i class="bi bi-grid-3x3-gap-fill"></i></div>
      <div class="stat-body">
        <div class="stat-num">{{ $galleries }}</div>
        <div class="stat-label">Gallery</div>
      </div>
    </a>
  </div>
</div>

{{-- Quick Actions + Recent Activity --}}
<div class="row g-3">

  {{-- Quick Actions --}}
  <div class="col-lg-4">
    <div class="admin-card h-100">
      <div class="admin-card-header d-flex justify-content-between align-items-center">
        <span class="card-title mb-0"><i class="bi bi-lightning-charge-fill"></i> Quick Actions</span>
        <div>
          <button class="btn btn-sm btn-outline-secondary qa-toggle" data-view="list"><i class="bi bi-list"></i></button>
          <button class="btn btn-sm btn-outline-secondary qa-toggle" data-view="grid"><i class="bi bi-grid"></i></button>
        </div>
      </div>
      <div class="admin-card-body">
        <div id="qa-container" class="qa-list">

        <a href="{{ route('course.add') }}" class="quick-link">
          <i class="bi bi-plus-circle-fill"></i> Add New Course
        </a>
        <a href="{{ route('teacher.add') }}" class="quick-link">
          <i class="bi bi-person-plus-fill"></i> Add Faculty Member
        </a>
        <a href="{{ route('notice.add') }}" class="quick-link">
          <i class="bi bi-megaphone-fill"></i> Post a Notice
        </a>
        <a href="{{ route('event.add') }}" class="quick-link">
          <i class="bi bi-calendar-plus-fill"></i> Create Event
        </a>
        <a href="{{ route('banner.add') }}" class="quick-link">
          <i class="bi bi-card-image"></i> Upload Banner
        </a>
        <a href="{{ route('gallery.table') }}" class="quick-link">
          <i class="bi bi-camera-fill"></i> Manage Gallery
        </a>
        <a href="{{ route('college_message.add') }}" class="quick-link">
          <i class="bi bi-chat-quote-fill"></i> Add Message
        </a>
        <a href="{{ route('page.add') }}" class="quick-link">
          <i class="bi bi-file-earmark-plus-fill"></i> New HTML Page
        </a>
        <a href="{{ route('home.sections.index') }}" class="quick-link">
          <i class="bi bi-layout-text-window-reverse"></i> Manage Homepage Layout
        </a>
        <a href="{{ route('campus.calendar.index') }}" class="quick-link">
          <i class="bi bi-calendar3"></i> Manage Campus Calendar
        </a>
        <a href="{{ route('site.settings.edit') }}" class="quick-link">
          <i class="bi bi-sliders2"></i> Manage Site Settings
        </a>

        </div>
      </div>
    </div>
  </div>

  {{-- Site sections overview --}}
  <div class="col-lg-8">
    <div class="admin-card h-100">
      <div class="admin-card-header">
        <span class="card-title"><i class="bi bi-layout-text-window-reverse"></i> Site Sections</span>
        <a href="{{ url('/') }}" target="_blank" class="btn-admin btn-admin-sm btn-admin-outline">
          <i class="bi bi-eye"></i> Preview
        </a>
      </div>
      <div class="admin-card-body p-0">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Section</th>
              <th>Records</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><i class="bi bi-mortarboard text-green me-2"></i>Courses</td>
              <td><span class="badge-admin badge-green">{{ $courses }}</span></td>
              <td><a href="{{ route('course.table') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-person-badge text-green me-2"></i>Faculty</td>
              <td><span class="badge-admin badge-green">{{ $teachers }}</span></td>
              <td><a href="{{ route('teacher.table') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-bell text-green me-2"></i>Notices</td>
              <td><span class="badge-admin badge-green">{{ $notices }}</span></td>
              <td><a href="{{ route('notice.table') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-calendar2-event text-green me-2"></i>Events</td>
              <td><span class="badge-admin badge-green">{{ $events }}</span></td>
              <td><a href="{{ route('event.table') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-chat-quote text-green me-2"></i>Testimonials</td>
              <td><span class="badge-admin badge-green">{{ App\Models\Testimonial::count() }}</span></td>
              <td><a href="{{ route('testimonial.table') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-grid-3x3-gap text-green me-2"></i>Gallery Photos</td>
              <td><span class="badge-admin badge-green">{{ $galleries }}</span></td>
              <td><a href="{{ route('gallery.table') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-person-lines-fill text-green me-2"></i>College Messages</td>
              <td><span class="badge-admin badge-green">{{ App\Models\CollegeMessage::count() }}</span></td>
              <td><a href="{{ route('college_message.table') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-layout-text-window-reverse text-green me-2"></i>Homepage Layout</td>
              <td><span class="badge-admin badge-green">{{ App\Models\HomeSection::count() }}</span></td>
              <td><a href="{{ route('home.sections.index') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-calendar3 text-green me-2"></i>Campus Calendar</td>
              <td><span class="badge-admin badge-green">{{ App\Models\CampusCalendarEntry::count() }}</span></td>
              <td><a href="{{ route('campus.calendar.index') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-sliders2 text-green me-2"></i>Site Settings</td>
              <td><span class="badge-admin badge-green">1</span></td>
              <td><a href="{{ route('site.settings.edit') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
            <tr>
              <td><i class="bi bi-file-earmark-code text-green me-2"></i>HTML Pages</td>
              <td><span class="badge-admin badge-green">{{ App\Models\Page::count() }}</span></td>
              <td><a href="{{ route('page.table') }}" class="btn-admin btn-admin-sm btn-admin-light">Manage</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('qa-container');
    const toggles = document.querySelectorAll('.qa-toggle');
    
    // Load view preference
    const savedView = localStorage.getItem('qaView') || 'list';
    setView(savedView);
    
    // Toggle view
    toggles.forEach(toggle => {
      toggle.addEventListener('click', () => {
        setView(toggle.dataset.view);
      });
    });
    
    function setView(view) {
      if (view === 'grid') {
        container.classList.remove('qa-list');
        container.classList.add('qa-grid');
      } else {
        container.classList.remove('qa-grid');
        container.classList.add('qa-list');
      }
      localStorage.setItem('qaView', view);
      
      toggles.forEach(btn => {
        if (btn.dataset.view === view) {
          btn.classList.add('btn-secondary', 'text-white');
          btn.classList.remove('btn-outline-secondary');
        } else {
          btn.classList.remove('btn-secondary', 'text-white');
          btn.classList.add('btn-outline-secondary');
        }
      });
    }
    
    // Sortable integration
    const sortable = new Sortable(container, {
      animation: 150,
      ghostClass: 'sortable-ghost',
      onEnd: function (evt) {
        saveOrder();
      }
    });
    
    // Give each child an ID based on href if not present
    Array.from(container.children).forEach((child, index) => {
      if (!child.id) child.id = 'qa-' + index;
    });
    
    // Load order
    const savedOrder = localStorage.getItem('qaOrder');
    if (savedOrder) {
      const orderArr = JSON.parse(savedOrder);
      sortable.sort(orderArr);
    }
    
    function saveOrder() {
      const order = sortable.toArray();
      localStorage.setItem('qaOrder', JSON.stringify(order));
    }
  });
</script>
@endpush
