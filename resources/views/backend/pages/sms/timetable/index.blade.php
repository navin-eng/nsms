@extends('backend.pages.layout.master')

@section('title', 'Timetable Management')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Manage Class Timetable</h4>
            <p class="text-muted mb-0">Assign subjects, teachers, and rooms to class periods.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('sms.timetable.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Academic Year <span class="text-danger">*</span></label>
                    <select name="academic_year_id" class="form-select" onchange="this.form.submit()" required>
                        <option value="">Select Year</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ $selectedYear == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Class <span class="text-danger">*</span></label>
                    <select name="academic_class_id" class="form-select" onchange="this.form.submit()" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Section</label>
                    <select name="section_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-class-ids="{{ $section->academicClasses->pluck('id')->join(',') }}" {{ $selectedSection == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-funnel"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedYear && $selectedClass)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if($periods->isNotEmpty())
                    <form method="POST" action="{{ route('sms.timetable.save') }}">
                        @csrf
                        <input type="hidden" name="academic_year_id" value="{{ $selectedYear }}">
                        <input type="hidden" name="academic_class_id" value="{{ $selectedClass }}">
                        <input type="hidden" name="section_id" value="{{ $selectedSection }}">

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0 text-center" style="min-width: 1200px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3" style="width: 150px;">Period</th>
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
                                                    <td class="p-2">
                                                        <div class="d-flex flex-column gap-1">
                                                            <select name="entries[{{ $key }}][subject_id]" class="form-select form-select-sm">
                                                                <option value="">- Subject -</option>
                                                                @foreach($subjects as $subject)
                                                                    <option value="{{ $subject->id }}" {{ ($entry->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                                                        {{ $subject->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            
                                                            <select name="entries[{{ $key }}][staff_id]" class="form-select form-select-sm">
                                                                <option value="">- Teacher -</option>
                                                                @foreach($teachers as $teacher)
                                                                    <option value="{{ $teacher->id }}" {{ ($entry->staff_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                                                        {{ $teacher->first_name }} {{ $teacher->last_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <input type="text" name="entries[{{ $key }}][room]" value="{{ $entry->room ?? '' }}" class="form-control form-control-sm text-center" placeholder="Room (opt)">
                                                        </div>
                                                    </td>
                                                @endforeach
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white text-end p-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save"></i> Save Timetable
                            </button>
                        </div>
                    </form>
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
            Please select an academic year and class to manage their timetable.
        </div>
    @endif
</div>
@endsection