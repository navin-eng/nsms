@extends('frontend.layout.master')
@section('frontend-content')

<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>Campus Events</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Events
            </nav>
        </div>
    </div>
</div>

<section class="section-block">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-5" data-aos="fade-up">
            <div>
                <span class="section-tag">Campus Life</span>
                <h2 class="section-title mt-2 mb-0">Dedicated Events Page</h2>
            </div>
            <a href="{{ route('calendar') }}" class="btn-gplc">
                <i class="fa-solid fa-calendar-days"></i> Open Campus Calendar
            </a>
        </div>

        @if($events->count())
            <div class="event-stack-grid">
                @foreach($events as $event)
                    <a href="{{ url('event/' . $event->slug) }}" class="event-stack-tile" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="event-stack-media">
                            <img src="{{ asset($event->image) }}" alt="{{ $event->name }}">
                            <span class="event-stack-type">{{ $event->event_type_label ?? 'Event' }}</span>
                        </div>
                        <div class="event-stack-body">
                            <div class="event-stack-date">
                                <i class="fa-regular fa-calendar"></i> {{ $event->visit_date }}
                            </div>
                            <h5>{{ $event->name }}</h5>
                            <p>{{ \Illuminate\Support\Str::words(strip_tags($event->description), 24) }}</p>
                            <span class="event-stack-link">View Details <i class="fa-solid fa-arrow-right"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fa-solid fa-calendar-xmark fa-3x mb-3" style="color: var(--green-light);"></i>
                <h5 style="color: var(--dark);">No events available right now.</h5>
                <p class="text-muted">Please check the campus calendar for upcoming academic dates and notices.</p>
            </div>
        @endif
    </div>
</section>

@push('styles')
    <style>
        .event-stack-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .event-stack-tile {
            display: block;
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            box-shadow: var(--shadow);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .event-stack-tile:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
        }
        .event-stack-media {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        .event-stack-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .event-stack-type {
            position: absolute;
            left: 16px;
            bottom: 16px;
            background: rgba(15, 23, 42, 0.74);
            color: #fff;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }
        .event-stack-body {
            padding: 20px;
        }
        .event-stack-date {
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .event-stack-body h5 {
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 12px;
        }
        .event-stack-body p {
            color: var(--text-muted);
            margin-bottom: 16px;
            line-height: 1.7;
        }
        .event-stack-link {
            font-weight: 700;
            color: var(--primary-dark);
        }
    </style>
@endpush

@endsection
