@extends('backend.pages.layout.master')
@push('b-title', 'Staff Directory')

@section('backend-content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">Staff Directory</h3>
            <p class="text-muted mb-0">Manage all teaching and non-teaching staff.</p>
        </div>
        <div class="d-grid d-md-block">
            <a href="{{ route('sms.staff.create') }}" class="btn btn-primary w-100">
                <i class="bi bi-person-plus"></i> Add Staff
            </a>
        </div>
    </div>

    <style>
        .table-custom th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; color: #6c757d; border-bottom: 2px solid #f1f3f5; }
        .table-custom td { vertical-align: middle; border-bottom: 1px solid #f1f3f5; }
        .action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: all 0.2s; }
        .action-btn:hover { transform: translateY(-2px); }
    </style>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
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

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Staff Member</th>
                            <th class="d-none d-md-table-cell">ID</th>
                            <th>Department & Role</th>
                            <th class="d-none d-md-table-cell">Contact</th>
                            <th class="d-none d-lg-table-cell">Status</th>
                            <th class="text-end pe-4 text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffs as $staff)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $staff->photo ? asset('storage/' . $staff->photo) : asset('backend/images/avatar.png') }}" class="rounded-circle shadow-sm" style="width: 45px; height: 45px; object-fit: cover;" alt="Photo">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $staff->full_name }}</div>
                                            @if($staff->show_on_website)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1" style="font-size: 10px;">Listed on Website</span>
                                            @endif
                                            <div class="d-md-none mt-1 d-flex flex-column gap-1">
                                                <span class="badge bg-light text-secondary border text-start w-100" style="font-size: 0.65rem;"><i class="bi bi-hash"></i> {{ $staff->employee_id }}</span>
                                                <span class="badge bg-light text-secondary border text-start w-100" style="font-size: 0.65rem;"><i class="bi bi-telephone"></i> {{ $staff->phone ?? 'N/A' }}</span>
                                                @if($staff->status == 'Active')
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 text-start w-100" style="font-size: 0.65rem;">Active</span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 text-start w-100" style="font-size: 0.65rem;">{{ $staff->status }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold text-muted d-none d-md-table-cell">{{ $staff->employee_id }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $staff->designation->name ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $staff->department->name ?? 'N/A' }}</div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="small"><i class="bi bi-telephone text-muted me-1"></i> {{ $staff->phone ?? 'N/A' }}</div>
                                    <div class="small"><i class="bi bi-envelope text-muted me-1"></i> {{ $staff->email ?? 'N/A' }}</div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    @if($staff->status == 'Active')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Active</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">{{ $staff->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('sms.staff.show', $staff->id) }}" class="btn btn-light action-btn text-info border me-1" title="Profile"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('sms.staff.edit', $staff->id) }}" class="btn btn-light action-btn text-primary border me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('sms.staff.destroy', $staff->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this staff member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light action-btn text-danger border" title="Delete"><i class="bi bi-trash"></i></button>
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
