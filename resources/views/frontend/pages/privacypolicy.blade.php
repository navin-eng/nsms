@extends('frontend.layout.master')

@section('frontend-content')
@php
    $siteSettings = \App\Models\SiteSetting::current();
    $privacy = $privacyPolicy; // from controller
@endphp

<style>
    .privacy-hero {
        position: relative;
        overflow: hidden;
        padding: 88px 0 54px;
        background:
            radial-gradient(circle at top left, rgba(82, 183, 136, 0.18), transparent 32%),
            linear-gradient(135deg, #081c15 0%, #0f3d2e 55%, #1b4332 100%);
        color: #fff;
    }

    .privacy-hero::after {
        content: '';
        position: absolute;
        inset: auto -8% -70px auto;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        filter: blur(6px);
    }

    .privacy-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.1);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .privacy-title {
        margin: 18px 0 14px;
        font-size: clamp(32px, 5vw, 54px);
        line-height: 1.05;
        font-weight: 800;
        max-width: 760px;
    }

    .privacy-subtitle {
        max-width: 720px;
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        font-size: 16px;
        line-height: 1.8;
    }

    .privacy-shell {
        margin-top: -34px;
        margin-bottom: 72px;
        position: relative;
        z-index: 2;
    }

    .privacy-meta-card,
    .privacy-content-card {
        background: #fff;
        border-radius: 28px;
        box-shadow: 0 24px 70px rgba(8, 28, 21, 0.08);
    }

    .privacy-meta-card {
        padding: 26px;
        position: sticky;
        top: 110px;
    }

    .privacy-meta-card h3,
    .privacy-content-card h3 {
        font-size: 20px;
        font-weight: 800;
        color: #0b2c20;
        margin-bottom: 18px;
    }

    .privacy-meta-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: 14px;
    }

    .privacy-meta-list li {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        color: #315246;
        line-height: 1.6;
        font-size: 14px;
    }

    .privacy-meta-list i {
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef8f2;
        color: #1b7f5b;
        font-size: 16px;
    }

    .privacy-content-card {
        padding: 34px;
    }

    .privacy-content-card .privacy-body {
        color: #35564b;
        font-size: 16px;
        line-height: 1.9;
    }

    .privacy-content-card .privacy-body h1,
    .privacy-content-card .privacy-body h2,
    .privacy-content-card .privacy-body h3,
    .privacy-content-card .privacy-body h4,
    .privacy-content-card .privacy-body h5,
    .privacy-content-card .privacy-body h6 {
        color: #0b2c20;
        font-weight: 800;
        margin-top: 28px;
        margin-bottom: 12px;
    }

    .privacy-content-card .privacy-body p:last-child {
        margin-bottom: 0;
    }

    .privacy-content-card .privacy-body ul,
    .privacy-content-card .privacy-body ol {
        padding-left: 20px;
    }

    .privacy-contact-box {
        margin-top: 28px;
        padding: 22px 24px;
        border-radius: 22px;
        background: linear-gradient(135deg, #f3fbf6 0%, #edf7ff 100%);
        border: 1px solid rgba(27, 127, 91, 0.12);
    }

    .privacy-contact-box p {
        margin-bottom: 8px;
        color: #315246;
    }

    .privacy-contact-box p:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 991.98px) {
        .privacy-meta-card {
            position: static;
        }

        .privacy-shell {
            margin-top: -18px;
        }
    }

    @media (max-width: 575.98px) {
        .privacy-hero {
            padding: 72px 0 40px;
        }

        .privacy-content-card,
        .privacy-meta-card {
            border-radius: 22px;
            padding: 22px;
        }
    }
</style>

<section class="privacy-hero">
    <div class="container">
        <span class="privacy-kicker"><i class="fa-solid fa-shield-heart"></i> Privacy Policy</span>
        <h1 class="privacy-title">Your information matters, and we handle it with care.</h1>
        <p class="privacy-subtitle">
            This page explains how {{ $siteSettings->site_name }} collects, uses, protects, and manages information across admissions, inquiries, academic communication, and website interactions.
        </p>
    </div>
</section>

<section class="privacy-shell">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="privacy-meta-card">
                    <h3>Quick Overview</h3>
                    <ul class="privacy-meta-list">
                        <li>
                            <i class="fa-solid fa-building-columns"></i>
                            <div>
                                <strong>Institution</strong><br>
                                {{ $siteSettings->site_name }}
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <div>
                                <strong>Campus Address</strong><br>
                                {{ $siteSettings->contact_address ?: 'Contact address will be updated soon.' }}
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope-circle-check"></i>
                            <div>
                                <strong>Privacy Contact</strong><br>
                                {{ $siteSettings->contact_email ?: 'Email will be updated soon.' }}
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone-volume"></i>
                            <div>
                                <strong>Support Phone</strong><br>
                                {{ $siteSettings->contact_phone ?: 'Phone will be updated soon.' }}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="privacy-content-card">
                    <h3>Policy Details</h3>

                    <div class="privacy-body">
                        @if($privacy && filled($privacy->desc))
                            {!! $privacy->desc !!}
                        @else
                            <p>We respect the privacy of students, guardians, faculty, staff, and website visitors. Personal information shared through admissions, contact forms, or official communication is used only for legitimate academic and administrative purposes.</p>
                            <p>Information may be collected to respond to inquiries, process admissions, communicate notices, publish approved academic updates, and improve institutional services. We do not intentionally expose confidential data to unauthorized parties.</p>
                            <p>If you need clarification about what information is stored, how it is used, or how to request a correction, please contact the college administration using the details listed here.</p>
                        @endif
                    </div>

                    <div class="privacy-contact-box">
                        <p><strong>Need help with a privacy-related request?</strong></p>
                        <p>Email: <a href="mailto:{{ $siteSettings->contact_email }}">{{ $siteSettings->contact_email }}</a></p>
                        <p>Phone: <a href="tel:{{ $siteSettings->contact_phone }}">{{ $siteSettings->contact_phone }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
