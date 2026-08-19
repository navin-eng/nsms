@extends('backend.pages.layout.master')
@push('b-title', 'Campus Calendar')

@section('backend-content')
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

    <style>
        .calendar-admin-shell {
            display: grid;
            grid-template-columns: 1.4fr .9fr;
            gap: 20px;
        }
        .calendar-admin-card {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(148,163,184,.14);
            box-shadow: 0 14px 36px rgba(15,23,42,.06);
        }
        .calendar-admin-card h4 {
            font-weight: 800;
            margin-bottom: 8px;
        }
        .calendar-help {
            color: #64748b;
            margin-bottom: 18px;
        }
        .calendar-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        @media (max-width: 991px) {
            .calendar-admin-shell {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="calendar-admin-shell">
        <div class="calendar-admin-card">
            <h4>Campus Calendar Manager</h4>
            <p class="calendar-help">Click any date on the calendar to add an item. Use `end date` for long holidays like festival breaks.</p>
            <div class="calendar-actions">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#calendarEntryModal">Add Entry</button>
                <a href="{{ route('calendar') }}" target="_blank" class="btn btn-outline-primary">Open Public Calendar</a>
            </div>
            <div id="adminCampusCalendar"></div>
        </div>

        <div class="calendar-admin-card">
            <h4>Calendar Entries</h4>
            <p class="calendar-help">Manage holidays, exams, tests, CCA/ECA, results, and other date-based items.</p>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                            <tr>
                                <td>
                                    <strong>{{ $entry->title }}</strong>
                                    @if($entry->description)
                                        <div class="text-muted small">{{ \Illuminate\Support\Str::limit($entry->description, 60) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background: {{ $typeColors[$entry->entry_type] ?? '#2d6a4f' }};">
                                        {{ $entry->entry_type_label }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Illuminate\Support\Carbon::parse($entry->start_date)->format('d M Y') }}
                                    @if($entry->end_date && $entry->end_date !== $entry->start_date)
                                        <div class="text-muted small">to {{ \Illuminate\Support\Carbon::parse($entry->end_date)->format('d M Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('campus.calendar.status', $entry->id) }}" class="btn btn-sm {{ $entry->status ? 'btn-outline-success' : 'btn-outline-secondary' }}">
                                        {{ $entry->status ? 'Visible' : 'Hidden' }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-info btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCalendarEntryModal{{ $entry->id }}">
                                            Edit
                                        </button>
                                        <a href="{{ route('campus.calendar.destroy', $entry->id) }}" class="btn btn-danger btn-sm deleteBtn" data-href="{{ route('campus.calendar.destroy', $entry->id) }}">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No campus calendar entries added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="calendarEntryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('campus.calendar.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Calendar Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('backend.pages.calendar.partials.form-fields', ['entry' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($entries as $entry)
        <div class="modal fade" id="editCalendarEntryModal{{ $entry->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('campus.calendar.update', $entry->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Calendar Entry</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @include('backend.pages.calendar.partials.form-fields', ['entry' => $entry])
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('adminCampusCalendar');
                if (!calendarEl) return;

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
                    height: 'auto',
                    selectable: true,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,listMonth'
                    },
                    dateClick: function(info) {
                        const startInput = document.querySelector('#calendarEntryModal input[name="start_date"]');
                        const endInput = document.querySelector('#calendarEntryModal input[name="end_date"]');
                        if (startInput) startInput.value = info.dateStr;
                        if (endInput) endInput.value = info.dateStr;
                        const modal = new bootstrap.Modal(document.getElementById('calendarEntryModal'));
                        modal.show();
                    },
                    events: [
                        @foreach($entries as $entry)
                        {
                            title: @json($entry->title),
                            start: @json($entry->start_date),
                            end: @json($entry->end_date ? \Illuminate\Support\Carbon::parse($entry->end_date)->addDay()->toDateString() : null),
                            color: @json($typeColors[$entry->entry_type] ?? '#2d6a4f')
                        },
                        @endforeach
                    ]
                });

                calendar.render();
            });
        </script>
    @endpush
@endsection
