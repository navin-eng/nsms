@extends('frontend.layout.master')
@section('frontend-content')

{{-- ===== PAGE HERO ===== --}}
<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>Our Team</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Our Team
            </nav>
        </div>
    </div>
</div>

{{-- ===== TEAM SECTION ===== --}}
@php
    $administrativeStaff = $teachers->where('staff_type', 'administrative');
    $teachingStaff       = $teachers->where('staff_type', 'teaching');
    $nonTeachingStaff    = $teachers->where('staff_type', 'non_teaching');
    $hasAny = $administrativeStaff->count() || $teachingStaff->count() || $nonTeachingStaff->count();
@endphp

<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">The People Behind GPLC</span>
            <h2 class="section-title mt-2">Meet Our Team</h2>
            <div class="section-divider center"></div>
        </div>

        @if($hasAny)

            @if($administrativeStaff->count() > 0)
            <div class="mb-5" data-aos="fade-up">
                <h3 class="mb-4" style="font-family: var(--font-heading); color: var(--dark);">
                    <i class="fa-solid fa-building-columns" style="color: var(--green-dark); margin-right: 8px;"></i>Administrative Staff
                </h3>
                <div class="row">
                    @foreach($administrativeStaff as $data)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                        <div class="faculty-card">
                            <div class="fc-img">
                                @if($data->image)
                                    <img src="{{ asset($data->image) }}" alt="{{ $data->name }}" loading="lazy">
                                @else
                                    <div style="width:100%;height:100%;background:var(--green-pale);display:flex;align-items:center;justify-content:center;">
                                        <i class="fa-solid fa-user fa-4x" style="color:var(--green-dark);opacity:.4;"></i>
                                    </div>
                                @endif
                                <div class="fc-overlay">
                                    @if($data->facebook_link)
                                        <a href="{{ $data->facebook_link }}" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                                    @endif
                                </div>
                            </div>
                            <div class="fc-body">
                                <h5>{{ $data->name }}</h5>
                                <span>{{ $data->role }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($teachingStaff->count() > 0)
            <div class="mb-5" data-aos="fade-up">
                <h3 class="mb-4" style="font-family: var(--font-heading); color: var(--dark);">
                    <i class="fa-solid fa-chalkboard-user" style="color: var(--green-dark); margin-right: 8px;"></i>Teaching Staff
                </h3>
                <div class="row">
                    @foreach($teachingStaff as $data)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                        <div class="faculty-card">
                            <div class="fc-img">
                                @if($data->image)
                                    <img src="{{ asset($data->image) }}" alt="{{ $data->name }}" loading="lazy">
                                @else
                                    <div style="width:100%;height:100%;background:var(--green-pale);display:flex;align-items:center;justify-content:center;">
                                        <i class="fa-solid fa-user fa-4x" style="color:var(--green-dark);opacity:.4;"></i>
                                    </div>
                                @endif
                                <div class="fc-overlay">
                                    @if($data->facebook_link)
                                        <a href="{{ $data->facebook_link }}" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                                    @endif
                                </div>
                            </div>
                            <div class="fc-body">
                                <h5>{{ $data->name }}</h5>
                                <span>{{ $data->role }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($nonTeachingStaff->count() > 0)
            <div data-aos="fade-up">
                <h3 class="mb-4" style="font-family: var(--font-heading); color: var(--dark);">
                    <i class="fa-solid fa-users" style="color: var(--green-dark); margin-right: 8px;"></i>Non-Teaching Staff
                </h3>
                <div class="row">
                    @foreach($nonTeachingStaff as $data)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                        <div class="faculty-card">
                            <div class="fc-img">
                                @if($data->image)
                                    <img src="{{ asset($data->image) }}" alt="{{ $data->name }}" loading="lazy">
                                @else
                                    <div style="width:100%;height:100%;background:var(--green-pale);display:flex;align-items:center;justify-content:center;">
                                        <i class="fa-solid fa-user fa-4x" style="color:var(--green-dark);opacity:.4;"></i>
                                    </div>
                                @endif
                                <div class="fc-overlay">
                                    @if($data->facebook_link)
                                        <a href="{{ $data->facebook_link }}" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                                    @endif
                                </div>
                            </div>
                            <div class="fc-body">
                                <h5>{{ $data->name }}</h5>
                                <span>{{ $data->role }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        @else
        <div class="text-center py-5" data-aos="fade-up">
            <i class="fa-solid fa-users fa-3x mb-3" style="color: var(--green-light);"></i>
            <h5 style="color: var(--dark);">Team information coming soon.</h5>
            <p class="text-muted">We are updating our team profiles. Please check back later.</p>
        </div>
        @endif
    </div>
</section>

@endsection
