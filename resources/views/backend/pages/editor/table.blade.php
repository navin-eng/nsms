@extends('backend.pages.layout.master')
@push('b-title', 'Editor Table')
@section('backend-content')
    <div class="row">
        <h5 class="h4" style="text-align: center; margin:10px 0;">Editor Table</h5>
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Add New Editor
              </button>
        </div>

          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add a new editor</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                          <label for="exampleInputEmail1" class="form-label">Name</label>
                          <input type="text" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                          </div>
                          <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" id="exampleInputEmail1" aria-describedby="emailHelp">
                          </div>
                          <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Password</label>
                            <input type="text" class="form-control" name="password" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">SN</th>
                <th scope="col">Name</th>
                <th scope="col">Image</th>
                <th scope="col">Email</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @isset($editor)
                @foreach ($editor as $data)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $data->name }}</td>
                        <td>
                            <img src="{{ asset($data->image) }}" class="table-backend-image" alt="">
                        </td>
                        <td>
                            {{ $data->email }}
                        </td>
                        <td>
                            <a href="{{ route('editor.edit',$data->id) }}" class="btn btn-info"><i class="bi bi-pen"></i></a>
                            <button class="btn btn-danger delete-wrap" data-route="{{ route('editor.delete',$data->id) }}"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endisset
        </tbody>
    </table>
@endsection
