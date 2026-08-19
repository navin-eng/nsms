@extends('backend.pages.layout.master')
@push('b-title', 'Team')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Our Team</h1><p class="aph-sub">Manage administrative, teaching, and non-teaching staff.</p></div>
  <a href="{{ route('teacher.add') }}" class="btn-admin btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Member</a>
</div>
<div class="admin-card">
  <div class="admin-card-header">
    <span class="card-title"><i class="bi bi-people-fill"></i> All Team Members</span>
  </div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th>#</th><th>Photo</th><th>Name</th><th>Role</th><th>Staff Type</th><th>Profile</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($teacher as $data)
          <tr>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td><img src="{{ asset($data->image) }}" class="table-img-round" alt="{{ $data->name }}"></td>
            <td style="font-weight:600;">{{ $data->name }}</td>
            <td>{{ $data->role }}</td>
            <td>
              @php
                $typeLabel = match($data->staff_type) {
                  'administrative' => 'Administrative',
                  'non_teaching'   => 'Non-Teaching',
                  default          => 'Teaching',
                };
                $typeClass = match($data->staff_type) {
                  'administrative' => 'badge-info',
                  'non_teaching'   => 'badge-warning',
                  default          => 'badge-green',
                };
              @endphp
              <span class="badge-admin {{ $typeClass }}">{{ $typeLabel }}</span>
            </td>
            <td>
              @if($data->facebook_link)
                <a href="{{ $data->facebook_link }}" target="_blank" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-facebook"></i> Profile</a>
              @else <span style="color:#718096;">—</span> @endif
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('teacher.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('teacher.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:40px;color:#718096;">No faculty added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
