@extends('backend.auth.layout.master')
@push('user-title')
    <title>OTP Code</title>
@endpush
@section('backend-auth-content')
<div class="card-body p-4">

    <div class="text-center w-75 m-auto">
        <h4 class="text-dark-50 text-center pb-0 fw-bold">OTP CODE</h4>
        <p class="text-muted mb-4">Enter the correct code to create a new Account.</p>
    </div>

    <form action="{{ route('otp.check') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="emailaddress" class="form-label">OTP Code</label>
            <input class="form-control" name="otp" type="text" id="emailaddress" required placeholder="Enter the otp code..">
        </div>

        <div class="mb-3 mb-0 text-center">
            <button class="btn btn-primary" type="submit">End</button>
        </div>
    </form>
    <div class="row mt-3">
        <div class="col-12 text-center">
            <p class="text-muted">Don't have an account? <a href="{{ route('admin.register') }}" class="text-muted ms-1"><b>Sign Up</b></a></p>
        </div> <!-- end col -->
    </div>
</div> <!-- end card-body -->

@endsection
