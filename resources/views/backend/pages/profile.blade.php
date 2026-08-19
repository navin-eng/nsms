@extends('backend.pages.layout.master')
@push('b-title','MY Profile')
@section('backend-content')
<div class="row">
    <div class="col-md-4 col-12">
        <div class="ProfileImage">
            <img src="{{ asset(Auth::user()->image) }}" width="100%" alt="">
        </div>
    </div>
    <div class="col-md-8 col-12">
        <h3 class="h3">{{ Auth::user()->name }}</h3>
        <h3 class="h4">{{ Auth::user()->email }}</h3>
        @if (Auth::user()->a_type == 'A')
        <h5 class="h5">Role: Admin</h5>
        @else
            <h5 class="h5">Role: Editor</h5>
        @endif
        <a href="{{ route('editor.edit',Auth::user()->id) }}" class="btn btn-primary">Edit</a>
    </div>
</div>

@endsection
