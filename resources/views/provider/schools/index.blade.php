@extends('provider.layout.master')

@section('title', 'Managed School Tenants')
@section('page-title', 'Managed School Tenants')
@section('breadcrumb', 'Schools')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Managed School Tenants</h4>
        <p class="text-muted small mb-0">Full lifecycle control, subscription status, and module entitlements.</p>
    </div>
    <a href="{{ route('provider.schools.create') }}" class="btn btn-success fw-semibold">
        <i class="bi bi-plus-circle-fill me-1"></i> Onboard New School
    </a>
</div>

<!-- Filters & Search -->
<div class="card border-0 shadow-sm rounded-3 p-3 mb-4">
    <form method="GET" action="{{ route('provider.schools.index') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, code (SCH-XXXXXX), or email..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="package" class="form-select">
                <option value="">All Packages</option>
                <option value="Basic" {{ request('package') == 'Basic' ? 'selected' : '' }}>Basic</option>
                <option value="Professional" {{ request('package') == 'Professional' ? 'selected' : '' }}>Professional</option>
                <option value="Enterprise" {{ request('package') == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Filter</button>
            <a href="{{ route('provider.schools.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Schools Table -->
<div class="card border-0 shadow-sm rounded-3 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted text-uppercase">
                    <th>School Code</th>
                    <th>School / Institution</th>
                    <th>Enrolments</th>
                    <th>Package</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schools as $school)
                    <tr>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border font-monospace py-1 px-2">
                                {{ $school->school_code }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $school->name }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $school->contact_email }} · {{ $school->contact_phone }}</small>
                        </td>
                        <td>
                            <span class="small mono fw-semibold text-success">{{ number_format($school->students_count) }} students</span><br>
                            <small class="text-muted" style="font-size: 0.72rem;">{{ number_format($school->staff_count) }} staff</small>
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
                        <td>
                            <span class="text-muted small">{{ $school->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('provider.schools.show', $school->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.78rem;">
                                Manage Config &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No schools match your search query.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $schools->links() }}
    </div>
</div>
@endsection
