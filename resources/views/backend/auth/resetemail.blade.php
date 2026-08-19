@extends('backend.auth.layout.master')
@push('user-title')
    <title>Reset Email</title>
@endpush
@section('backend-auth-content')
<div class="card-body p-4">

    <div class="text-center w-75 m-auto">
        <h4 class="text-dark-50 text-center pb-0 fw-bold">Forgot Password</h4>
        <p class="text-muted mb-4">Enter the correct email to get the verification code.</p>
    </div>

    <form action="{{ route('email.check') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="emailaddress" class="form-label">Email address</label>
            <input class="form-control" name="email" type="email" id="emailaddress" required placeholder="Enter your email">
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
