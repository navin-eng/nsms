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
      <a href="{{ route('sms.exams.index') }}" class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-journal-check"></i></div>
        <div class="stat-body">
          <div class="stat-num">{{ $exams }}</div>
          <div class="stat-label">Total Exams</div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <a href="{{ route('sms.exams.index') }}" class="stat-card">
        <div class="stat-icon green"><i class="bi bi-clipboard-data-fill"></i></div>
        <div class="stat-body">
          <div class="stat-num">{{ $results }}</div>
          <div class="stat-label">Total Result Entries</div>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="stat-card" style="opacity: 0.6;">
        <div class="stat-icon amber"><i class="bi bi-person-video2"></i></div>
        <div class="stat-body">
          <div class="stat-num">--</div>
          <div class="stat-label">Total Students (Phase 2)</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
      <div class="stat-card" style="opacity: 0.6;">
        <div class="stat-icon purple"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-body">
          <div class="stat-num">--</div>
          <div class="stat-label">Fee Invoices (Phase 3)</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Quick Actions & Overviews --}}
  <div class="row g-3">

    <div class="col-lg-4">
      <div class="admin-card h-100">
        <div class="admin-card-header">
          <span class="card-title mb-0"><i class="bi bi-lightning-charge-fill"></i> Quick Actions</span>
        </div>
        <div class="admin-card-body">
          <div class="qa-list">
            <a href="{{ route('sms.exams.index') }}" class="quick-link">
              <i class="bi bi-plus-circle-fill"></i> Add New Exam
            </a>
            <a href="{{ route('sms.exams.index') }}" class="quick-link">
              <i class="bi bi-file-earmark-spreadsheet-fill"></i> Upload Result CSV
            </a>
            <a href="{{ route('site.settings.sms.edit') }}" class="quick-link">
              <i class="bi bi-building-gear"></i> School Info
            </a>
            <a href="#" class="quick-link"
              onclick="alert('Student Information System is coming in Phase 2'); return false;">
              <i class="bi bi-person-plus-fill text-muted"></i> Add New Student
            </a>
            <a href="#" class="quick-link" onclick="alert('Financial System is coming in Phase 3'); return false;">
              <i class="bi bi-receipt text-muted"></i> Generate Fee Invoice
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="admin-card h-100">
        <div class="admin-card-header">
          <span class="card-title"><i class="bi bi-journal-text"></i> Recent Exam Activity</span>
          <a href="{{ route('sms.exams.index') }}" class="btn-admin btn-admin-sm btn-admin-outline">
            View All
          </a>
        </div>
        <div class="admin-card-body p-0">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Exam Name</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse(App\Models\Exam::latest()->take(5)->get() as $exam)
                <tr>
                  <td class="fw-bold">{{ $exam->title }}</td>
                  <td>
                    @if($exam->status == 1)
                      <span class="badge-admin badge-active">Active</span>
                    @else
                      <span class="badge-admin badge-inactive">Inactive</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('exam.show', $exam->id) }}" class="btn-admin btn-admin-sm btn-admin-light">Manage
                      Results</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted py-4">No exams have been created yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
@endsection