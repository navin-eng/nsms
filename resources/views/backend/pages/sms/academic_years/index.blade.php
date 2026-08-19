@extends('backend.pages.layout.master')
@push('b-title', 'Academic Years')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Academic Years</h3>
            <p class="text-muted mb-0">Manage school sessions and academic years. Current Calendar: <strong>{{ $calendarSystem === 'BS' ? 'Nepali (BS)' : 'English (AD)' }}</strong></p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg"></i> Add Academic Year
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Start Date ({{ $calendarSystem }})</th>
                            <th>End Date ({{ $calendarSystem }})</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($years as $year)
                            @php
                                $displayStart = $calendarSystem === 'BS' ? toBS(\Carbon\Carbon::parse($year->start_date)) : $year->start_date;
                                $displayEnd = $calendarSystem === 'BS' ? toBS(\Carbon\Carbon::parse($year->end_date)) : $year->end_date;
                            @endphp
                            <tr>
                                <td>{{ $year->name }}</td>
                                <td>{{ $displayStart }}</td>
                                <td>{{ $displayEnd }}</td>
                                <td>
                                    <form action="{{ route('sms.academic-years.active', $year->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" onChange="this.form.submit()" {{ $year->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $year->is_active ? 'Active' : 'Inactive' }}</label>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $year->id }}">Edit</button>
                                    <form action="{{ route('sms.academic-years.destroy', $year->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" {{ $year->is_active ? 'disabled' : '' }}>Delete</button>
                                    </form>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach($years as $year)
        @php
            $displayStart = $calendarSystem === 'BS' ? toBS(\Carbon\Carbon::parse($year->start_date)) : $year->start_date;
            $displayEnd = $calendarSystem === 'BS' ? toBS(\Carbon\Carbon::parse($year->end_date)) : $year->end_date;
        @endphp
        <div class="modal fade" id="editModal{{ $year->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('sms.academic-years.update', $year->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Academic Year</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $year->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Start Date ({{ $calendarSystem }})</label>
                                @if($calendarSystem === 'BS')
                                    <input type="text" name="start_date" class="form-control nepali-datepicker" value="{{ $displayStart }}" placeholder="YYYY-MM-DD" required pattern="\d{4}-\d{2}-\d{2}">
                                @else
                                    <input type="date" name="start_date" class="form-control" value="{{ $year->start_date }}" required>
                                @endif
                            </div>
                            <div class="mb-0">
                                <label class="form-label">End Date ({{ $calendarSystem }})</label>
                                @if($calendarSystem === 'BS')
                                    <input type="text" name="end_date" class="form-control nepali-datepicker" value="{{ $displayEnd }}" placeholder="YYYY-MM-DD" required pattern="\d{4}-\d{2}-\d{2}">
                                @else
                                    <input type="date" name="end_date" class="form-control" value="{{ $year->end_date }}" required>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sms.academic-years.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Academic Year</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name (e.g. 2026/27)</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date ({{ $calendarSystem }})</label>
                            @if($calendarSystem === 'BS')
                                <input type="text" name="start_date" class="form-control nepali-datepicker" placeholder="YYYY-MM-DD" required pattern="\d{4}-\d{2}-\d{2}">
                            @else
                                <input type="date" name="start_date" class="form-control" required>
                            @endif
                        </div>
                        <div class="mb-0">
                            <label class="form-label">End Date ({{ $calendarSystem }})</label>
                            @if($calendarSystem === 'BS')
                                <input type="text" name="end_date" class="form-control nepali-datepicker" placeholder="YYYY-MM-DD" required pattern="\d{4}-\d{2}-\d{2}">
                            @else
                                <input type="date" name="end_date" class="form-control" required>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Year</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@if($calendarSystem === 'BS')
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var nepaliDateInputs = document.querySelectorAll('.nepali-datepicker');
                nepaliDateInputs.forEach(function(input) {
                    input.nepaliDatePicker({
                        ndpYear: true,
                        ndpMonth: true,
                        ndpYearCount: 10
                    });
                });
            });
        </script>
        <style>
            #ndp-nepali-box {
                z-index: 99999 !important;
            }
        </style>
    @endpush
@endif
