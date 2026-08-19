@extends('backend.pages.layout.master')
@push('b-title', 'Edit Testimonial')

@push('styles')
<style>
    .testimonial-editor-hero {
        background:
            radial-gradient(circle at top right, rgba(82, 183, 136, 0.18), transparent 28%),
            linear-gradient(135deg, #f9fffb 0%, #f4f7ff 100%);
    }

    .testimonial-editor-kicker {
        display: inline-flex;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eaf7ef;
        color: #157347;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .testimonial-editor-title {
        font-size: 2rem;
        font-weight: 800;
        color: #132238;
    }

    .testimonial-form-card,
    .testimonial-side-card {
        border-radius: 24px;
        overflow: hidden;
    }

    .form-group-modern .form-label {
        font-weight: 700;
        color: #17324d;
        margin-bottom: 10px;
    }

    .form-group-modern .form-control {
        border-radius: 16px;
        border: 1px solid #dbe6f2;
        box-shadow: none;
        padding: 14px 16px;
    }

    .form-group-modern .form-control:focus {
        border-color: #7cb4ff;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.08);
    }

    .testimonial-textarea {
        min-height: 220px;
        resize: vertical;
    }

    .testimonial-side-title,
    .testimonial-guide-card h6 {
        font-weight: 800;
        color: #132238;
    }

    .testimonial-preview-card {
        border: 1px solid #e7edf5;
        border-radius: 22px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 14px 34px rgba(19, 34, 56, 0.07);
    }

    .testimonial-preview-image,
    .testimonial-preview-placeholder {
        width: 100%;
        height: 240px;
        object-fit: cover;
        display: block;
        background: linear-gradient(135deg, #eefbf3 0%, #edf4ff 100%);
    }

    .testimonial-preview-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 72px;
        color: #6c8aac;
    }

    .testimonial-preview-body {
        padding: 22px;
    }

    .testimonial-preview-body h6 {
        margin-bottom: 4px;
        font-size: 1.1rem;
        font-weight: 800;
        color: #132238;
    }

    .testimonial-preview-body span {
        display: inline-block;
        margin-bottom: 12px;
        color: #198754;
        font-weight: 700;
    }

    .testimonial-preview-body p {
        margin-bottom: 0;
        color: #4d657f;
        line-height: 1.75;
    }

    .testimonial-guide-card {
        padding: 20px;
        border-radius: 20px;
        background: linear-gradient(135deg, #f7fbff 0%, #f8fffb 100%);
        border: 1px solid #e5edf6;
    }

    .testimonial-guide-list {
        margin: 14px 0 0;
        padding-left: 18px;
        color: #48627e;
        line-height: 1.85;
    }
</style>
@endpush

@section('backend-content')
    <form action="{{ route('testimonial.update', $testimonial->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @include('backend.pages.testimonial.form')
    </form>
@endsection

