@extends('backend.pages.layout.master')
@push('b-title', 'Edit Banner')
@section('backend-content')
    <div class="row">
        <h5 class="h4" style="text-align: center; margin:10px 0;">Edti Banner</h5>
    </div>
    <br>
    <form action="{{ route('banner.update',$banner->id) }}" enctype="multipart/form-data" method="POST" style="width: 100%;">
        @csrf
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="mb-3">
                    <label class="form-label">Title (One)</label>
                    <input type="text" name="title1" value="{{ $banner->title1 }}" class="form-control"
                        placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Title (Two)</label>
                    <input type="text" name="title2" value="{{ $banner->title2 }}" class="form-control"
                        placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>
        </div>
        <div class="mb-3" style="margin: 10px 0;">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
@endsection
