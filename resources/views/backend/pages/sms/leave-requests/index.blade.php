@extends('backend.pages.layout.master')

@section('title', 'Leave Requests')

@section('backend-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Leave Requests</h4>
            <p class="text-muted mb-0">Manage leave applications for students and staff</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeaveModal">
                <i class="bi bi-plus-circle"></i> Add Leave Request
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('sms.leave-requests.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="Pending" {{ $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">User Type</label>
                    <select name="user_type" class="form-select">
                        <option value="all" {{ $userType == 'all' ? 'selected' : '' }}>All Users</option>
                        <option value="student" {{ $userType == 'student' ? 'selected' : '' }}>Students Only</option>
                        <option value="staff" {{ $userType == 'staff' ? 'selected' : '' }}>Staff Only</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($leaveRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Applicant</th>
                                <th>Role</th>
                                <th>Date Range</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveRequests as $leave)
                                <tr>
                                    <td class="ps-4 fw-semibold">
                                        {{ $leave->leavable->full_name ?? 'Unknown User' }}
                                    </td>
                                    <td>
                                        @if($leave->leavable_type === 'App\Models\Student')
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info">Student</span>
                                        @else
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">Staff</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $sDate = $calendarSystem === 'BS' ? \Pratiksh\Nepalidate\Services\NepaliDate::create(\Carbon\Carbon::parse($leave->start_date))->toBS() : \Carbon\Carbon::parse($leave->start_date)->format('M d, Y');
                                            $eDate = $calendarSystem === 'BS' ? \Pratiksh\Nepalidate\Services\NepaliDate::create(\Carbon\Carbon::parse($leave->end_date))->toBS() : \Carbon\Carbon::parse($leave->end_date)->format('M d, Y');
                                        @endphp
                                        <div>{{ $sDate }}</div>
                                        <div class="small text-muted">to {{ $eDate }}</div>
                                    </td>
                                    <td style="max-width: 200px;" class="text-truncate" title="{{ $leave->reason }}">
                                        {{ $leave->reason }}
                                    </td>
                                    <td>
                                        @if($leave->status === 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($leave->status === 'Approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewLeaveModal{{ $leave->id }}" title="View / Update">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <form action="{{ route('sms.leave-requests.destroy', $leave->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this leave request?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                
                                <!-- View/Update Modal -->
                                <div class="modal fade" id="viewLeaveModal{{ $leave->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('sms.leave-requests.update', $leave->id) }}" method="POST" class="modal-content">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Leave Request Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <strong>Applicant:</strong> {{ $leave->leavable->full_name ?? 'Unknown User' }}
                                                    ({{ $leave->leavable_type === 'App\Models\Student' ? 'Student' : 'Staff' }})
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Date Range:</strong> {{ $sDate }} to {{ $eDate }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Reason:</strong>
                                                    <div class="p-2 bg-light border rounded mt-1">{{ $leave->reason }}</div>
                                                </div>
                                                <hr>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Action</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Pending" {{ $leave->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="Approved" {{ $leave->status == 'Approved' ? 'selected' : '' }}>Approve</option>
                                                        <option value="Rejected" {{ $leave->status == 'Rejected' ? 'selected' : '' }}>Reject</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Admin Remarks (Optional)</label>
                                                    <textarea name="remarks" class="form-control" rows="2" placeholder="Note to applicant...">{{ $leave->remarks }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $leaveRequests->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-1 d-block mb-3 opacity-50"></i>
                    <h5>No Leave Requests Found</h5>
                    <p>No leave requests match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Leave Request Modal -->
<div class="modal fade" id="addLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('sms.leave-requests.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Manual Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">User Type <span class="text-danger">*</span></label>
                    <select name="user_type" id="modalUserType" class="form-select" required>
                        <option value="">Select Type...</option>
                        <option value="student">Student</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                
                <div class="mb-3 d-none" id="studentSelectWrapper">
                    <label class="form-label">Select Student <span class="text-danger">*</span></label>
                    <select name="user_id" id="studentSelect" class="form-select">
                        <option value="">Select Student...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->full_name }} (ID: {{ $student->admission_no }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3 d-none" id="staffSelectWrapper">
                    <label class="form-label">Select Staff <span class="text-danger">*</span></label>
                    <select name="user_id" id="staffSelect" class="form-select">
                        <option value="">Select Staff...</option>
                        @foreach($staffMembers as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->full_name }} ({{ $staff->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        @if($calendarSystem === 'BS')
                            <input type="text" name="start_date" class="form-control nepali-datepicker" pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" required>
                        @else
                            <input type="date" name="start_date" class="form-control" required>
                        @endif
                    </div>
                    <div class="col-6">
                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                        @if($calendarSystem === 'BS')
                            <input type="text" name="end_date" class="form-control nepali-datepicker" pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" required>
                        @else
                            <input type="date" name="end_date" class="form-control" required>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reason <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Initial Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Approved">Approved</option>
                        <option value="Pending">Pending</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Leave Request</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userTypeSelect = document.getElementById('modalUserType');
        const studentSelectWrapper = document.getElementById('studentSelectWrapper');
        const staffSelectWrapper = document.getElementById('staffSelectWrapper');
        const studentSelect = document.getElementById('studentSelect');
        const staffSelect = document.getElementById('staffSelect');

        userTypeSelect.addEventListener('change', function() {
            if (this.value === 'student') {
                studentSelectWrapper.classList.remove('d-none');
                staffSelectWrapper.classList.add('d-none');
                studentSelect.required = true;
                staffSelect.required = false;
                staffSelect.name = ''; // Prevent sending empty user_id for staff
                studentSelect.name = 'user_id';
            } else if (this.value === 'staff') {
                staffSelectWrapper.classList.remove('d-none');
                studentSelectWrapper.classList.add('d-none');
                staffSelect.required = true;
                studentSelect.required = false;
                studentSelect.name = ''; 
                staffSelect.name = 'user_id';
            } else {
                studentSelectWrapper.classList.add('d-none');
                staffSelectWrapper.classList.add('d-none');
                studentSelect.required = false;
                staffSelect.required = false;
            }
        });

        // Initialize Nepali Datepicker if BS calendar is active
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
