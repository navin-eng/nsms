@extends('backend.auth.layout.master')
@push('user-title')
    <title>Reset Password</title>
@endpush
@section('backend-auth-content')
<div class="card-body p-4">

    <div class="text-center w-75 m-auto">
        <h4 class="text-dark-50 text-center pb-0 fw-bold">Reset Password</h4>
    </div>

    <form action="{{ route('resetPassword') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="emailaddress" class="form-label">Code</label>
            <input class="form-control" name="code" type="text" id="emailaddress" required="" placeholder="OTP CODE">
        </div>
        <div class="mb-3">
            <label for="emailaddress" class="form-label">Password</label>
            <input class="form-control" name="password" type="password" id="emailaddress" required placeholder="New Password">
        </div>

        <div class="mb-3 mb-0 text-center">
            <button class="btn btn-primary" type="submit">Send</button>
        </div>
    </form>
    <div class="row mt-3">
        <div class="col-12 text-center">
            <p class="text-muted"><a href="{{ route('admin.login') }}" class="text-muted ms-1"><b>Go Back</b></a></p>
        </div> <!-- end col -->
    </div>
</div> <!-- end card-body -->

@endsection
