@extends('provider.layout.master')

@section('title', 'Platform Overview')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Central Platform Telemetry</h4>
        <p class="text-secondary small mb-0">Cross-tenant monitoring, multi-school health &amp; God Mode controls.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('provider.schools.create') }}" class="btn btn-emerald">
            <i class="bi bi-plus-circle-fill me-1"></i> Onboard New School
        </a>
    </div>
</div>

<!-- Primary Stats Grid -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-god-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-secondary small">Total Tenants</span>
                <i class="bi bi-buildings text-primary fs-5"></i>
            </div>
            <div class="fs-3 fw-bold mono">{{ number_format($totalSchools) }}</div>
            <div class="text-secondary small mt-1" style="font-size: 0.75rem;">
                <span class="text-success fw-semibold">{{ $activeSchools }} Active</span> · {{ $trialSchools }} Trial
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-god-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-secondary small">Platform Students</span>
                <i class="bi bi-mortarboard text-success fs-5"></i>
            </div>
            <div class="fs-3 fw-bold mono">{{ number_format($totalStudents) }}</div>
            <div class="text-secondary small mt-1" style="font-size: 0.75rem;">Across all schools</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-god-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-secondary small">Platform Teachers &amp; Staff</span>
                <i class="bi bi-people text-info fs-5"></i>
            </div>
            <div class="fs-3 fw-bold mono">{{ number_format($totalStaff) }}</div>
            <div class="text-secondary small mt-1" style="font-size: 0.75rem;">Enrolled faculty</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-god-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="text-secondary small">Suspended / Disabled</span>
                <i class="bi bi-slash-circle text-danger fs-5"></i>
            </div>
            <div class="fs-3 fw-bold mono text-danger">{{ $suspendedSchools + $disabledSchools }}</div>
            <div class="text-secondary small mt-1" style="font-size: 0.75rem;">Requires attention</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Partner Schools Table -->
    <div class="col-lg-8">
        <div class="card-god p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Managed School Tenants</h5>
                <a href="{{ route('provider.schools.index') }}" class="text-secondary small text-decoration-none">
                    View All &rarr;
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0" style="background: transparent;">
                    <thead>
                        <tr class="text-secondary small border-secondary border-opacity-25">
                            <th>School Code</th>
                            <th>Institution Name</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th class="text-end">God Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSchools as $school)
                            <tr class="border-secondary border-opacity-10">
                                <td>
                                    <span class="badge bg-secondary bg-opacity-25 font-monospace text-white py-1 px-2">
                                        {{ $school->school_code }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $school->name }}</div>
                                    <small class="text-secondary" style="font-size: 0.75rem;">{{ $school->contact_email }}</small>
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
                                    <a href="{{ route('provider.schools.show', $school->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.78rem;">
                                        Manage &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-secondary">No schools onboarded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Provider Audit Log Stream -->
    <div class="col-lg-4">
        <div class="card-god p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Audit Trail</h5>
                <span class="badge bg-secondary bg-opacity-25 text-secondary mono">LIVE</span>
            </div>

            <div class="d-flex flex-column gap-3">
                @forelse($recentLogs as $log)
                    <div class="p-2 rounded-2" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle);">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="fw-semibold text-emerald-400 mono" style="color:var(--emerald-400);">{{ $log->action }}</span>
                            <span class="text-secondary" style="font-size: 0.7rem;">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-secondary small mb-0" style="font-size: 0.78rem;">
                            {{ $log->description }}
                        </p>
                    </div>
                @empty
                    <div class="text-center text-secondary py-4 small">No recent provider actions logged.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
