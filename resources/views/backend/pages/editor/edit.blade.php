@extends('backend.pages.layout.master')
@push('b-title', 'Update Editor')
@section('backend-content')
    <div class="row">
        <h5 class="h4" style="text-align: center; margin:10px 0;">Update Editor</h5>
        <form action="{{ route('editor.update',$editor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Name</label>
              <input type="text" class="form-control" name="name" value="{{ $editor->name }}" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input type="text" class="form-control" name="email" value="{{ $editor->email }}" id="exampleInputEmail1" aria-describedby="emailHelp">
              </div>
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Image</label>
                <input type="file" class="form-control" name="image" id="exampleInputEmail1" aria-describedby="emailHelp">
              </div>
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Password </label>
                <input type="text" class="form-control" name="password" id="exampleInputEmail1" aria-describedby="emailHelp">
              </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
@endsection
