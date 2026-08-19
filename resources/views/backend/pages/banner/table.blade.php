@extends('backend.pages.layout.master')
@push('b-title', 'Banners')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Banners</h1><p class="aph-sub">Manage homepage hero slider banners.</p></div>
  <a href="{{ route('banner.add') }}" class="btn-admin btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Banner</a>
</div>
<div class="admin-card">
  <div class="admin-card-header"><span class="card-title"><i class="bi bi-image-fill"></i> All Banners</span></div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th>#</th><th>Image</th><th>Title One</th><th>Title Two</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($banner as $data)
          <tr>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td><img src="{{ asset($data->image) }}" class="table-img" alt="Banner"></td>
            <td style="font-weight:600;">{{ $data->title1 }}</td>
            <td style="color:#718096;">{{ $data->title2 }}</td>
            <td><a href="{{ route('banner.status', $data->id) }}" class="badge-admin {{ $data->status==1 ? 'badge-active' : 'badge-inactive' }}">{{ $data->status==1 ? 'Active' : 'Inactive' }}</a></td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('banner.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('banner.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center;padding:40px;color:#718096;">No banners added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
