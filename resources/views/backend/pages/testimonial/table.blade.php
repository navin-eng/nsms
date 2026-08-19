@extends('backend.pages.layout.master')
@push('b-title', 'Testimonials')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Testimonials</h1><p class="aph-sub">Manage student and alumni testimonials.</p></div>
  <a href="{{ route('testimonial.add') }}" class="btn-admin btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Testimonial</a>
</div>
<div class="admin-card">
  <div class="admin-card-header"><span class="card-title"><i class="bi bi-chat-quote-fill"></i> All Testimonials</span></div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th>#</th><th>Photo</th><th>Name</th><th>Role</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($testimonial as $data)
          <tr>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td><img src="{{ asset($data->image) }}" class="table-img-round" alt="{{ $data->name }}"></td>
            <td style="font-weight:600;">{{ $data->name }}</td>
            <td>{{ $data->role }}</td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('testimonial.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('testimonial.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:40px;color:#718096;">No testimonials added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
