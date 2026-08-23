@extends('backend.pages.layout.master')
@push('b-title', 'Hostel Attendance')

@section('backend-content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 text-dark mb-0"><i class="bi bi-calendar-check me-2"></i>Hostel Attendance</h2>
            <p class="text-muted mb-0">Mark daily attendance for students currently staying in hostels.</p>
        </div>
        @if($selectedHostelId && $attendances->count() > 0)
        <div class="d-flex gap-2">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-outline-danger" target="_blank" title="Export PDF">
                <i class="bi bi-file-earmark-pdf"></i>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-outline-success" title="Export Excel">
                <i class="bi bi-file-earmark-excel"></i>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'print']) }}" class="btn btn-outline-secondary" target="_blank" title="Print">
                <i class="bi bi-printer"></i>
            </a>
        </div>
        @endif
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('sms.hostel.attendance.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label text-muted fw-semibold">Select Hostel</label>
                    <select name="hostel_id" class="form-select border-0 bg-light" required>
                        <option value="">-- Select Hostel --</option>
                        @foreach($hostels as $hostel)
                            <option value="{{ $hostel->id }}" {{ $selectedHostelId == $hostel->id ? 'selected' : '' }}>
                                {{ $hostel->name }} ({{ $hostel->type }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label text-muted fw-semibold">Date</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-calendar"></i></span>
                        <input type="text" name="date" class="form-control border-0 bg-light {{ $calendarService->system() === 'BS' ? 'nepali-datepicker' : '' }}" 
                               value="{{ $selectedDateDisplay }}" 
                               {{ $calendarService->system() === 'AD' ? 'type=date' : 'placeholder=YYYY-MM-DD' }} required>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Load
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Grid -->
    @if($selectedHostelId)
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0 fw-bold">Attendance Sheet</h5>
            </div>
            <div class="card-body p-0">
                @if($allocations->count() > 0)
                    <form action="{{ route('sms.hostel.attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{ $selectedDateAD }}">
                        <input type="hidden" name="hostel_id" value="{{ $selectedHostelId }}">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Student</th>
                                        <th class="py-3">Room & Bed</th>
                                        <th class="py-3" style="min-width: 300px;">Attendance Status</th>
                                        <th class="py-3 text-end px-4">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allocations as $allocation)
                                        @php
                                            $attendance = $attendances->get($allocation->id);
                                            $status = $attendance ? $attendance->status : 'Present'; // Default to Present
                                            $remarks = $attendance ? $attendance->remarks : '';
                                        @endphp
                                        <tr>
                                            <td class="px-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        @if($allocation->student->photo)
                                                            <img src="{{ asset('storage/'.$allocation->student->photo) }}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                                        @else
                                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 40px; height: 40px;">
                                                                {{ substr($allocation->student->first_name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <span class="fw-medium text-dark">{{ $allocation->student->first_name }} {{ $allocation->student->last_name }}</span>
                                                        <div class="small text-muted">{{ $allocation->student->student_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary rounded-pill me-1">R: {{ $allocation->bed->room->room_number }}</span>
                                                <span class="badge bg-dark rounded-pill">B: {{ $allocation->bed->bed_number }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check form-check-inline form-radio-success">
                                                        <input class="form-check-input" type="radio" name="attendance[{{ $allocation->id }}][status]" id="present_{{ $allocation->id }}" value="Present" {{ $status == 'Present' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="present_{{ $allocation->id }}">Present</label>
                                                    </div>
                                                    <div class="form-check form-check-inline form-radio-danger">
                                                        <input class="form-check-input" type="radio" name="attendance[{{ $allocation->id }}][status]" id="absent_{{ $allocation->id }}" value="Absent" {{ $status == 'Absent' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="absent_{{ $allocation->id }}">Absent</label>
                                                    </div>
                                                    <div class="form-check form-check-inline form-radio-warning">
                                                        <input class="form-check-input" type="radio" name="attendance[{{ $allocation->id }}][status]" id="leave_{{ $allocation->id }}" value="Leave" {{ $status == 'Leave' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="leave_{{ $allocation->id }}">Leave</label>
                                                    </div>
                                                    <div class="form-check form-check-inline form-radio-info">
                                                        <input class="form-check-input" type="radio" name="attendance[{{ $allocation->id }}][status]" id="late_{{ $allocation->id }}" value="Late" {{ $status == 'Late' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="late_{{ $allocation->id }}">Late In</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4">
                                                <input type="text" name="attendance[{{ $allocation->id }}][remarks]" class="form-control form-control-sm bg-light border-0" placeholder="Optional remarks" value="{{ $remarks }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-light border-0 p-4 text-end">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-save me-2"></i> Save Attendance
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-x fs-1 text-muted opacity-50 mb-3 d-block"></i>
                        <h5 class="text-muted">No Students Found</h5>
                        <p class="text-muted mb-0">There are no active student allocations for this hostel on the selected date.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Initial empty state -->
        <div class="card border-0 shadow-sm rounded-4 bg-transparent border-dashed">
            <div class="card-body text-center py-5">
                <i class="bi bi-search fs-1 text-muted opacity-50 mb-3 d-block"></i>
                <h5 class="text-muted">Select a Hostel</h5>
                <p class="text-muted mb-0">Please select a hostel and date from the filters above to load the attendance sheet.</p>
            </div>
        </div>
    @endif
</div>

<style>
    /* Custom radio button colors for attendance statuses */
    .form-radio-success .form-check-input:checked { background-color: var(--bs-success); border-color: var(--bs-success); }
    .form-radio-danger .form-check-input:checked { background-color: var(--bs-danger); border-color: var(--bs-danger); }
    .form-radio-warning .form-check-input:checked { background-color: var(--bs-warning); border-color: var(--bs-warning); }
    .form-radio-info .form-check-input:checked { background-color: var(--bs-info); border-color: var(--bs-info); }
    .border-dashed { border: 2px dashed #dee2e6 !important; }
</style>
@endsection

@push('scripts')
    @if($calendarService->system() === 'BS')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof jQuery !== 'undefined') {
                    $('.nepali-datepicker').nepaliDatePicker({
                        dateFormat: "YYYY-MM-DD",
                        closeOnDateSelect: true
                    });
                }
            });
        </script>
    @endif
@endpush
