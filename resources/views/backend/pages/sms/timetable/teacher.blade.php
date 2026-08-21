@extends('backend.pages.layout.master')

@section('title', 'Teacher Timetable')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Teacher Timetable</h4>
            <p class="text-muted mb-0">View timetable for a specific teacher.</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('sms.timetable.teacher') }}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-bold">Academic Year <span class="text-danger">*</span></label>
                    <select name="academic_year_id" class="form-select" required>
                        <option value="">Select Academic Year</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ $selectedYear == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold">Teacher <span class="text-danger">*</span></label>
                    <select name="staff_id" class="form-select" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $selectedTeacher == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->employee_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> View
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedTeacher && $selectedYear)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if($periods->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">Period</th>
                                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                        <th class="py-3">{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($periods as $period)
                                    <tr class="{{ $period->is_break ? 'table-warning' : '' }}">
                                        <td class="fw-bold bg-light">
                                            {{ $period->name }}<br>
                                            <small class="text-muted fw-normal">{{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}</small>
                                        </td>
                                        @if($period->is_break)
                                            <td colspan="7" class="text-muted fw-bold">BREAK</td>
                                        @else
                                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                                @php
                                                    $key = $period->id . '_' . $day;
                                                    $entry = $entries->get($key);
                                                @endphp
                                                <td style="min-width: 150px; height: 80px;">
                                                    @if($entry)
                                                        <div class="p-2 border rounded bg-white shadow-sm h-100 d-flex flex-column justify-content-center">
                                                            <strong class="text-primary">{{ $entry->subject->name ?? 'N/A' }}</strong>
                                                            <div class="text-muted small mt-1">
                                                                Class: {{ $entry->academicClass->name ?? '' }} 
                                                                @if($entry->section) ({{ $entry->section->name }}) @endif
                                                            </div>
                                                            @if($entry->room)
                                                                <div class="text-muted small"><i class="bi bi-door-open"></i> Room: {{ $entry->room }}</div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center">
                        <p class="text-muted mb-0">No periods defined yet. <a href="{{ route('sms.periods.index') }}">Add a period</a></p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-info border-0 shadow-sm text-center p-4">
            <i class="bi bi-info-circle-fill fs-4 d-block mb-2 text-primary"></i>
            Please select an academic year and a teacher to view their timetable.
        </div>
    @endif
</div>
@endsection
