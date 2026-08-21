@extends('backend.pages.layout.master')
@push('b-title', 'Add Notice')
@section('backend-content')
    <div class="row">
        <div class="mb-3">
            <a href="{{ route('notice.table') }}" class="btn btn-success">&nbsp;&nbsp;Table</a>
        </div>
        <h5 class="h4" style="text-align: center; margin:10px 0;">Add Notice</h5>
    </div>
    <br>
    <form action="{{ route('notice.store') }}" enctype="multipart/form-data" method="POST" style="width: 100%;">
        @csrf
        <div class="row">
            <div class="col-md-6 col-12 mx-auto">
                <div class="card shadow-sm p-4">
                    <div class="mb-3">
                        <label for="" class="form-label fw-bold">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label fw-bold">Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" name="description" rows="5" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary w-100">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
