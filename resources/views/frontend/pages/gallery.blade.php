@extends('frontend.layout.master')
@section('frontend-content')

{{-- ===== PAGE HERO ===== --}}
<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>Photo Gallery</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Photo Gallery
            </nav>
        </div>
    </div>
</div>

{{-- ===== GALLERY SECTION ===== --}}
<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Memories</span>
            <h2 class="section-title mt-2">Our Gallery</h2>
            <div class="section-divider center"></div>
        </div>

        @if($galleries->count() > 0)
            <div id="galleryRow" data-aos="fade-up" data-aos-delay="100">
                @foreach($galleries as $gallery)
                    @php
                        $images = is_array($gallery->gallery)
                            ? $gallery->gallery
                            : json_decode($gallery->gallery, true);
                    @endphp
                    @if($images && count($images) > 0)
                        @foreach($images as $img)
                            <a href="{{ asset('backend/images/gallery/' . $img) }}"
                               data-lg-size="1400-900"
                               data-sub-html="{{ $gallery->title ?? '' }}">
                                <img src="{{ asset('backend/images/gallery/' . $img) }}"
                                     alt="{{ $gallery->title ?? 'Gallery Image' }}"
                                     loading="lazy">
                            </a>
                        @endforeach
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fa-solid fa-images fa-3x mb-3" style="color: var(--green-light);"></i>
                <h5 style="color: var(--dark);">No gallery images available yet.</h5>
                <p class="text-muted">Check back soon for photos from our campus events and activities.</p>
            </div>
        @endif
    </div>
</section>

@endsection
