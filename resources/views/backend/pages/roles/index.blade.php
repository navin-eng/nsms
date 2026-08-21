@extends('backend.pages.layout.master')

@section('title', 'Manage Roles')

@section('backend-content')
<div class="container-fluid">
    <div class="mb-4">
        <h4 class="mb-1 fw-bold">Roles & Permissions</h4>
        <p class="text-muted mb-0">Create roles and manage their system permissions</p>
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

    <div class="row">
        <!-- Create Role Form -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Add New Role</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Accountant, Librarian" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Role</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="bi bi-list-check text-primary me-2"></i>Existing Roles</h5>
                </div>
                <div class="card-body p-0 mt-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Role Name</th>
                                    <th>Modules / Permissions</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Hardcoded Super Admin -->
                                <tr>
                                    <td class="ps-4 fw-semibold text-primary">
                                        Super Admin
                                        <span class="badge bg-primary ms-2">System</span>
                                    </td>
                                    <td><span class="badge bg-success">All Permissions</span></td>
                                    <td class="text-end pe-4 text-muted small">
                                        <em>Cannot be edited</em>
                                    </td>
                                </tr>
                                @foreach($roles as $role)
                                    <tr>
                                        <td class="ps-4 fw-semibold">
                                            {{ $role->name }}
                                        </td>
                                        <td>
                                            @if($role->permissions->count() > 0)
                                                <span class="badge bg-info">{{ $role->permissions->count() }} active modules</span>
                                            @else
                                                <span class="badge bg-secondary">No modules assigned</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary" title="Manage Permissions">
                                                <i class="bi bi-shield-lock me-1"></i> Manage
                                            </a>
                                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Delete Role">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($roles->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            No custom roles found. Add one using the form on the left.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
