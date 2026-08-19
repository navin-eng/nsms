@extends('frontend.layout.master')
@section('frontend-content')

@php
    $typeColors = [
        'holiday' => '#dc2626',
        'exam' => '#7c3aed',
        'test' => '#ea580c',
        'cca_eca' => '#0891b2',
        'result' => '#ca8a04',
        'other' => '#2d6a4f',
    ];
@endphp

<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>Campus Calendar</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Campus Calendar
            </nav>
        </div>
    </div>
</div>

<section class="section-block">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-5" data-aos="fade-up">
            <div>
                <span class="section-tag">Academic Planner</span>
                <h2 class="section-title mt-2 mb-0">Holidays, Exams, Tests, Activities, and Results</h2>
            </div>
            <a href="{{ route('events.index') }}" class="btn-gplc">
                <i class="fa-solid fa-calendar-week"></i> View Events Page
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="calendar-shell">
                    <div id="campusCalendar"></div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="calendar-sidebar">
                    <h4>Calendar Guide</h4>
                    <div class="calendar-legend">
                        <div><span style="background:#dc2626;"></span> Holiday</div>
                        <div><span style="background:#7c3aed;"></span> Exam</div>
                        <div><span style="background:#ea580c;"></span> Test</div>
                        <div><span style="background:#0891b2;"></span> CCA / ECA</div>
                        <div><span style="background:#ca8a04;"></span> Result</div>
                        <div><span style="background:#2d6a4f;"></span> Other</div>
                    </div>
                    <hr>
                    <h5 class="mb-3">Upcoming Calendar Items</h5>
                    @forelse($entries->sortBy('start_date')->take(8) as $entry)
                        <div class="calendar-item-card">
                            <div class="calendar-item-badge" style="background: {{ $typeColors[$entry->entry_type] ?? '#2d6a4f' }};">
                                {{ $entry->entry_type_label }}
                            </div>
                            <h6>{{ $entry->title }}</h6>
                            <p>
                                {{ \Illuminate\Support\Carbon::parse($entry->start_date)->format('d M Y') }}
                                @if($entry->end_date && $entry->end_date !== $entry->start_date)
                                    - {{ \Illuminate\Support\Carbon::parse($entry->end_date)->format('d M Y') }}
                                @endif
                            </p>
                            @if($entry->description)
                                <small>{{ \Illuminate\Support\Str::limit($entry->description, 85) }}</small>
                            @endif
                            @if($entry->result_link)
                                <div class="mt-2">
                                    <a href="{{ $entry->result_link }}" target="_blank" class="btn btn-sm btn-outline-warning">View Result</a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">No calendar entries available yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    <style>
        .calendar-shell,
        .calendar-sidebar {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow);
        }
        .calendar-legend {
            display: grid;
            gap: 10px;
        }
        .calendar-legend div {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }
        .calendar-legend span {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-block;
        }
        .calendar-item-card {
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 14px;
        }
        .calendar-item-badge {
            color: #fff;
            display: inline-flex;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .fc .fc-toolbar-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary-dark);
        }
        .fc .fc-button-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        .fc .fc-button-primary:hover,
        .fc .fc-button-primary:focus {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        @media (max-width: 768px) {
            .calendar-shell,
            .calendar-sidebar {
                padding: 16px;
            }
            .fc .fc-toolbar {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('campusCalendar');
            if (!calendarEl) return;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                events: [
                    @foreach($entries as $entry)
                    {
                        title: @json($entry->title),
                        start: @json($entry->start_date),
                        end: @json($entry->end_date ? \Illuminate\Support\Carbon::parse($entry->end_date)->addDay()->toDateString() : null),
                        color: @json($typeColors[$entry->entry_type] ?? '#2d6a4f'),
                    },
                    @endforeach
                ]
            });

            calendar.render();
        });
    </script>
@endpush

@endsection
