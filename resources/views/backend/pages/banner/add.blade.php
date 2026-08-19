@extends('backend.pages.layout.master')
@push('b-title', 'Add Banner')
@section('backend-content')
    <div class="row">
        <div class="mb-3">
            <a href="{{ route('banner.table') }}" class="btn btn-success">&nbsp;&nbsp;Table</a>
        </div>
        <h5 class="h4" style="text-align: center; margin:10px 0;">Add Banner</h5>
    </div>
    <br>
    <form action="{{ route('banner.store') }}" enctype="multipart/form-data" method="POST" style="width: 100%;">
        @csrf
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="mb-3">
                    <label class="form-label">Title (One)</label>
                    <input type="text" name="title1" value="{{ old('title1') }}" class="form-control"
                        placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Title (Two)</label>
                    <input type="text" name="title2" value="{{ old('title2') }}" class="form-control"
                        placeholder="" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" required class="form-control">
                </div>
            </div>
        </div>
        <div class="mb-3" style="margin: 10px 0;">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
