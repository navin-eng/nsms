@extends('backend.pages.layout.master')
@push('b-title', 'Gallery')
@section('backend-content')

<div class="admin-page-header">
  <div><h1 class="aph-title">Photo Gallery</h1><p class="aph-sub">Upload and manage gallery photos shown on the website.</p></div>
  <button type="button" class="btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
    <i class="bi bi-cloud-upload"></i> Upload Photos
  </button>
</div>

{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid var(--admin-border);">
        <h5 class="modal-title" style="font-weight:700;"><i class="bi bi-cloud-upload text-green me-2"></i>Upload Photos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <label class="admin-label">Select Images (multiple allowed)</label>
          <input type="file" name="gallery[]" multiple class="admin-input" accept="image/*">
          <p class="admin-input-hint">Supported: JPG, PNG, WebP. Max 5MB each.</p>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--admin-border);">
          <button type="button" class="btn-admin btn-admin-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-admin btn-admin-primary"><i class="bi bi-upload"></i> Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="admin-card">
  <div class="admin-card-header">
    <span class="card-title"><i class="bi bi-grid-3x3-gap-fill"></i> All Photos</span>
    @php $gallery = App\Models\Gallery::all(); $total = 0; foreach($gallery as $g){ $total += count(json_decode($g->gallery ?? '[]')); } @endphp
    <span class="badge-admin badge-green">{{ $total }} photos</span>
  </div>
  <div class="admin-card-body">
    <div class="LincolnRow">
      @foreach($gallery as $data)
        @foreach(json_decode($data->gallery ?? '[]') as $key => $img)
        <div>
          <a class="deleteImageBTN" href="{{ route('gallery.delete', [$data->id, $key]) }}">
            <i class="bi bi-x"></i>
          </a>
          <img src="{{ asset('backend/images/gallery/'.$img) }}" alt="Gallery Photo">
        </div>
        @endforeach
      @endforeach
    </div>
    @if($total === 0)
    <div style="text-align:center;padding:60px;color:#718096;">
      <i class="bi bi-images" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px;"></i>
      No photos uploaded yet. Click "Upload Photos" to add some.
    </div>
    @endif
  </div>
</div>
@endsection
