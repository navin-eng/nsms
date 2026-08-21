@extends('backend.pages.layout.master')

@section('title', 'Manage Modules & Permissions')

@section('backend-content')
<style>
    .module-card {
        transition: all 0.2s ease;
        border: 1px solid #eaeaea;
    }
    .module-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important;
        border-color: #d1e7dd;
    }
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }
</style>

<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Roles
        </a>
        <h4 class="mb-1 fw-bold">Manage Modules: <span class="text-primary">{{ $role->name }}</span></h4>
        <p class="text-muted">Control which system modules and features this role can access.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="name" value="{{ $role->name }}">

        <div class="row g-4 mb-4">
            @foreach($permissionsByModule as $moduleName => $modulePermissions)
                @php
                    $icon = 'bi-box';
                    $lowerName = strtolower($moduleName);
                    if(str_contains($lowerName, 'staff')) $icon = 'bi-person-badge';
                    if(str_contains($lowerName, 'student')) $icon = 'bi-mortarboard';
                    if(str_contains($lowerName, 'academic')) $icon = 'bi-building';
                    if(str_contains($lowerName, 'attendance')) $icon = 'bi-calendar-check';
                    if(str_contains($lowerName, 'exam')) $icon = 'bi-journal-check';
                    if(str_contains($lowerName, 'user') || str_contains($lowerName, 'role')) $icon = 'bi-shield-lock';
                    if(str_contains($lowerName, 'website') || str_contains($lowerName, 'cms')) $icon = 'bi-globe';
                    if(str_contains($lowerName, 'audit')) $icon = 'bi-clock-history';
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card module-card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi {{ $icon }} fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $moduleName }}</h6>
                                    <small class="text-muted">Select permissions</small>
                                </div>
                            </div>
                            
                            <hr class="text-muted opacity-25">

                            <div class="permissions-list mt-3">
                                @foreach($modulePermissions as $permission)
                                    @php
                                        $isAssigned = in_array($permission->name, old('permissions', $rolePermissions));
                                        // Pretty print the permission name
                                        $rawName = str_replace('manage_', '', $permission->name);
                                        $displayName = ucwords(str_replace('_', ' ', $rawName));
                                        if (strtolower($displayName) === strtolower($moduleName)) {
                                            $displayName = 'Manage All (Legacy)';
                                        }
                                    @endphp
                                    <div class="form-check form-switch mb-2 d-flex align-items-center">
                                        <input class="form-check-input shadow-none me-2 mt-0" type="checkbox" role="switch" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" {{ $isAssigned ? 'checked' : '' }}>
                                        <label class="form-check-label text-secondary mb-0" for="perm_{{ $permission->id }}">{{ $displayName }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body d-flex justify-content-between align-items-center p-4">
                <div>
                    <h6 class="fw-bold mb-1">Save Changes</h6>
                    <p class="text-muted mb-0 small">Applies module access settings to all users with the "{{ $role->name }}" role.</p>
                </div>
                <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold shadow-sm">
                    <i class="bi bi-check2-circle me-2"></i> Update Modules
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const switches = document.querySelectorAll('.form-check-input');
        switches.forEach(sw => {
            sw.addEventListener('change', function() {
                const card = this.closest('.module-card');
                if(this.checked) {
                    card.classList.add('border-primary');
                } else {
                    card.classList.remove('border-primary');
                }
            });
        });
    });
</script>
@endsection
