@extends('backend.auth.layout.master')
@push('user-title')
    <title>Admin Register</title>
@endpush
@section('backend-auth-content')
<div class="card-body p-4">

    <div class="text-center w-75 m-auto">
        <h4 class="text-dark-50 text-center pb-0 fw-bold">Sign Up</h4>
        <p class="text-muted mb-4">Create your new email and password</p>
    </div>

    <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="emailaddress" class="form-label">User Name</label>
            <input class="form-control" name="name" type="text" id="emailaddress" required placeholder="Enter your name">
        </div>
        <div class="mb-3">
            <label for="emailaddress" class="form-label">Create Your Avatar</label>
            <input class="form-control" name="image" type="file" id="emailaddress" >
        </div>

        <div class="mb-3">
            <label for="emailaddress" class="form-label">Email address</label>
            <input class="form-control" type="email" name="email" id="emailaddress" required placeholder="Enter your email">
        </div>
        @if (Auth::check() && Auth::user()->u_type == 'O')
            <select name="u_type" id="">
                <option value="E">Editor</option>
                <option value="S">Seller</option>
            </select>
        @endif
        <div class="mb-3">
            <a href="pages-recoverpw.html" class="text-muted float-end"><small>Forgot your password?</small></a>
            <label for="password" class="form-label">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
        </div>

        <div class="mb-3 mb-0 text-center">
            <button class="btn btn-primary" type="submit"> Register </button>
        </div>

    </form>
    <div class="row mt-3">
        <div class="col-12 text-center">
            <p class="text-muted">Have an account? <a href="{{ route('admin.login') }}" class="text-muted ms-1"><b>Sign In</b></a></p>
        </div> <!-- end col -->
    </div>
</div> <!-- end card-body -->

@endsection
