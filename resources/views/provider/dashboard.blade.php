@extends('provider.layout.master')

@section('title', 'Platform Telemetry Dashboard')
@section('page-title', 'Platform Telemetry Dashboard')
@section('breadcrumb', 'Overview')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Central Platform Overview</h4>
        <p class="text-muted small mb-0">Cross-tenant monitoring, multi-school health &amp; God Mode controls.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('provider.schools.create') }}" class="btn btn-success fw-semibold">
            <i class="bi bi-plus-circle-fill me-1"></i> Onboard New School
        </a>
    </div>
</div>

<!-- Primary Stats Grid -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-god-metric">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Total Tenants</span>
                <div class="p-2 rounded-2 bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-buildings fs-5"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold mono">{{ number_format($totalSchools) }}</div>
            <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                <span class="text-success fw-semibold">{{ $activeSchools }} Active</span> · {{ $trialSchools }} Trial
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card-god-metric">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Platform Students</span>
                <div class="p-2 rounded-2 bg-success bg-opacity-10 text-success">
                    <i class="bi bi-mortarboard fs-5"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold mono text-success">{{ number_format($totalStudents) }}</div>
            <div class="text-muted small mt-1" style="font-size: 0.75rem;">Across all school tenants</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card-god-metric">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Faculty &amp; Staff</span>
                <div class="p-2 rounded-2 bg-info bg-opacity-10 text-info">
                    <i class="bi bi-people fs-5"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold mono text-info">{{ number_format($totalStaff) }}</div>
            <div class="text-muted small mt-1" style="font-size: 0.75rem;">Enrolled teachers &amp; staff</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card-god-metric">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Suspended / Inactive</span>
                <div class="p-2 rounded-2 bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-slash-circle fs-5"></i>
                </div>
            </div>
            <div class="fs-3 fw-bold mono text-danger">{{ $suspendedSchools + $disabledSchools }}</div>
            <div class="text-muted small mt-1" style="font-size: 0.75rem;">Requires provider attention</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Partner Schools Table -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-transparent py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Managed School Tenants</h5>
                <a href="{{ route('provider.schools.index') }}" class="btn btn-sm btn-outline-secondary">
                    View All Tenants &rarr;
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th>School Code</th>
                            <th>Institution Name</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th class="text-end">God Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSchools as $school)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border font-monospace py-1 px-2">
                                        {{ $school->school_code }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $school->name }}</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $school->contact_email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $school->package_name }}</span>
                                </td>
                                <td>
                                    @if($school->status === 'active')
                                        <span class="badge bg-success bg-opacity-15 text-success">Active</span>
                                    @elseif($school->status === 'trial')
                                        <span class="badge bg-info bg-opacity-15 text-info">Trial</span>
                                    @elseif($school->status === 'suspended')
                                        <span class="badge bg-warning bg-opacity-15 text-warning">Suspended</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-15 text-danger">{{ ucfirst($school->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('provider.schools.show', $school->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.78rem;">
                                        Manage &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No schools onboarded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Provider Audit Log Stream -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-transparent py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Platform Audit Trail</h5>
                <span class="badge bg-success bg-opacity-10 text-success mono">LIVE</span>
            </div>

            <div class="card-body p-3">
                <div class="d-flex flex-column gap-3">
                    @forelse($recentLogs as $log)
                        <div class="p-2 rounded-2 border bg-light bg-opacity-50">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-semibold text-primary mono" style="font-size: 0.75rem;">{{ $log->action }}</span>
                                <span class="text-muted" style="font-size: 0.7rem;">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-muted small mb-0" style="font-size: 0.78rem;">
                                {{ $log->description }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 small">No recent provider actions logged.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
