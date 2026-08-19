@extends('backend.pages.layout.master')
@push('b-title', 'Courses')
@section('backend-content')
<div class="admin-page-header">
  <div><h1 class="aph-title">Courses</h1><p class="aph-sub">Manage all academic programmes offered by GPLC.</p></div>
  <a href="{{ route('course.add') }}" class="btn-admin btn-admin-primary"><i class="bi bi-plus-lg"></i> Add Course</a>
</div>
<div class="admin-card">
  <div class="admin-card-header">
    <span class="card-title"><i class="bi bi-mortarboard-fill"></i> All Courses</span>
  </div>
  <div class="admin-card-body p-0">
    <div class="table-scroll">
      <table class="admin-table">
        <thead><tr><th>#</th><th>Course</th><th>Image</th><th>Duration</th><th>Semester</th><th>Requirement</th><th>Time</th><th>Gallery</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($course as $data)
          <tr>
            <td><span class="sr-badge">{{ $loop->iteration }}</span></td>
            <td style="font-weight:600;">{{ $data->name }}</td>
            <td><img src="{{ asset($data->image) }}" class="table-img" alt="{{ $data->name }}"></td>
            <td>{{ $data->duration }}</td>
            <td>{{ $data->semester }}</td>
            <td><span class="badge-admin badge-info">{{ $data->requirement }}</span></td>
            <td style="white-space:nowrap;font-size:12px;"><i class="bi bi-clock"></i> {{ $data->starting_time }} – {{ $data->closing_time }}</td>
            <td>
              <button type="button" class="btn-admin btn-admin-sm btn-admin-info" data-bs-toggle="modal" data-bs-target="#gallery{{ $data->id }}">
                <i class="bi bi-images"></i> View
              </button>
              <div class="modal fade" id="gallery{{ $data->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content" style="border-radius:12px;overflow:hidden;">
                    <div class="modal-header"><h5 class="modal-title fw-bold">{{ $data->name }} — Gallery</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                      <div class="gallery-grid-admin">
                        @foreach(json_decode($data->gallery ?? '[]') as $key => $img)
                        <div class="g-item">
                          <img src="{{ asset('backend/images/courses/'.$img) }}" alt="">
                          <a href="/admin/dashboard/course/delete/gallery/{{ $data->id }}/{{ $key }}" class="g-delete"><i class="bi bi-x"></i></a>
                        </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </td>
            <td>
              <a href="{{ route('course.status', $data->id) }}" class="badge-admin {{ $data->status==1 ? 'badge-active' : 'badge-inactive' }}">
                {{ $data->status==1 ? 'Active' : 'Inactive' }}
              </a>
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('course.edit', $data->id) }}" class="btn-admin btn-admin-sm btn-admin-info"><i class="bi bi-pencil"></i></a>
                <button class="btn-admin btn-admin-sm btn-admin-danger delete-wrap" data-route="{{ route('course.destroy', $data->id) }}"><i class="bi bi-trash3"></i></button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="10" style="text-align:center;padding:40px;color:#718096;">No courses added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
