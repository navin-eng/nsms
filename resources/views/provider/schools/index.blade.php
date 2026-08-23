@extends('provider.layout.master')

@section('title', 'Managed School Tenants')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Managed School Tenants</h4>
        <p class="text-secondary small mb-0">Full lifecycle control, subscription status, and module entitlements.</p>
    </div>
    <a href="{{ route('provider.schools.create') }}" class="btn btn-emerald">
        <i class="bi bi-plus-circle-fill me-1"></i> Onboard New School
    </a>
</div>

<!-- Filters & Search -->
<div class="card-god p-3 mb-4">
    <form method="GET" action="{{ route('provider.schools.index') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 border-secondary border-opacity-25 text-secondary">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control bg-transparent border-start-0 border-secondary border-opacity-25 text-white" placeholder="Search by name, code (SCH-XXXXXX), or email..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select bg-transparent border-secondary border-opacity-25 text-white">
                <option value="" class="bg-dark">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }} class="bg-dark">Active</option>
                <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }} class="bg-dark">Trial</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }} class="bg-dark">Suspended</option>
                <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }} class="bg-dark">Disabled</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="package" class="form-select bg-transparent border-secondary border-opacity-25 text-white">
                <option value="" class="bg-dark">All Packages</option>
                <option value="Basic" {{ request('package') == 'Basic' ? 'selected' : '' }} class="bg-dark">Basic</option>
                <option value="Professional" {{ request('package') == 'Professional' ? 'selected' : '' }} class="bg-dark">Professional</option>
                <option value="Enterprise" {{ request('package') == 'Enterprise' ? 'selected' : '' }} class="bg-dark">Enterprise</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            <a href="{{ route('provider.schools.index') }}" class="btn btn-link text-secondary text-decoration-none">Reset</a>
        </div>
    </form>
</div>

<!-- Schools Table -->
<div class="card-god p-4">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0" style="background: transparent;">
            <thead>
                <tr class="text-secondary small border-secondary border-opacity-25">
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
                    <tr class="border-secondary border-opacity-10">
                        <td>
                            <span class="badge bg-secondary bg-opacity-25 font-monospace text-white py-1 px-2">
                                {{ $school->school_code }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $school->name }}</div>
                            <small class="text-secondary" style="font-size: 0.75rem;">{{ $school->contact_email }} · {{ $school->contact_phone }}</small>
                        </td>
                        <td>
                            <span class="small mono">{{ number_format($school->students_count) }} students</span><br>
                            <small class="text-secondary" style="font-size: 0.72rem;">{{ number_format($school->staff_count) }} staff</small>
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
                            <span class="text-secondary small">{{ $school->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('provider.schools.show', $school->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.78rem;">
                                Manage Config &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-secondary">No schools match your search query.</td>
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
