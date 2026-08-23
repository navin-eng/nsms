@extends('backend.pages.layout.master')
@push('b-title', 'SMS Dashboard')
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
  </style>
@endpush

@section('backend-content')
  @include('sweetalert::alert')

  @php
    $exams = App\Models\Exam::count();
    $results = App\Models\ExamMark::count();
  @endphp

  {{-- Page Header --}}
  <div class="admin-page-header">
    <div>
      <h1 class="aph-title">School Management System</h1>
      <p class="aph-sub">Manage academic and student operations.</p>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ number_format($totalStudents ?? 0) }}</div>
                <div class="stat-label">Active Students</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-body">
                <div class="stat-num">${{ number_format($monthlyRevenue ?? 0, 2) }}</div>
                <div class="stat-label">Revenue (This Month)</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-body">
                <div class="stat-num">${{ number_format($totalOutstanding ?? 0, 2) }}</div>
                <div class="stat-label">Outstanding Fees</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-calendar-check"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ $attendanceRate ?? 0 }}%</div>
                <div class="stat-label">Today's Attendance</div>
            </div>
        </div>
    </div>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Dashboard Widgets</h5>
    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editDashboardModal">
      <i class="bi bi-sliders"></i> Edit Dashboard
    </button>
  </div>

  @php
    $hiddenWidgets = $dashboardPreferences['hidden'] ?? [];
    $widgetOrder = $dashboardPreferences['order'] ?? [
      'revenue_trend', 'upcoming_birthdays', 'attendance_trend', 'student_distribution', 'quick_actions', 'detailed_reports', 'recent_activity'
    ];

    // Define widgets
    $widgets = [
      'revenue_trend' => [
        'class' => 'col-lg-8',
        'title' => 'Revenue Trend (Last 6 Months)',
        'icon' => 'bi-graph-up',
        'content' => '<canvas id="revenueTrendChart" height="100"></canvas>'
      ],
      'upcoming_birthdays' => [
        'class' => 'col-lg-4',
        'title' => 'Upcoming Birthdays',
        'icon' => 'bi-gift',
        'content' => view('backend.pages.sms.partials.birthdays', compact('upcomingBirthdays'))->render()
      ],
      'attendance_trend' => [
        'class' => 'col-lg-6',
        'title' => 'Attendance Trend (Last 7 Days)',
        'icon' => 'bi-calendar-check',
        'content' => '<canvas id="attendanceTrendChart" height="100"></canvas>'
      ],
      'student_distribution' => [
        'class' => 'col-lg-6',
        'title' => 'Student Distribution (Gender)',
        'icon' => 'bi-pie-chart',
        'content' => '<div class="d-flex justify-content-center"><canvas id="studentGenderChart" height="100" style="max-height: 250px;"></canvas></div>'
      ],
      'quick_actions' => [
        'class' => 'col-lg-4',
        'title' => 'Quick Actions',
        'icon' => 'bi-lightning-charge-fill',
        'content' => '
          <div class="qa-list">
            <a href="'.route('sms.exams.index').'" class="quick-link"><i class="bi bi-plus-circle-fill"></i> Add New Exam</a>
            <a href="'.route('site.settings.sms.edit').'" class="quick-link"><i class="bi bi-building-gear"></i> School Info</a>
          </div>'
      ],
      'detailed_reports' => [
        'class' => 'col-lg-4',
        'title' => 'Detailed Reports',
        'icon' => 'bi-box-arrow-in-right',
        'content' => '
          <div class="d-grid gap-2">
            <a href="'.route('admin.analytics.academic').'" class="btn btn-outline-primary text-start"><i class="bi bi-mortarboard me-2"></i> Academic Performance</a>
            <a href="'.route('admin.analytics.attendance').'" class="btn btn-outline-info text-start"><i class="bi bi-calendar-range me-2"></i> Attendance Trends</a>
            <a href="'.route('admin.analytics.financial').'" class="btn btn-outline-success text-start"><i class="bi bi-graph-up-arrow me-2"></i> Financial Health</a>
          </div>'
      ],
      'recent_activity' => [
        'class' => 'col-lg-4',
        'title' => 'Recent Exam Activity',
        'icon' => 'bi-journal-text',
        'content' => view('backend.pages.sms.partials.recent_exams')->render()
      ]
    ];
  @endphp

  <div class="row g-3 sortable-dashboard" id="dashboardGrid">
    @foreach($widgetOrder as $id)
      @if(isset($widgets[$id]))
        <div class="dashboard-widget {{ $widgets[$id]['class'] }}" data-widget-id="{{ $id }}" style="{{ in_array($id, $hiddenWidgets) ? 'display: none;' : '' }}">
          <div class="admin-card h-100 shadow-sm border-0">
            <div class="admin-card-header cursor-move" style="cursor: grab;">
              <span class="card-title mb-0"><i class="bi {{ $widgets[$id]['icon'] }}"></i> {{ $widgets[$id]['title'] }}</span>
              <i class="bi bi-grip-vertical text-muted float-end"></i>
            </div>
            <div class="admin-card-body">
              {!! $widgets[$id]['content'] !!}
            </div>
          </div>
        </div>
      @endif
    @endforeach
    
    {{-- Render any new widgets that aren't in the saved order yet --}}
    @foreach(array_diff(array_keys($widgets), $widgetOrder) as $id)
      <div class="dashboard-widget {{ $widgets[$id]['class'] }}" data-widget-id="{{ $id }}" style="{{ in_array($id, $hiddenWidgets) ? 'display: none;' : '' }}">
        <div class="admin-card h-100 shadow-sm border-0">
          <div class="admin-card-header cursor-move" style="cursor: grab;">
            <span class="card-title mb-0"><i class="bi {{ $widgets[$id]['icon'] }}"></i> {{ $widgets[$id]['title'] }}</span>
            <i class="bi bi-grip-vertical text-muted float-end"></i>
          </div>
          <div class="admin-card-body">
            {!! $widgets[$id]['content'] !!}
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Edit Dashboard Modal -->
  <div class="modal fade" id="editDashboardModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Customize Dashboard</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small">Check the widgets you want to display on your dashboard.</p>
          <form id="widgetVisibilityForm">
            @foreach($widgets as $id => $widget)
              <div class="form-check form-switch mb-2">
                <input class="form-check-input widget-toggle" type="checkbox" id="toggle_{{ $id }}" value="{{ $id }}" {{ !in_array($id, $hiddenWidgets) ? 'checked' : '' }}>
                <label class="form-check-label" for="toggle_{{ $id }}">{{ $widget['title'] }}</label>
              </div>
            @endforeach
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveDashboardPreferences()">Save Preferences</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Birthday Wish Modal -->
  <div class="modal fade" id="birthdayModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">Design Birthday Wish</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body bg-light">
          <!-- Template Controls -->
          <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-6">
                <label class="form-label small text-muted fw-bold">Select Template</label>
                <select class="form-select border-0 shadow-sm" id="templateSelector" onchange="changeTemplate(this.value)">
                    <option value="minimalist">Minimalist (Clean & Elegant)</option>
                    <option value="playful">Playful (Vibrant & Fun)</option>
                    <option value="professional">Professional (Corporate)</option>
                </select>
            </div>
            <div class="col-md-6 text-md-end">
                <label class="form-label small text-muted d-block fw-bold">Theme Color</label>
                <div class="btn-group shadow-sm">
                    <button type="button" class="btn btn-outline-primary active theme-btn" onclick="changeTheme(this, 'theme-blue')">Blue</button>
                    <button type="button" class="btn btn-outline-danger theme-btn" onclick="changeTheme(this, 'theme-red')">Red</button>
                    <button type="button" class="btn btn-outline-success theme-btn" onclick="changeTheme(this, 'theme-green')">Green</button>
                    <button type="button" class="btn btn-outline-dark theme-btn" onclick="changeTheme(this, 'theme-dark')">Dark</button>
                </div>
            </div>
          </div>

          <!-- Card Canvas -->
          <div id="birthdayCard" class="card mx-auto shadow-lg template-minimalist theme-blue position-relative" style="max-width: 500px; border-radius: 15px; overflow: hidden; color: white;">
             
             <!-- Decorative Elements based on template -->
             <div class="decoration dec-top"></div>
             <div class="decoration dec-bottom"></div>

             <div class="card-body text-center p-5 position-relative" style="z-index: 2;">
                <h2 class="bday-title mb-4 fw-bold">Happy Birthday!</h2>
                
                <div class="photo-wrapper mb-4">
                    <img id="bdayPhoto" src="" alt="Photo" class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                
                <h3 id="bdayName" class="bday-name fw-bold mb-1">Name</h3>
                <p id="bdayRole" class="bday-role text-white-50 mb-4 fw-semibold text-uppercase tracking-wide">Role</p>
                
                <div class="message-wrapper p-3 rounded">
                    <textarea id="bdayMessage" class="form-control bg-transparent text-white border-0 text-center fw-medium" rows="3" style="resize: none;" placeholder="Write a custom message..."></textarea>
                </div>
                
                <div class="bday-footer mt-4 pt-3 border-top border-white-50 d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Blooming Lotus English Secondary School</span>
                </div>
             </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <div class="d-flex justify-content-between w-100">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-info shadow-sm text-white" onclick="printCard()"><i class="bi bi-printer"></i> Print</button>
          <button type="button" class="btn btn-success shadow-sm" onclick="downloadCard(this)"><i class="bi bi-download"></i> Download Image</button>
        </div>
      </div>
    </div>
  </div>

  <form id="downloadImageForm" action="{{ route('sms.dashboard.download-image') }}" method="POST" style="display: none;">
      @csrf
      <input type="hidden" name="image" id="downloadImageData">
      <input type="hidden" name="filename" id="downloadImageFilename">
  </form>

@endsection

@push("scripts")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<style>
    /* Themes */
    .theme-blue { --bg-1: #0d6efd; --bg-2: #0dcaf0; --text-c: #ffffff; }
    .theme-red { --bg-1: #dc3545; --bg-2: #fd7e14; --text-c: #ffffff; }
    .theme-green { --bg-1: #198754; --bg-2: #20c997; --text-c: #ffffff; }
    .theme-dark { --bg-1: #212529; --bg-2: #495057; --text-c: #ffffff; }
    
    /* Base Card Setup */
    #birthdayCard {
        background-color: var(--bg-1) !important;
        background-image: linear-gradient(135deg, var(--bg-1), var(--bg-2)) !important; 
        color: var(--text-c) !important;
        border: none !important;
        transition: all 0.3s ease;
    }
    #birthdayCard * { color: inherit; }
    .decoration { position: absolute; border-radius: 50%; opacity: 0; transition: all 0.3s ease; }
    
    /* Template: Minimalist */
    .template-minimalist .bday-title { font-family: 'Helvetica Neue', Arial, sans-serif; font-weight: 300 !important; letter-spacing: 2px; }
    .template-minimalist .bday-name { font-family: 'Helvetica Neue', Arial, sans-serif; }
    .template-minimalist .message-wrapper { background: transparent !important; border: 1px solid rgba(255,255,255,0.3); }
    
    /* Template: Playful */
    .template-playful .bday-title { font-family: 'Comic Sans MS', cursive, sans-serif; font-size: 2.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); transform: rotate(-2deg); }
    .template-playful .photo-wrapper img { border: 5px dashed rgba(255,255,255,0.7) !important; padding: 5px; }
    .template-playful .dec-top { opacity: 0.2; width: 150px; height: 150px; background: white; top: -50px; left: -50px; }
    .template-playful .dec-bottom { opacity: 0.15; width: 200px; height: 200px; background: white; bottom: -80px; right: -80px; }
    .template-playful .message-wrapper { background: rgba(255,255,255,0.25); border-radius: 20px; box-shadow: inset 0 0 10px rgba(0,0,0,0.1); }
    
    /* Template: Professional */
    .template-professional { background: var(--bg-1) !important; border: 4px solid var(--bg-2); }
    .template-professional .bday-title { font-family: 'Georgia', serif; text-transform: uppercase; font-size: 1.8rem; letter-spacing: 3px; border-bottom: 2px solid rgba(255,255,255,0.3); padding-bottom: 15px; display: inline-block; }
    .template-professional .photo-wrapper img { border-radius: 10px !important; border: 3px solid white !important; }
    .template-professional .bday-footer { background: rgba(0,0,0,0.1); margin: 0 -3rem -3rem -3rem !important; padding: 1.5rem 3rem !important; }

    .dashboard-widget.sortable-ghost { opacity: 0.4; }
    .dashboard-widget.sortable-drag { cursor: grabbing !important; }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Init Sortable
        const grid = document.getElementById('dashboardGrid');
        if(grid) {
            new Sortable(grid, {
                animation: 150,
                handle: '.cursor-move',
                ghostClass: 'sortable-ghost',
                onEnd: function (evt) {
                    saveDashboardPreferences();
                },
            });
        }

        // Init Revenue Chart
        const revCtx = document.getElementById('revenueTrendChart');
        if(revCtx) {
            new Chart(revCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($revenueTrend['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Revenue ($)',
                        data: {!! json_encode($revenueTrend['data'] ?? []) !!},
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        borderWidth: 2, fill: true, tension: 0.3
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }

        // Init Attendance Chart
        const attCtx = document.getElementById('attendanceTrendChart');
        if(attCtx) {
            new Chart(attCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($attendanceTrend['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Attendance (%)',
                        data: {!! json_encode($attendanceTrend['data'] ?? []) !!},
                        backgroundColor: '#0dcaf0',
                        borderRadius: 4
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100 } } }
            });
        }

        // Init Gender Chart
        const genCtx = document.getElementById('studentGenderChart');
        if(genCtx) {
            new Chart(genCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($studentGenderChart['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($studentGenderChart['data'] ?? []) !!},
                        backgroundColor: ['#0d6efd', '#d63384', '#ffc107']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
            });
        }
    });

    function saveDashboardPreferences() {
        const order = Array.from(document.querySelectorAll('.dashboard-widget')).map(el => el.getAttribute('data-widget-id'));
        const hidden = Array.from(document.querySelectorAll('.widget-toggle:not(:checked)')).map(el => el.value);

        // Hide/show in DOM
        document.querySelectorAll('.dashboard-widget').forEach(el => {
            if (hidden.includes(el.getAttribute('data-widget-id'))) {
                el.style.display = 'none';
            } else {
                el.style.display = '';
            }
        });

        // Save via AJAX
        fetch("{{ route('sms.dashboard.preferences') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                preferences: { order: order, hidden: hidden }
            })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                // optionally show a toast
                const modal = bootstrap.Modal.getInstance(document.getElementById('editDashboardModal'));
                if(modal) modal.hide();
            }
        });
    }

    function openBirthdayModal(name, role, photo) {
        document.getElementById('bdayName').innerText = name;
        document.getElementById('bdayRole').innerText = role;
        document.getElementById('bdayPhoto').src = photo || '{{ asset("assets/backend/images/avatar.png") }}';
        document.getElementById('bdayMessage').value = `Wishing you a fantastic birthday, ${name}! Hope your day is filled with joy and success.`;
        
        new bootstrap.Modal(document.getElementById('birthdayModal')).show();
    }

    function changeTheme(btnElement, themeClass) {
        const card = document.getElementById('birthdayCard');
        card.classList.remove('theme-blue', 'theme-red', 'theme-green', 'theme-dark');
        card.classList.add(themeClass);
        
        // Update active button state
        document.querySelectorAll('.theme-btn').forEach(btn => btn.classList.remove('active'));
        if (btnElement) {
            btnElement.classList.add('active');
        }
    }

    function changeTemplate(templateClass) {
        const card = document.getElementById('birthdayCard');
        card.classList.remove('template-minimalist', 'template-playful', 'template-professional');
        card.classList.add('template-' + templateClass);
    }

    function downloadCard(btnElement) {
        const originalHtml = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="bi bi-hourglass-split"></i> Generating...';
        btnElement.classList.add('disabled');

        const card = document.getElementById('birthdayCard');
        html2canvas(card, { scale: 2, useCORS: true }).then(canvas => {
            const dataUrl = canvas.toDataURL("image/png");
            let safeName = document.getElementById('bdayName').innerText.trim().replace(/[^a-zA-Z0-9]/g, '_');
            if (!safeName) safeName = 'Card';
            
            document.getElementById('downloadImageData').value = dataUrl;
            document.getElementById('downloadImageFilename').value = safeName;
            document.getElementById('downloadImageForm').submit();

            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
                btnElement.classList.remove('disabled');
            }, 1000);
        });
    }

    function printCard() {
        const card = document.getElementById('birthdayCard');
        html2canvas(card, { scale: 2, useCORS: true }).then(canvas => {
            const win = window.open('', '_blank');
            win.document.write('<html><head><title>Print Birthday Wish</title></head><body style="margin:0; display:flex; justify-content:center; align-items:center; height:100vh;">');
            win.document.write('<img src="' + canvas.toDataURL() + '" style="max-width:100%; height:auto;" onload="window.print();window.close();" />');
            win.document.write('</body></html>');
            win.document.close();
        });
    }
</script>
@endpush