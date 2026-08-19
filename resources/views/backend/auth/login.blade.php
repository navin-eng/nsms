@extends('backend.auth.layout.master')
@push('user-title')
    <title>Admin Login</title>
@endpush
@section('backend-auth-content')
<div class="card-body p-4">

    <div class="text-center w-100 m-auto">
        <h4 class="text-dark text-center pb-0 fw-bold">Admin Sign In</h4>
        <p class="text-muted mb-4">Use your authorized email and password to enter the college management dashboard.</p>
    </div>

    <form action="{{ route('admin.check') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="emailaddress" class="form-label">Email address</label>
            <input class="form-control" name="email" type="email" id="emailaddress" required="" placeholder="Enter your email">
        </div>

        <div class="mb-3">
            <a href="{{ route('forgot.password') }}" class="text-muted float-end"><small>Forgot your password?</small></a>
            <label for="password" class="form-label">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
        </div>

        <div class="mb-3 mb-0 text-center">
            <button class="btn btn-primary w-100" type="submit"> Log In </button>
        </div>
    </form>
    <div class="row mt-3">
        <div class="col-12 text-center">
            <p class="text-muted mb-0"><a href="{{ route('home') }}" class="text-decoration-none">Return to public website</a></p>
        </div>
    </div>
</div> <!-- end card-body -->

@endsection
