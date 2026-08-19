@extends('frontend.layout.master')
@section('frontend-content')

@foreach($orderedSectionKeys as $sectionKey)
    @includeIf('frontend.pages.home.' . $sectionKey)
@endforeach

@endsection
