@extends('frontend.layout.master')
@section('frontend-content')

{{-- ===== COURSE DETAIL HERO ===== --}}
<div class="course-detail-hero">
    <img src="{{ asset($course->image) }}" alt="{{ $course->name }}">
    <div class="overlay"></div>
    <div class="cd-title">
        <h1>{{ $course->name }}</h1>
    </div>
</div>

{{-- ===== COURSE META BAR ===== --}}
<div class="course-meta-bar">
    <div class="meta-item">
        <i class="fa-regular fa-clock"></i>
        <span class="meta-label">Duration</span>
        <span class="meta-value">{{ $course->duration }} Years</span>
    </div>
    <div class="meta-item">
        <i class="fa-solid fa-layer-group"></i>
        <span class="meta-label">Semesters</span>
        <span class="meta-value">{{ $course->semester }}</span>
    </div>
    <div class="meta-item">
        <i class="fa-solid fa-school"></i>
        <span class="meta-label">Requirement</span>
        <span class="meta-value">{{ $course->requirement }}</span>
    </div>
    <div class="meta-item">
        <i class="fa-solid fa-clock"></i>
        <span class="meta-label">Timing</span>
        <span class="meta-value">{{ $course->starting_time }} &ndash; {{ $course->closing_time }}</span>
    </div>
</div>

{{-- ===== COURSE CONTENT ===== --}}
<section class="course-content-body">
    <div class="container">
        <div class="row g-4">

            {{-- Main Content --}}
            <div class="col-lg-8" data-aos="fade-up">
                <div class="rich-content">
                    {!! $course->fulldescription !!}
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div style="background: #fff; border-radius: var(--radius-md); box-shadow: var(--shadow-md); overflow: hidden; position: sticky; top: 90px;">
                    {{-- Sidebar Header --}}
                    <div style="background: linear-gradient(135deg, var(--dark), var(--green-dark)); padding: 26px 28px; color: #fff;">
                        <h5 style="font-family: var(--font-heading); font-size: 16px; font-weight: 800; margin-bottom: 4px;">Course Summary</h5>
                        <p style="font-size: 12.5px; color: rgba(255,255,255,.6); margin: 0;">Quick overview of this program</p>
                    </div>
                    {{-- Sidebar Body --}}
                    <div style="padding: 28px;">
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid var(--border);">
                                <span style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-book" style="color: var(--green-dark); width: 16px;"></i> Program Name
                                </span>
                                <span style="font-size: 13px; font-weight: 700; color: var(--dark); text-align: right; max-width: 55%;">{{ $course->name }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid var(--border);">
                                <span style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-regular fa-clock" style="color: var(--green-dark); width: 16px;"></i> Duration
                                </span>
                                <span style="font-size: 13px; font-weight: 700; color: var(--dark);">{{ $course->duration }} Years</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid var(--border);">
                                <span style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-layer-group" style="color: var(--green-dark); width: 16px;"></i> Semesters
                                </span>
                                <span style="font-size: 13px; font-weight: 700; color: var(--dark);">{{ $course->semester }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid var(--border);">
                                <span style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-school" style="color: var(--green-dark); width: 16px;"></i> Requirement
                                </span>
                                <span style="font-size: 13px; font-weight: 700; color: var(--dark); text-align: right; max-width: 55%;">{{ $course->requirement }}</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0;">
                                <span style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-clock" style="color: var(--green-dark); width: 16px;"></i> Class Timing
                                </span>
                                <span style="font-size: 13px; font-weight: 700; color: var(--dark);">{{ $course->starting_time }} &ndash; {{ $course->closing_time }}</span>
                            </li>
                        </ul>
                        <div style="margin-top: 24px;">
                            <a href="{{ route('contact') }}" class="btn-gplc w-100 justify-content-center">
                                <i class="fa-solid fa-user-plus"></i> Apply Now
                            </a>
                        </div>
                        <p style="font-size: 12px; color: var(--text-muted); text-align: center; margin-top: 12px; margin-bottom: 0;">
                            <i class="fa-solid fa-shield-halved" style="color: var(--green-dark);"></i>
                            Affiliated with Lincoln University Malaysia
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
