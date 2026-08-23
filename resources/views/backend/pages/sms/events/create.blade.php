@extends('backend.pages.layout.master')
@push('b-title', 'New Event')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4" style="max-width:860px">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('sms.events.index') }}" class="btn btn-sm btn-light border">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="h4 fw-bold text-dark mb-0">New Event</h2>
            <p class="text-muted small mb-0">Fill in the details to create a new event.</p>
        </div>
    </div>

    <form action="{{ route('sms.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('backend.pages.sms.events._form')
        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-5">Create Event</button>
            <a href="{{ route('sms.events.index') }}" class="btn btn-light border">Cancel</a>
        </div>
    </form>
</div>
@endsection
