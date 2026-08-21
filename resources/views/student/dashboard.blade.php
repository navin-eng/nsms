@extends('student.layout.master')

@section('content')
<div class="text-center mb-5 mt-3">
    <h1 class="fw-bold text-primary" style="font-size: 2.5rem;">{{ $greeting ?? 'Hello' }}, {{ $student->first_name }}! 👋</h1>
    <h4 class="text-muted mt-2">You have <span class="badge bg-warning text-dark shadow-sm fs-5">⭐ {{ $student->points ?? 0 }} Points</span></h4>
</div>

<div class="row mb-4">
    <!-- Homework Alert -->
    @if(isset($dueHomeworks) && $dueHomeworks->count() > 0)
    <div class="col-12 mb-3">
        <div class="alert alert-danger shadow-sm border-0 rounded-4 d-flex flex-column flex-md-row align-items-center justify-content-between p-4">
            <div class="mb-3 mb-md-0 text-center text-md-start">
                <h4 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Action Required!</h4>
                <p class="mb-0 fs-5">You have <strong>{{ $dueHomeworks->count() }}</strong> homework assignment(s) due soon.</p>
            </div>
            <a href="{{ route('student.homework') }}" class="btn btn-danger btn-lg rounded-pill fw-bold shadow-sm">Do it now <i class="bi bi-arrow-right-circle-fill ms-1"></i></a>
        </div>
    </div>
    @endif
    
    <!-- Kudos and Attendance Progress -->
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 text-center">
                <h5 class="fw-bold mb-3"><i class="bi bi-battery-charging text-success me-2 fs-3"></i>Attendance Power</h5>
                <div class="progress rounded-pill bg-light shadow-inner" style="height: 30px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated {{ ($attendancePercentage ?? 0) < 50 ? 'bg-danger' : (($attendancePercentage ?? 0) < 80 ? 'bg-warning text-dark' : 'bg-success') }} fw-bold fs-5" role="progressbar" style="width: {{ $attendancePercentage ?? 0 }}%;" aria-valuenow="{{ $attendancePercentage ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $attendancePercentage ?? 0 }}%</div>
                </div>
                <p class="text-muted mt-2 mb-0">Keep it up to earn more stars!</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary bg-opacity-10 border border-primary border-2">
            <div class="card-body p-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-mailbox-flag me-2 fs-3"></i>Teacher Kudos</h5>
                @if(isset($kudos) && $kudos->count() > 0)
                    @foreach($kudos as $kudo)
                        <div class="d-flex align-items-center mb-2 bg-white p-2 rounded-3 shadow-sm border-start border-4 border-primary">
                            <div class="fs-2 me-3">{{ $kudo->icon }}</div>
                            <div>
                                <div class="fw-bold">{{ $kudo->message }}</div>
                                <small class="text-muted">From {{ optional($kudo->sender)->name ?? 'Teacher' }} • {{ $kudo->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted mt-3">
                        <i class="bi bi-envelope-paper fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                        No new messages. Keep doing great work!
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 justify-content-center">
    
    <!-- Profile -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('student.profile') }}" class="module-card shadow-sm p-4 text-center">
            <div class="module-icon text-info">
                <i class="bi bi-person-bounding-box"></i>
            </div>
            <div class="module-title">My Profile</div>
        </a>
    </div>

    <!-- Attendance -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('student.attendance') }}" class="module-card shadow-sm p-4 text-center">
            <div class="module-icon text-success">
                <i class="bi bi-calendar2-check-fill"></i>
            </div>
            <div class="module-title">Attendance</div>
        </a>
    </div>

    <!-- Homework -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('student.homework') }}" class="module-card shadow-sm p-4 text-center position-relative">
            @if($dueHomeworks->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger fs-6 p-2 shadow">
                    {{ $dueHomeworks->count() }}
                    <span class="visually-hidden">pending homeworks</span>
                </span>
            @endif
            <div class="module-icon text-primary">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div class="module-title">Homework</div>
        </a>
    </div>

    <!-- Study Materials -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('student.materials') }}" class="module-card shadow-sm p-4 text-center">
            <div class="module-icon text-warning">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="module-title">Study Stuff</div>
        </a>
    </div>

    <!-- Routine -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('student.routine') }}" class="module-card shadow-sm p-4 text-center">
            <div class="module-icon" style="color: #6f42c1;">
                <i class="bi bi-clock-fill"></i>
            </div>
            <div class="module-title">Time Table</div>
        </a>
    </div>

    <!-- Results -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('student.results') }}" class="module-card shadow-sm p-4 text-center">
            <div class="module-icon text-danger">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="module-title">My Grades</div>
        </a>
    </div>

    <!-- Notices -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('student.notices') }}" class="module-card shadow-sm p-4 text-center">
            <div class="module-icon" style="color: #fd7e14;">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <div class="module-title">Notices</div>
        </a>
    </div>

    <!-- Library -->
    <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ route('student.library') }}" class="module-card shadow-sm p-4 text-center">
            <div class="module-icon" style="color: #20c997;">
                <i class="bi bi-collection-fill"></i>
            </div>
            <div class="module-title">Library</div>
        </a>
    </div>

</div>
@endsection
