@extends('backend.pages.layout.master')
@push('b-title', 'Admission Applications')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Applications Dashboard</h3>
        <p class="text-muted mb-0">Manage incoming student applications.</p>
    </div>
    <a href="{{ route('sms.admissions.applications.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Application
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('sms.admissions.applications.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="Enrolled" {{ request('status') == 'Enrolled' ? 'selected' : '' }}>Enrolled</option>
                </select>
            </div>
            <div class="col-md-9 text-end">
                <a href="{{ route('sms.admissions.applications.index') }}" class="btn btn-sm btn-light border">Clear Filter</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Applicant Name</th>
                        <th>Class Applied</th>
                        <th>Academic Year</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td>{{ $app->application_date->format('d M, Y') }}</td>
                        <td class="fw-semibold">{{ $app->first_name }} {{ $app->last_name }}</td>
                        <td>{{ $app->academicClass->name ?? '-' }}</td>
                        <td>{{ $app->academicYear->name ?? '-' }}</td>
                        <td>{{ $app->contact_number }}</td>
                        <td>
                            @if($app->status == 'Pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($app->status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($app->status == 'Rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @elseif($app->status == 'Enrolled')
                                <span class="badge bg-primary">Enrolled</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('sms.admissions.applications.show', $app->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No applications found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
