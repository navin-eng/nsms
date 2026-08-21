@extends('backend.pages.layout.master')

@section('title', 'Staff Attendance')

@section('backend-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Staff Attendance</h4>
            <p class="text-muted mb-0">Mark daily attendance for staff members</p>
        </div>
        <div>
            <a href="{{ route('sms.staff-attendance.report') }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-text"></i> View Reports
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('sms.staff-attendance.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    @if($calendarSystem === 'BS')
                        <input type="text" name="date" class="form-control nepali-datepicker" value="{{ $selectedDateDisplay }}" pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" required>
                    @else
                        <input type="date" name="date" class="form-control" value="{{ $selectedDateDisplay }}" required>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <select name="department_id" class="form-select" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ $selectedDepartmentId == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
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
    @if($selectedDepartmentId)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    Staff List ({{ $calendarSystem === 'BS' ? $selectedDateDisplay : \Carbon\Carbon::parse($selectedDateAD)->format('D, d M Y') }})
                </h6>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-success mark-all" data-status="Present">Mark All Present</button>
                    <button type="button" class="btn btn-sm btn-outline-danger mark-all" data-status="Absent">Mark All Absent</button>
                </div>
            </div>
            
            <div class="card-body p-0">
                @if(count($staffMembers) > 0)
                    <form action="{{ route('sms.staff-attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{ $selectedDateAD }}">
                        <input type="hidden" name="department_id" value="{{ $selectedDepartmentId }}">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start ps-4" style="width: 25%;">Staff Member</th>
                                        <th style="width: 15%;">Designation</th>
                                        <th style="width: 35%;">Attendance Status</th>
                                        <th style="width: 25%;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($staffMembers as $staff)
                                        <tr>
                                            <td class="text-start ps-4">
                                                <div class="d-flex align-items-center">
                                                    @if($staff->image)
                                                        <img src="{{ asset('storage/' . $staff->image) }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light text-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-person"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $staff->first_name }} {{ $staff->last_name }}</h6>
                                                        <small class="text-muted">{{ $staff->phone }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $staff->designation->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group w-100 attendance-status-group" role="group">
                                                    <input type="radio" class="btn-check" name="attendance[{{ $staff->id }}][status]" id="status_p_{{ $staff->id }}" value="Present" {{ $staff->attendance_status == 'Present' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-success" for="status_p_{{ $staff->id }}">Present</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $staff->id }}][status]" id="status_a_{{ $staff->id }}" value="Absent" {{ $staff->attendance_status == 'Absent' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-danger" for="status_a_{{ $staff->id }}">Absent</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $staff->id }}][status]" id="status_l_{{ $staff->id }}" value="Late" {{ $staff->attendance_status == 'Late' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-warning" for="status_l_{{ $staff->id }}">Late</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $staff->id }}][status]" id="status_h_{{ $staff->id }}" value="Half-Day" {{ $staff->attendance_status == 'Half-Day' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-info" for="status_h_{{ $staff->id }}">Half-Day</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="attendance[{{ $staff->id }}][remarks]" class="form-control form-control-sm" placeholder="Note (optional)" value="{{ $staff->attendance_remarks }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="card-footer bg-light p-3 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save"></i> Save Attendance
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-3 opacity-50"></i>
                        <h5>No Staff Found</h5>
                        <p>No active staff members found in this department.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-arrow-up-circle fs-1 d-block mb-3 opacity-50"></i>
                <h5>Select Department</h5>
                <p>Please select a department to mark attendance.</p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark All buttons
        document.querySelectorAll('.mark-all').forEach(button => {
            button.addEventListener('click', function() {
                const status = this.getAttribute('data-status');
                document.querySelectorAll('.attendance-status-group').forEach(group => {
                    const targetRadio = group.querySelector(`input[value="${status}"]`);
                    if (targetRadio) {
                        targetRadio.checked = true;
                    }
                });
            });
        });

        // Initialize Nepali Datepicker if BS calendar is active
        if (typeof jQuery !== 'undefined') {
            var nepaliDateInput = document.querySelector('.nepali-datepicker');
            if (nepaliDateInput) {
                $(nepaliDateInput).nepaliDatePicker({
                    dateFormat: "YYYY-MM-DD",
                    closeOnDateSelect: true
                });
            }
        }
    });
</script>
@endpush
