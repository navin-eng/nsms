@extends('backend.pages.layout.master')
@push('b-title', 'Edit Event')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4" style="max-width:860px">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('sms.events.show', $event) }}" class="btn btn-sm btn-light border">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="h4 fw-bold text-dark mb-0">Edit Event</h2>
            <p class="text-muted small mb-0">{{ $event->name }}</p>
        </div>
    </div>

    <form action="{{ route('sms.events.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('backend.pages.sms.events._form', ['event' => $event])
        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-5">Save Changes</button>
            <a href="{{ route('sms.events.show', $event) }}" class="btn btn-light border">Cancel</a>
        </div>
    </form>
</div>
@endsection
