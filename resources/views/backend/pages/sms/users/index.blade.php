@extends('backend.pages.layout.master')

@section('title', 'SMS User IDs')

@section('backend-content')
<div class="container-fluid">
    <div class="mb-4">
        <h4 class="mb-1 fw-bold">SMS User IDs</h4>
        <p class="text-muted mb-0">Generate and manage login accounts for your staff members.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Staff Member</th>
                            <th>Department & Title</th>
                            <th>Account Status</th>
                            <th>Roles</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staffs as $staff)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($staff->photo)
                                            <img src="{{ asset('storage/' . $staff->photo) }}" alt="Avatar" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px;">
                                                {{ substr($staff->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $staff->first_name }} {{ $staff->last_name }}</div>
                                            <div class="text-muted small">ID: {{ $staff->employee_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $staff->department->name ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $staff->designation->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @if($staff->user_id && $staff->user)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                            <i class="bi bi-check-circle me-1"></i> Active
                                        </span>
                                        <div class="text-muted small mt-1">{{ $staff->user->email }}</div>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">
                                            <i class="bi bi-slash-circle me-1"></i> No Account
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($staff->user_id && $staff->user)
                                        @forelse($staff->user->roles as $role)
                                            <span class="badge bg-info">{{ $role->name }}</span>
                                        @empty
                                            <span class="text-muted small">No roles</span>
                                        @endforelse
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($staff->user_id && $staff->user)
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#manageUserModal{{ $staff->user->id }}">
                                            <i class="bi bi-shield-lock me-1"></i> Manage User ID
                                        </button>
                                        <form action="{{ route('sms.users.destroy', $staff->user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this User ID? The staff record will remain intact.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Delete User ID">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                        <!-- Manage User ID Modal -->
                                        <div class="modal fade text-start" id="manageUserModal{{ $staff->user->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content border-0 shadow">
                                                    <form action="{{ route('sms.users.update', $staff->user->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header bg-light">
                                                            <h5 class="modal-title fw-bold">Manage User ID: {{ $staff->first_name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Email Address (Read Only)</label>
                                                                <input type="email" class="form-control bg-light" value="{{ $staff->user->email }}" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Update Roles</label>
                                                                <div class="row g-2">
                                                                    @foreach($roles as $role)
                                                                        <div class="col-6">
                                                                            <div class="form-check p-2 border rounded position-relative">
                                                                                <input class="form-check-input ms-1 me-2" type="checkbox" name="roles[]" value="{{ $role->name }}" id="m_role_{{ $role->id }}_{{ $staff->id }}" 
                                                                                    {{ $staff->user->hasRole($role->name) ? 'checked' : '' }}>
                                                                                <label class="form-check-label stretched-link" for="m_role_{{ $role->id }}_{{ $staff->id }}">
                                                                                    {{ $role->name }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 border-top pt-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="reset_password" value="1" id="reset_{{ $staff->id }}">
                                                                    <label class="form-check-label fw-bold text-danger" for="reset_{{ $staff->id }}">
                                                                        Reset Password
                                                                    </label>
                                                                    <div class="text-muted small">Checking this will generate a new random password and email it to the user.</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update User ID</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @else
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#generateUserModal{{ $staff->id }}">
                                            <i class="bi bi-person-plus me-1"></i> Generate User ID
                                        </button>

                                        <!-- Generate User ID Modal -->
                                        <div class="modal fade text-start" id="generateUserModal{{ $staff->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content border-0 shadow">
                                                    <form action="{{ route('sms.users.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                                                        <div class="modal-header bg-light">
                                                            <h5 class="modal-title fw-bold">Generate User ID</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="mb-3">A secure login account will be created for <strong>{{ $staff->first_name }} {{ $staff->last_name }}</strong>.</p>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                                                <input type="email" name="email" class="form-control" value="{{ $staff->email }}" required placeholder="staff@school.com">
                                                                <div class="text-muted small mt-1">Credentials will be sent to this email.</div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Assign Roles</label>
                                                                <div class="row g-2">
                                                                    @foreach($roles as $role)
                                                                        <div class="col-6">
                                                                            <div class="form-check p-2 border rounded bg-light position-relative">
                                                                                <input class="form-check-input ms-1 me-2" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}_{{ $staff->id }}">
                                                                                <label class="form-check-label stretched-link" for="role_{{ $role->id }}_{{ $staff->id }}">
                                                                                    {{ $role->name }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                    @if($roles->isEmpty())
                                                                        <div class="col-12 text-muted small">No roles available. Add roles in Security Management first.</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="alert alert-info py-2 mb-0 border-0">
                                                                <i class="bi bi-info-circle me-2"></i> A random password will be auto-generated.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Generate & Email ID</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($staffs->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No staff members found. Please add staff in the Staff Directory first.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $staffs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
