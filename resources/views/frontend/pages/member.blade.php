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
    // $teachers here is a collection of App\Models\Staff
    $groupedStaff = $teachers->groupBy(function($staff) {
        return $staff->department ? $staff->department->name : 'Other Staff';
    });
@endphp

<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">The People Behind GPLC</span>
            <h2 class="section-title mt-2">Meet Our Team</h2>
            <div class="section-divider center"></div>
        </div>

        @if($groupedStaff->count() > 0)
            @foreach($groupedStaff as $departmentName => $staffMembers)
            <div class="mb-5" data-aos="fade-up">
                <h3 class="mb-4" style="font-family: var(--font-heading); color: var(--dark);">
                    <i class="fa-solid fa-users" style="color: var(--green-dark); margin-right: 8px;"></i>{{ $departmentName }}
                </h3>
                <div class="row">
                    @foreach($staffMembers as $data)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                        <div class="faculty-card">
                            <div class="fc-img">
                                @if($data->photo)
                                    <img src="{{ asset('storage/' . $data->photo) }}" alt="{{ $data->full_name }}" loading="lazy" style="height: 250px; object-fit: cover;">
                                @else
                                    <div style="width:100%;height:250px;background:var(--green-pale);display:flex;align-items:center;justify-content:center;">
                                        <i class="fa-solid fa-user fa-4x" style="color:var(--green-dark);opacity:.4;"></i>
                                    </div>
                                @endif
                                <div class="fc-overlay">
                                    {{-- If you add social links to staff later, output them here --}}
                                </div>
                            </div>
                            <div class="fc-body">
                                <h5>{{ $data->full_name }}</h5>
                                <span>{{ $data->designation->name ?? 'Staff' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
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
