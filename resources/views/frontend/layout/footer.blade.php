@php
    $siteSettings = \App\Models\SiteSetting::current();
    $footerCourses = \App\Models\Course::where('status', 1)->get();
@endphp
{{-- Footer uses gplc-footer CSS from style.css --}}
<style>
.gplc-footer {
    background: #0a1a0c; /* Keep it dark regardless of theme */
    color: rgba(255,255,255,0.65);
    padding: 0;
}
.gplc-footer-brand img { margin-bottom: 14px; }
.gplc-footer-brand p { font-size: 14px; line-height: 1.7; color: rgba(255,255,255,0.6); }
.gplc-footer h6 {
    color: #fff;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
}
.gplc-footer h6::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 28px;
    height: 2px;
    background: #52b788;
}
.gplc-footer-links { list-style: none; padding: 0; margin: 0; }
.gplc-footer-links li { margin-bottom: 10px; }
.gplc-footer-links a {
    color: rgba(255,255,255,0.6);
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s, padding-left 0.2s;
}
.gplc-footer-links a:hover { color: #52b788; padding-left: 4px; }
.gplc-footer-contact li {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
    font-size: 14px;
}
.gplc-footer-contact li i { color: #52b788; margin-top: 2px; min-width: 16px; }
.gplc-footer-contact a { color: rgba(255,255,255,0.6); text-decoration: none; }
.gplc-footer-contact a:hover { color: #52b788; }
.gplc-footer-social { display: flex; gap: 10px; margin-top: 16px; }
.gplc-footer-social a {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
    color: rgba(255,255,255,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
}
.gplc-footer-social a:hover { background: #52b788; color: #fff; }
.gplc-footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.08);
    padding: 18px 0;
    margin-top: 50px;
    font-size: 13px;
}
.gplc-footer-bottom a { color: #52b788; text-decoration: none; }
.gplc-footer-bottom a:hover { text-decoration: underline; }
</style>

<footer class="gplc-footer">
    <div class="container">
        <div class="row g-5">

            {{-- Brand --}}
            <div class="col-lg-3 col-md-6">
                <div class="gplc-footer-brand">
                    <img src="{{ \App\Models\SiteSetting::current()->site_logo ? asset('storage/' . \App\Models\SiteSetting::current()->site_logo) : asset('backend/images/logo.png') }}" width="110" alt="GPLC Logo">
                    <p>{{ $siteSettings->site_name }}, providing quality education and shaping future leaders in {{ $siteSettings->contact_address }}.</p>
                    <div class="gplc-footer-social">
                        <a href="{{ $siteSettings->facebook_url }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $siteSettings->youtube_url }}" target="_blank"><i class="fab fa-youtube"></i></a>
                        <a href="{{ $siteSettings->instagram_url }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/{{ $siteSettings->whatsapp_number }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6">
                <h6>Quick Links</h6>
                <ul class="gplc-footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about.us') }}">About Us</a></li>
                    <li><a href="{{ route('calendar') }}">Calendar</a></li>
                    <li><a href="{{ route('gallery') }}">Gallery</a></li>
                    <li><a href="{{ route('member') }}">Faculties</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            {{-- Courses --}}
            <div class="col-lg-3 col-md-6">
                <h6>Our Courses</h6>
                <ul class="gplc-footer-links">
                    @foreach($footerCourses as $fc)
                        <li><a href="{{ url('course/' . $fc->slug) }}">{{ $fc->name }}</a></li>
                    @endforeach
                    @if($footerCourses->isEmpty())
                        <li><a href="{{ route('home') }}">No courses yet</a></li>
                    @endif
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-lg-4 col-md-6">
                <h6>Contact Us</h6>
                <ul class="gplc-footer-contact list-unstyled">
                    <li><i class="fa-solid fa-location-dot"></i> <span>{{ $siteSettings->contact_address }}</span></li>
                    <li><i class="fa-solid fa-phone"></i> <a href="tel:{{ $siteSettings->contact_phone }}">{{ $siteSettings->contact_phone }}</a></li>
                    <li><i class="fa-solid fa-envelope"></i> <a href="mailto:{{ $siteSettings->contact_email }}">{{ $siteSettings->contact_email }}</a></li>
                    <li><i class="fa-brands fa-whatsapp"></i> <a href="https://wa.me/{{ $siteSettings->whatsapp_number }}">+977 {{ $siteSettings->whatsapp_number }}</a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="gplc-footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0">&copy; {{ now()->year }} {{ $siteSettings->site_name }}. All Rights Reserved.</p>
            <p class="mb-0">
                <a href="{{ route('privacy.policy') }}">Privacy Policy</a> &nbsp;|&nbsp;
                Designed by <a href="https://nstudios1.blogspot.com/" target="_blank">nstudios</a>
            </p>
        </div>
    </div>
</footer>
