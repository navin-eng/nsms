@extends('backend.pages.layout.master')
@push('b-title', 'Edit Message')
@section('backend-content')
    <div class="row">
        <div class="mb-3">
            <a href="{{ route('college_message.table') }}" class="btn btn-success">All Messages</a>
        </div>
        <h5 class="h4" style="text-align: center; margin:10px 0;">Edit Message: {{ $msg->name }}</h5>
    </div>
    <br>
    <form action="{{ route('college_message.update', $msg->id) }}" enctype="multipart/form-data" method="POST" style="width:100%;">
        @csrf
        <div class="row">
            <div class="col-md-7 col-12">
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $msg->name) }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                    <select name="designation" class="form-select" required>
                        <option value="">-- Select Designation --</option>
                        @foreach(['Principal','Chairman','Coordinator','Vice Principal','Director','Other'] as $d)
                            <option value="{{ $d }}" {{ old('designation', $msg->designation) == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control" rows="8">{{ old('message', $msg->message) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="order" value="{{ old('order', $msg->order) }}" class="form-control" min="0">
                </div>
            </div>
            <div class="col-md-5 col-12">
                <div class="mb-3">
                    <label class="form-label">Photo</label>
                    <input type="file" name="image" class="form-control" accept="image/*"
                        onchange="previewImage(this)">
                    <small class="text-muted">Leave blank to keep current photo.</small>
                </div>
                @if ($msg->image)
                    <div style="margin-top:10px;">
                        <p class="text-muted small">Current Photo:</p>
                        <img id="previewImg" src="{{ asset($msg->image) }}" alt="{{ $msg->name }}"
                            style="width:160px; height:180px; object-fit:cover; border-radius:8px; border:2px solid #dee2e6;">
                    </div>
                @else
                    <div id="imagePreview" style="display:none; margin-top:10px;">
                        <img id="previewImg" src="" alt="Preview"
                            style="width:160px; height:180px; object-fit:cover; border-radius:8px; border:2px solid #dee2e6;">
                    </div>
                @endif
            </div>
        </div>
        <div class="mb-3" style="margin:16px 0;">
            <button type="submit" class="btn btn-primary px-5">Update Message</button>
        </div>
    </form>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('previewImg').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
