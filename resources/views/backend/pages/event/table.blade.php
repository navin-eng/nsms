@extends('backend.pages.layout.master')
@push('b-title', 'Events')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Events</h1><p class="aph-sub">Manage college events and calendar entries.</p></div>
  <a href="{{ route('event.add') }}" class="btn-admin btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Event</a>
</div>
<div class="admin-card">
  <div class="admin-card-header"><span class="card-title"><i class="bi bi-calendar2-event-fill"></i> All Events</span></div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th>#</th><th>Image</th><th>Event Name</th><th>Category</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($event as $data)
          <tr>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td><img src="{{ asset($data->image) }}" class="table-img" alt="{{ $data->name }}"></td>
            <td style="font-weight:600;">{{ $data->name }}</td>
            <td><span class="badge-admin badge-info">{{ $data->event_type_label ?? ucfirst(str_replace('_',' ',$data->event_type ?? 'event')) }}</span></td>
            <td style="white-space:nowrap;font-size:12px;">{{ date('d M Y', strtotime($data->getRawOriginal('visit_date'))) }}</td>
            <td><a href="{{ route('event.status', $data->id) }}" class="badge-admin {{ $data->status==1 ? 'badge-active' : 'badge-inactive' }}">{{ $data->status==1 ? 'Active' : 'Inactive' }}</a></td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('event.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('event.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:40px;color:#718096;">No events added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
