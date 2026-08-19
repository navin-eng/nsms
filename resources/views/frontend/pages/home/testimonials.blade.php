@if($testimonials->count() > 0)
    <section class="gplc-testi">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-tag" style="background: rgba(146,203,108,.15); color: var(--green-light);">Student Voice</span>
                <h2 class="section-title mt-2" style="color: #fff;">What Our Students Say</h2>
                <div class="section-divider center"></div>
            </div>
            <div class="swiper testiSwiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    @foreach($testimonials as $testi)
                        <div class="swiper-slide">
                            <div class="testi-card">
                                <div class="quote-ico">
                                    <i class="fa-solid fa-quote-left"></i>
                                </div>
                                <div class="testi-copy">
                                    <p>{{ $testi->description }}</p>
                                </div>
                                <div class="testi-author">
                                    @if($testi->image)
                                        <img src="{{ asset($testi->image) }}" alt="{{ $testi->name }}" loading="lazy">
                                    @else
                                        <img src="{{ asset('backend/images/default-avatar.png') }}" alt="{{ $testi->name }}" loading="lazy">
                                    @endif
                                    <div>
                                        <div class="name">{{ $testi->name }}</div>
                                        <div class="role">{{ $testi->role ?? 'Student' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="testi-pag swiper-pagination"></div>
        </div>
    </section>
@endif
