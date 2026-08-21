@extends('backend.pages.layout.master')

@section('title', 'Mark Daily Attendance')

@section('backend-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Mark Daily Attendance</h4>
            <p class="text-muted mb-0">Record and update daily student attendance</p>
        </div>
        <div>
            <a href="{{ route('sms.attendance.report') }}" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-bar-graph"></i> View Reports
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('sms.attendance.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Attendance Date <span class="text-danger">*</span></label>
                    @if($calendarSystem === 'BS')
                        <input type="text" name="date" class="form-control nepali-datepicker" value="{{ $selectedDateDisplay }}" required pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                    @else
                        <input type="date" name="date" class="form-control" value="{{ $selectedDateDisplay }}" required max="{{ date('Y-m-d') }}">
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Class <span class="text-danger">*</span></label>
                    <select name="academic_class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Section <span class="text-danger">*</span></label>
                    <select name="section_id" class="form-select" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-class-ids="{{ $section->academicClasses->pluck('id')->join(',') }}" {{ $selectedSectionId == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Form -->
    @if($selectedClassId && $selectedSectionId)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    Students List ({{ $calendarSystem === 'BS' ? $selectedDateDisplay : \Carbon\Carbon::parse($selectedDateAD)->format('D, d M Y') }})
                </h6>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-success mark-all" data-status="Present">Mark All Present</button>
                    <button type="button" class="btn btn-sm btn-outline-danger mark-all" data-status="Absent">Mark All Absent</button>
                </div>
            </div>
            
            <div class="card-body p-0">
                @if(count($students) > 0)
                    <form action="{{ route('sms.attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{ $selectedDateAD }}">
                        <input type="hidden" name="academic_class_id" value="{{ $selectedClassId }}">
                        <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Roll No</th>
                                        <th>Student Name</th>
                                        <th>Admission No</th>
                                        <th style="min-width: 300px;">Attendance Status</th>
                                        <th>Remarks (Optional)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students->sortBy('roll_no') as $enrollment)
                                        <tr>
                                            <td class="ps-4 fw-semibold">{{ $enrollment->roll_no ?? '-' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($enrollment->student->photo)
                                                        <img src="{{ asset('storage/' . $enrollment->student->photo) }}" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 text-primary fw-bold" style="width: 35px; height: 35px;">
                                                            {{ substr($enrollment->student->first_name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    {{ $enrollment->student->full_name }}
                                                </div>
                                            </td>
                                            <td>{{ $enrollment->student->admission_no }}</td>
                                            <td>
                                                <div class="btn-group w-100 attendance-options" role="group">
                                                    <input type="radio" class="btn-check" name="attendance[{{ $enrollment->student_id }}][status]" id="p_{{ $enrollment->student_id }}" value="Present" {{ $enrollment->attendance_status == 'Present' ? 'checked' : '' }} required>
                                                    <label class="btn btn-outline-success" for="p_{{ $enrollment->student_id }}">Present</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $enrollment->student_id }}][status]" id="a_{{ $enrollment->student_id }}" value="Absent" {{ $enrollment->attendance_status == 'Absent' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-danger" for="a_{{ $enrollment->student_id }}">Absent</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $enrollment->student_id }}][status]" id="l_{{ $enrollment->student_id }}" value="Late" {{ $enrollment->attendance_status == 'Late' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-warning" for="l_{{ $enrollment->student_id }}">Late</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $enrollment->student_id }}][status]" id="h_{{ $enrollment->student_id }}" value="Half-Day" {{ $enrollment->attendance_status == 'Half-Day' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-info" for="h_{{ $enrollment->student_id }}">Half-Day</label>
                                                </div>
                                            </td>
                                            <td class="pe-4">
                                                <input type="text" name="attendance[{{ $enrollment->student_id }}][remarks]" class="form-control form-control-sm" placeholder="Reason..." value="{{ $enrollment->attendance_remarks }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="card-footer bg-light py-3 px-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-save"></i> Save Attendance
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-3 opacity-50"></i>
                        <h5>No Students Found</h5>
                        <p>There are no active students in the selected class and section.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-calendar-check fs-1 d-block mb-3 opacity-50"></i>
                <h5>Select Class and Section</h5>
                <p>Please use the filter above to load the student list and mark attendance.</p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle "Mark All" buttons
        const markAllBtns = document.querySelectorAll('.mark-all');
        
        markAllBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetStatus = this.getAttribute('data-status');
                const radios = document.querySelectorAll(`input[type="radio"][value="${targetStatus}"]`);
                
                radios.forEach(radio => {
                    radio.checked = true;
                });
            });
        });

        if (typeof jQuery !== 'undefined') {
            var nepaliDateInputs = document.querySelectorAll('.nepali-datepicker');
            if (nepaliDateInputs.length > 0) {
                nepaliDateInputs.forEach(function(input) {
                    input.nepaliDatePicker({
                        dateFormat: "YYYY-MM-DD",
                        closeOnDateSelect: true
                    });
                });
            }
        }
    });
</script>
@endpush
