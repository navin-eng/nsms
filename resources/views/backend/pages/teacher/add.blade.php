@extends('backend.pages.layout.master')
@push('b-title', 'Add Team Member')
@section('backend-content')

<div class="admin-page-header">
    <div>
        <h1 class="aph-title">Add Team Member</h1>
        <p class="aph-sub">Add a new member to the college team directory.</p>
    </div>
    <a href="{{ route('teacher.table') }}" class="btn-admin btn-admin-outline"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-person-badge-fill"></i> Member Details</span>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('teacher.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="admin-form-group">
                        <label class="admin-label">Full Name <span style="color:#e53e3e">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="admin-input" placeholder="e.g. Ram Prasad Sharma" required>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Designation / Role <span style="color:#e53e3e">*</span></label>
                        <input type="text" name="role" value="{{ old('role') }}" class="admin-input" placeholder="e.g. Assistant Professor" required>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Staff Category <span style="color:#e53e3e">*</span></label>
                        <select name="staff_type" class="admin-select" required>
                            <option value="">Select category</option>
                            <option value="administrative" {{ old('staff_type') === 'administrative' ? 'selected' : '' }}>Administrative Staff</option>
                            <option value="teaching" {{ old('staff_type') === 'teaching' ? 'selected' : '' }}>Teaching Staff</option>
                            <option value="non_teaching" {{ old('staff_type') === 'non_teaching' ? 'selected' : '' }}>Non-Teaching Staff</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Photo <span style="color:#e53e3e">*</span></label>
                        <input type="file" name="image" class="admin-input" accept="image/*" required>
                        <span class="admin-input-hint">JPG or PNG, recommended 400×400px</span>
                    </div>
                    <div class="admin-form-group mb-0">
                        <label class="admin-label">Facebook Profile Link</label>
                        <input type="text" name="facebook_link" value="{{ old('facebook_link') }}" class="admin-input" placeholder="https://facebook.com/... (optional)">
                    </div>
                    <hr style="border-color:var(--admin-border);margin:18px 0;">
                    <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-check-lg"></i> Save Member</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
