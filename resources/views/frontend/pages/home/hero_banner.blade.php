<div class="gplc-hero">
    <div class="swiper MainSwiper">
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
                <div class="swiper-slide gplc-hero-slide">
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->title2 }}">
                    <div class="gplc-hero-overlay"></div>
                    <div class="gplc-hero-content">
                        <span class="tag-line">
                            <i class="fa-solid fa-graduation-cap"></i>
                            Affiliated with Lincoln University Malaysia
                        </span>
                        <h1>{{ $banner->title2 }}</h1>
                        <p>Shaping Future Leaders with World-Class Education</p>
                        <div class="gplc-hero-actions">
                            <a href="{{ route('about.us') }}" class="btn-gplc">
                                <i class="fa-solid fa-compass"></i> Explore Programs
                            </a>
                            <a href="{{ route('contact') }}" class="btn-gplc-light">
                                <i class="fa-solid fa-phone"></i> Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide gplc-hero-slide" style="background: linear-gradient(135deg, var(--dark) 0%, var(--green-dark) 100%);">
                    <div class="gplc-hero-overlay"></div>
                    <div class="gplc-hero-content">
                        <span class="tag-line">
                            <i class="fa-solid fa-graduation-cap"></i>
                            Affiliated with Lincoln University Malaysia
                        </span>
                        <h1>Green Peace Lincoln College</h1>
                        <p>Shaping Future Leaders with World-Class Education</p>
                        <div class="gplc-hero-actions">
                            <a href="{{ route('about.us') }}" class="btn-gplc">
                                <i class="fa-solid fa-compass"></i> Explore Programs
                            </a>
                            <a href="{{ route('contact') }}" class="btn-gplc-light">
                                <i class="fa-solid fa-phone"></i> Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="hero-pagination swiper-pagination"></div>
    </div>
</div>
