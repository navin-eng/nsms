@extends('frontend.layout.master')
@section('frontend-content')
<style>
    .page-hero {
        background: linear-gradient(135deg, #1a472a 0%, #2d6a4f 60%, #40916c 100%);
        padding: 70px 0 50px;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 60%);
    }
    .page-hero h1 {
        font-size: 2.4rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin: 0;
        position: relative;
    }
    .breadcrumb-bar {
        background: #f8f9fa;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .breadcrumb-bar a { color: #2d6a4f; text-decoration: none; }
    .breadcrumb-bar a:hover { text-decoration: underline; }
    .custom-page-content {
        max-width: 900px;
        margin: 50px auto;
        padding: 0 20px 60px;
        font-size: 16px;
        line-height: 1.8;
        color: #333;
    }
    .custom-page-content h1, .custom-page-content h2, .custom-page-content h3 {
        color: #1a472a;
        margin-top: 1.4em;
        margin-bottom: 0.5em;
    }
    .custom-page-content img {
        max-width: 100%;
        border-radius: 8px;
        margin: 12px 0;
    }
    .custom-page-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 16px 0;
    }
    .custom-page-content table th,
    .custom-page-content table td {
        border: 1px solid #dee2e6;
        padding: 10px 14px;
        text-align: left;
    }
    .custom-page-content table th { background: #f0f4f2; font-weight: 600; }
    .custom-page-content ul, .custom-page-content ol { padding-left: 22px; }
    .custom-page-content a { color: #2d6a4f; }
    .custom-page-content blockquote {
        border-left: 4px solid #40916c;
        background: #f0faf4;
        padding: 14px 20px;
        margin: 16px 0;
        border-radius: 0 6px 6px 0;
        font-style: italic;
        color: #555;
    }
    .custom-page-content hr { border-color: #dee2e6; margin: 28px 0; }
</style>

<div class="page-hero">
    <h1>{{ $page->title }}</h1>
</div>

<div class="breadcrumb-bar">
    <div class="container">
        <small>
            <a href="{{ route('home') }}">Home</a> &rsaquo; <span>{{ $page->title }}</span>
        </small>
    </div>
</div>

<div class="custom-page-content">
    {!! $page->content !!}
</div>

@endsection
