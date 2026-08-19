@extends('backend.pages.layout.master')
@push('b-title', 'Notices')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Notices</h1><p class="aph-sub">Manage notices shown in marquee or as popups.</p></div>
  <a href="{{ route('notice.add') }}" class="btn-admin btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Notice</a>
</div>
<div class="admin-card">
  <div class="admin-card-header"><span class="card-title"><i class="bi bi-bell-fill"></i> All Notices</span></div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th>#</th><th>Title</th><th>Image</th><th>Display As</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($notice as $data)
          <tr>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td style="font-weight:600;">{{ $data->title }}</td>
            <td><img src="{{ asset($data->image) }}" class="table-img" alt="Notice"></td>
            <td>
              <a href="{{ route('notice.status', $data->id) }}" class="badge-admin {{ $data->show_in == 'm' ? 'badge-green' : 'badge-info' }}">
                {{ $data->show_in == 'm' ? 'Marquee' : 'Popup' }}
              </a>
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('notice.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('notice.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:40px;color:#718096;">No notices added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
