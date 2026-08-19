@extends('backend.pages.layout.master')
@push('b-title', 'Add Message')
@section('backend-content')
    <div class="row">
        <div class="mb-3">
            <a href="{{ route('college_message.table') }}" class="btn btn-success">All Messages</a>
        </div>
        <h5 class="h4" style="text-align: center; margin:10px 0;">Add Message (Principal / Chairman / Coordinator)</h5>
    </div>
    <br>
    <form action="{{ route('college_message.store') }}" enctype="multipart/form-data" method="POST" style="width:100%;">
        @csrf
        <div class="row">
            <div class="col-md-7 col-12">
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                        placeholder="e.g. Ram Prasad Sharma" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                    <select name="designation" class="form-select" required>
                        <option value="">-- Select Designation --</option>
                        <option value="Principal" {{ old('designation') == 'Principal' ? 'selected' : '' }}>Principal</option>
                        <option value="Chairman" {{ old('designation') == 'Chairman' ? 'selected' : '' }}>Chairman</option>
                        <option value="Coordinator" {{ old('designation') == 'Coordinator' ? 'selected' : '' }}>Coordinator</option>
                        <option value="Vice Principal" {{ old('designation') == 'Vice Principal' ? 'selected' : '' }}>Vice Principal</option>
                        <option value="Director" {{ old('designation') == 'Director' ? 'selected' : '' }}>Director</option>
                        <option value="Other" {{ old('designation') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control" rows="8"
                        placeholder="Write the message here...">{{ old('message') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control"
                        placeholder="0 = first" min="0">
                    <small class="text-muted">Lower number appears first.</small>
                </div>
            </div>
            <div class="col-md-5 col-12">
                <div class="mb-3">
                    <label class="form-label">Photo</label>
                    <input type="file" name="image" class="form-control" accept="image/*"
                        onchange="previewImage(this)">
                </div>
                <div id="imagePreview" style="display:none; margin-top:10px;">
                    <img id="previewImg" src="" alt="Preview"
                        style="width:160px; height:180px; object-fit:cover; border-radius:8px; border:2px solid #dee2e6;">
                </div>
            </div>
        </div>
        <div class="mb-3" style="margin:16px 0;">
            <button type="submit" class="btn btn-primary px-5">Save Message</button>
        </div>
    </form>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
