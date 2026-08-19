@extends('frontend.layout.master')
@section('frontend-content')

{{-- ===== PAGE HERO ===== --}}
<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>Contact Us</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Contact Us
            </nav>
        </div>
    </div>
</div>

{{-- ===== CONTACT MAIN SECTION ===== --}}
<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Get In Touch</span>
            <h2 class="section-title mt-2">We'd Love to Hear From You</h2>
            <div class="section-divider center"></div>
        </div>

        <div class="row g-4">
            {{-- Contact Info Card --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="contact-info-card">
                    <h4>Contact Information</h4>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="info-text">
                            <div class="label">Address</div>
                            Dipendra Chowk, Itahari-2, Sunsari, Nepal
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-phone-alt"></i></div>
                        <div class="info-text">
                            <div class="label">Phone</div>
                            <a href="tel:025586701">025-586701</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fas fa-envelope"></i></div>
                        <div class="info-text">
                            <div class="label">Email</div>
                            <a href="mailto:info@gplc.edu.np">info@gplc.edu.np</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ico"><i class="fab fa-whatsapp"></i></div>
                        <div class="info-text">
                            <div class="label">WhatsApp</div>
                            <a href="https://wa.me/9812355717" target="_blank">+977 9812355717</a>
                        </div>
                    </div>
                    <div class="social-row">
                        <a href="https://www.facebook.com/GplcIth" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="https://wa.me/9812355717" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            {{-- Map --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="gplc-map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3565.63095380432!2d87.27117921420957!3d26.66029607733071!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ef6c6f77f068db%3A0x5459ed0af4c0ffc2!2sGreen%20Peace%20Linclon%20College!5e0!3m2!1sen!2snp!4v1671550315569!5m2!1sen!2snp"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-form-card">
                    <h4>Send Message</h4>
                    <form action="{{ route('message.send') }}" method="POST">
                        @csrf
                        <input type="text" name="name" placeholder="Your Full Name" class="gplc-input" required>
                        <input type="email" name="email" placeholder="Email Address" class="gplc-input" required>
                        <input type="tel" name="phone" placeholder="Phone Number" class="gplc-input">
                        <input type="text" name="address" placeholder="Your Address" class="gplc-input">
                        <textarea name="desc" placeholder="Write your message here..." class="gplc-input"></textarea>
                        <button type="submit" class="btn-gplc w-100 justify-content-center">
                            <i class="fas fa-paper-plane me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== QUICK INFO BOXES ROW ===== --}}
        <div class="row g-4 mt-4">
            @foreach([
                ['fas fa-map-marker-alt','Our Address','Dipendra Chowk, Itahari-2, Sunsari, Nepal','#'],
                ['fas fa-phone-alt','Call Us','025-586701','tel:025586701'],
                ['fas fa-envelope','Email Us','info@gplc.edu.np','mailto:info@gplc.edu.np'],
            ] as [$icon, $label, $value, $link])
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div style="background:var(--green-pale);border:1.5px solid var(--border);border-radius:12px;padding:36px 28px;text-align:center;height:100%;transition:all .3s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 8px 32px rgba(0,95,26,.13)'" onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                    <div style="width:66px;height:66px;border-radius:50%;background:var(--green-dark);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                        <i class="{{ $icon }} fa-lg" style="color:#fff;"></i>
                    </div>
                    <p style="font-size:11px;text-transform:uppercase;letter-spacing:2px;color:var(--green-dark);font-weight:700;margin-bottom:8px;">{{ $label }}</p>
                    <a href="{{ $link }}" style="font-size:15px;font-weight:700;color:var(--dark);text-decoration:none;">{{ $value }}</a>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

@endsection
