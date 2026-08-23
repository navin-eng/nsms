@extends('backend.pages.layout.master')
@push('b-title', 'Events & Activities')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-start mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-0">Events & Activities</h2>
            <p class="text-muted small mb-0">Manage school events, registrations, and attendance.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-sm btn-light border" target="_blank">
                <i class="bi bi-file-earmark-pdf text-danger me-1"></i> PDF
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'print']) }}" class="btn btn-sm btn-light border" target="_blank">
                <i class="bi bi-printer text-secondary me-1"></i> Print
            </a>
            <a href="{{ route('sms.events.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> New Event
            </a>
        </div>
    </div>

    {{-- Stats --}}
    @php
        use App\Models\Event;
        $totalEvents     = Event::count();
        $upcomingCount   = Event::where('visit_date', '>=', $today)->count();
        $pastCount       = Event::where('visit_date', '<', $today)->count();
        $totalRegs       = \App\Models\EventParticipant::whereIn('status', ['registered','attended'])->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Total Events</div>
                    <div class="fs-4 fw-bold text-dark">{{ $totalEvents }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Upcoming</div>
                    <div class="fs-4 fw-bold" style="color:#2563eb">{{ $upcomingCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Past Events</div>
                    <div class="fs-4 fw-bold" style="color:#6b7280">{{ $pastCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Total Registrations</div>
                    <div class="fs-4 fw-bold" style="color:#16a34a">{{ $totalRegs }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('sms.events.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Search by name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach(['sports' => 'Sports', 'cultural' => 'Cultural', 'academic' => 'Academic', 'seminar' => 'Seminar', 'workshop' => 'Workshop', 'health' => 'Health & Wellness', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" {{ request('category') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Events</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-sm btn-primary w-100">Filter</button>
                    <a href="{{ route('sms.events.index') }}" class="btn btn-sm btn-light border">Clear</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Events Grid --}}
    @if($events->count())
    <div class="row g-3">
        @foreach($events as $event)
        @php
            $isPast     = $event->visit_date < now()->toDateString();
            $isUpcoming = !$isPast;
            $catColors  = ['sports' => '#2563eb', 'cultural' => '#7c3aed', 'academic' => '#059669', 'seminar' => '#d97706', 'workshop' => '#0891b2', 'health' => '#dc2626', 'other' => '#6b7280'];
            $catColor   = $catColors[$event->category] ?? '#6b7280';
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100" style="overflow:hidden">
                {{-- Color bar --}}
                <div style="height:4px;background:{{ $catColor }}"></div>
                <div class="card-body p-3">
                    {{-- Category & Status --}}
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge rounded-pill px-2 py-1"
                              style="font-size:11px;background:{{ $catColor }}20;color:{{ $catColor }};border:1px solid {{ $catColor }}40">
                            {{ $event->category_label }}
                        </span>
                        @if($isPast)
                            <span class="badge rounded-pill px-2 py-1"
                                  style="font-size:11px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb">Past</span>
                        @else
                            <span class="badge rounded-pill px-2 py-1"
                                  style="font-size:11px;background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe">Upcoming</span>
                        @endif
                        @if($event->registration_open)
                            <span class="badge rounded-pill px-2 py-1 ms-auto"
                                  style="font-size:11px;background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0">
                                <i class="bi bi-door-open me-1"></i>Open
                            </span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h6 class="fw-bold text-dark mb-1" style="line-height:1.3">{{ $event->name }}</h6>

                    {{-- Meta --}}
                    <div class="text-muted small mb-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $event->visit_date->format('d M Y') }}
                        @if($event->start_time) · {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} @endif
                    </div>
                    @if($event->venue)
                    <div class="text-muted small mb-2">
                        <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }}
                    </div>
                    @endif

                    {{-- Participants count --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-people text-muted small"></i>
                        <span class="small text-muted">{{ $event->total_participants }} registered</span>
                        @if($event->max_participants)
                            <span class="small text-muted">/ {{ $event->max_participants }} max</span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('sms.events.show', $event) }}" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="bi bi-eye me-1"></i>View
                        </a>
                        <a href="{{ route('sms.events.edit', $event) }}" class="btn btn-sm btn-light border">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('sms.events.destroy', $event) }}"
                              onsubmit="return confirm('Delete this event?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light border text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
    @else
    <div class="text-center py-5 text-muted">
        <i class="bi bi-calendar-x fs-1 d-block mb-3" style="opacity:.2"></i>
        <div class="fw-semibold">No Events Found</div>
        <div class="small mb-3">Create your first event to get started.</div>
        <a href="{{ route('sms.events.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New Event
        </a>
    </div>
    @endif

</div>
@endsection
