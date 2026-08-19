@extends('backend.pages.layout.master')
@push('b-title', 'Edit Notice')
@section('backend-content')
    <div class="row">
        <div class="mb-3">
            <a href="{{ route('notice.table') }}" class="btn btn-success">&nbsp;&nbsp;Table</a>
        </div>
        <h5 class="h4" style="text-align: center; margin:10px 0;">Edit Notice</h5>
    </div>
    <br>
    <form action="{{ route('notice.update',$notice->id) }}" enctype="multipart/form-data" method="POST" style="width: 100%;">
        @csrf
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="mb-3">
                    <label for="" class="form-label">Title</label>
                    <input type="text" name="title" value="{{ $notice->title }}" id="" class="form-control"
                        placeholder="" aria-describedby="helpId">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Description</label>
                    <textarea class="form-control"  name="description">
                        {{ $notice->description }}
                    </textarea>
                </div>
            </div>
        </div>
        <div class="mb-3" style="margin: 10px 0;">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
@endsection
