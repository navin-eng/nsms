@extends('backend.pages.layout.master')
@push('b-title', 'Staff Directory')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Staff Directory</h3>
            <p class="text-muted mb-0">Manage all teaching and non-teaching staff.</p>
        </div>
        <a href="{{ route('sms.staff.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Add Staff
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('sms.staff.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="department_id" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="designation_id" class="form-select">
                        <option value="">All Designations</option>
                        @foreach($designations as $desig)
                            <option value="{{ $desig->id }}" {{ request('designation_id') == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Resigned" {{ request('status') == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                        <option value="Terminated" {{ request('status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('sms.staff.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-table">
                    <thead>
                        <tr>
                            <th>Staff Member</th>
                            <th>ID</th>
                            <th>Department & Role</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffs as $staff)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $staff->photo ? asset('storage/' . $staff->photo) : asset('backend/images/avatar.png') }}" class="table-img-round" alt="Photo">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $staff->full_name }}</div>
                                            @if($staff->show_on_website)
                                                <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 10px;">Listed on Website</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold text-muted">{{ $staff->employee_id }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $staff->designation->name ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $staff->department->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="small"><i class="bi bi-telephone text-muted me-1"></i> {{ $staff->phone ?? 'N/A' }}</div>
                                    <div class="small"><i class="bi bi-envelope text-muted me-1"></i> {{ $staff->email ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @if($staff->status == 'Active')
                                        <span class="badge badge-active">Active</span>
                                    @else
                                        <span class="badge badge-inactive">{{ $staff->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('sms.staff.show', $staff->id) }}" class="btn btn-sm btn-outline-info me-1"><i class="bi bi-eye"></i> Profile</a>
                                    <a href="{{ route('sms.staff.edit', $staff->id) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('sms.staff.destroy', $staff->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this staff member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-people fs-1 d-block mb-2 opacity-50"></i>
                                    No staff members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($staffs->hasPages())
                <div class="p-3 border-top">
                    {{ $staffs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
