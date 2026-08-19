@extends('backend.pages.layout.master')
@push('b-title', 'Manage Counter')

@section('backend-content')
    @php
        $isExisting = !is_null($counter);
        $action = $isExisting ? route('counter.update', $counter->id) : route('counter.store');
        $defaults = [
            'section_tag' => old('section_tag', $counter->section_tag ?? 'Our Impact'),
            'section_title' => old('section_title', $counter->section_title ?? 'Numbers That Reflect Our Growth'),
            'section_description' => old('section_description', $counter->section_description ?? 'Update these figures from the dashboard anytime.'),
            'title1' => old('title1', $counter->title1 ?? 'Students Enrolled'),
            'counter1' => old('counter1', $counter->counter1 ?? 1200),
            'suffix1' => old('suffix1', $counter->suffix1 ?? '+'),
            'icon1' => old('icon1', $counter->icon1 ?? 'fa-solid fa-users'),
            'title2' => old('title2', $counter->title2 ?? 'Graduates'),
            'counter2' => old('counter2', $counter->counter2 ?? 450),
            'suffix2' => old('suffix2', $counter->suffix2 ?? '+'),
            'icon2' => old('icon2', $counter->icon2 ?? 'fa-solid fa-graduation-cap'),
            'title3' => old('title3', $counter->title3 ?? 'Awards Won'),
            'counter3' => old('counter3', $counter->counter3 ?? 32),
            'suffix3' => old('suffix3', $counter->suffix3 ?? '+'),
            'icon3' => old('icon3', $counter->icon3 ?? 'fa-solid fa-trophy'),
            'title4' => old('title4', $counter->title4 ?? 'Programs Offered'),
            'counter4' => old('counter4', $counter->counter4 ?? 14),
            'suffix4' => old('suffix4', $counter->suffix4 ?? '+'),
            'icon4' => old('icon4', $counter->icon4 ?? 'fa-solid fa-book'),
        ];
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">Counter Management</h3>
            <p class="text-muted mb-0">Customize the label, value, icon, and suffix for each homepage counter card.</p>
        </div>
    </div>

    <form action="{{ $action }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Section Tag</label>
                        <input type="text" name="section_tag" class="form-control" value="{{ $defaults['section_tag'] }}" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Section Title</label>
                        <input type="text" name="section_title" class="form-control" value="{{ $defaults['section_title'] }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Section Description</label>
                        <input type="text" name="section_description" class="form-control" value="{{ $defaults['section_description'] }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @for($i = 1; $i <= 4; $i++)
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="mb-3">Counter {{ $i }}</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Label</label>
                                    <input type="text" name="title{{ $i }}" class="form-control" value="{{ $defaults['title' . $i] }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Value</label>
                                    <input type="number" min="0" name="counter{{ $i }}" class="form-control" value="{{ $defaults['counter' . $i] }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Suffix</label>
                                    <input type="text" name="suffix{{ $i }}" class="form-control" value="{{ $defaults['suffix' . $i] }}" placeholder="+">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Icon Class</label>
                                    <input type="text" name="icon{{ $i }}" class="form-control" value="{{ $defaults['icon' . $i] }}" placeholder="fa-solid fa-users">
                                    <small class="text-muted">Example: <code>fa-solid fa-users</code></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-4">{{ $isExisting ? 'Update Counter Section' : 'Create Counter Section' }}</button>
        </div>
    </form>
@endsection
