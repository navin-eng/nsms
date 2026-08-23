@extends('backend.pages.layout.master')
@push('b-title', $event->name)

@section('backend-content')
@php
    $catColors = ['sports'=>'#2563eb','cultural'=>'#7c3aed','academic'=>'#059669','seminar'=>'#d97706','workshop'=>'#0891b2','health'=>'#dc2626','other'=>'#6b7280'];
    $catColor  = $catColors[$event->category] ?? '#6b7280';
    $totalAttended   = $event->participants->where('status', 'attended')->count();
    $totalRegistered = $event->participants->whereIn('status', ['registered','attended'])->count();
@endphp

<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-start mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('sms.events.index') }}" class="btn btn-sm btn-light border">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="h4 fw-bold text-dark mb-0">{{ $event->name }}</h2>
                <div class="text-muted small">
                    <i class="bi bi-calendar3 me-1"></i>{{ $event->visit_date->format('d M Y') }}
                    @if($event->end_date && $event->end_date != $event->visit_date)
                        — {{ $event->end_date->format('d M Y') }}
                    @endif
                    @if($event->venue) &nbsp;·&nbsp; <i class="bi bi-geo-alt me-1"></i>{{ $event->venue }} @endif
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-sm btn-light border" target="_blank">
                <i class="bi bi-file-earmark-pdf text-danger me-1"></i> PDF
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'print']) }}" class="btn btn-sm btn-light border" target="_blank">
                <i class="bi bi-printer text-secondary me-1"></i> Print
            </a>
            <a href="{{ route('sms.events.edit', $event) }}" class="btn btn-sm btn-light border">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4" style="overflow:hidden">
        <div style="height:4px;background:{{ $catColor }}"></div>
        <div class="card-body p-4">
            <div class="row g-4 align-items-start">
                @if($event->image)
                <div class="col-md-3">
                    <img src="{{ asset('storage/'.$event->image) }}" class="rounded-3 w-100" style="object-fit:cover;max-height:180px">
                </div>
                @endif
                <div class="{{ $event->image ? 'col-md-9' : 'col-12' }}">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge rounded-pill px-2 py-1"
                              style="font-size:11px;background:{{ $catColor }}20;color:{{ $catColor }};border:1px solid {{ $catColor }}40">
                            {{ $event->category_label }}
                        </span>
                        <span class="badge rounded-pill px-2 py-1"
                              style="font-size:11px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb">
                            {{ $event->event_type_label }}
                        </span>
                        @if($event->registration_open)
                        <span class="badge rounded-pill px-2 py-1"
                              style="font-size:11px;background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0">
                            <i class="bi bi-door-open me-1"></i>Registration Open
                        </span>
                        @endif
                    </div>
                    <div class="row g-3" style="font-size:14px">
                        @if($event->start_time)
                        <div class="col-sm-4">
                            <div class="text-muted small">Time</div>
                            <div class="fw-medium">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                                @if($event->end_time) — {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }} @endif
                            </div>
                        </div>
                        @endif
                        <div class="col-sm-4">
                            <div class="text-muted small">Registered</div>
                            <div class="fw-bold text-dark">{{ $totalRegistered }}
                                @if($event->max_participants) / {{ $event->max_participants }} @endif
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Attended</div>
                            <div class="fw-bold" style="color:#16a34a">{{ $totalAttended }}</div>
                        </div>
                        @if($event->registration_deadline)
                        <div class="col-sm-4">
                            <div class="text-muted small">Reg. Deadline</div>
                            <div class="fw-medium">{{ $event->registration_deadline->format('d M Y') }}</div>
                        </div>
                        @endif
                        @if($event->result_link)
                        <div class="col-sm-4">
                            <div class="text-muted small">Results</div>
                            <a href="{{ $event->result_link }}" target="_blank" class="small">View Results <i class="bi bi-box-arrow-up-right"></i></a>
                        </div>
                        @endif
                    </div>
                    @if($event->description)
                    <p class="text-muted small mt-3 mb-0">{{ $event->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs border-bottom mb-4" id="eventTabs">
        <li class="nav-item">
            <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#participants">
                <i class="bi bi-people me-1"></i>Participants
                <span class="badge ms-1" style="font-size:10px;background:#f3f4f6;color:#374151">{{ $totalRegistered }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#attendance" id="attendance-tab">
                <i class="bi bi-check2-square me-1"></i>Attendance
                <span class="badge ms-1" style="font-size:10px;background:#dcfce7;color:#16a34a">{{ $totalAttended }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#certificates">
                <i class="bi bi-award me-1"></i>Certificates
                <span class="badge ms-1" style="font-size:10px;background:#eff6ff;color:#2563eb">{{ $event->participants->where('certificate_issued', true)->count() }}</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ===================== PARTICIPANTS TAB ===================== --}}
        <div class="tab-pane fade show active" id="participants">

            {{-- Register Button --}}
            @if($event->registration_open || true)
            <div class="mb-3">
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="bi bi-person-plus me-1"></i> Register Participant(s)
                </button>
            </div>
            @endif

            {{-- Students --}}
            @if($studentParticipants->count())
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-semibold mb-0"><i class="bi bi-mortarboard me-2"></i>Students ({{ $studentParticipants->count() }})</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Student</th>
                                <th class="py-3">ID</th>
                                <th class="py-3">Registered</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-end px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($studentParticipants as $ep)
                        @php $s = $studentMap->get($ep->participant_id); @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    @if($s && $s->photo)
                                        <img src="{{ asset('storage/'.$s->photo) }}" class="rounded-circle" width="36" height="36" style="object-fit:cover">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold text-white flex-shrink-0"
                                             style="width:36px;height:36px;background:#6366f1;font-size:13px">
                                            {{ strtoupper(substr($s->first_name ?? '?', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size:13px">{{ $s->first_name ?? '—' }} {{ $s->last_name ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12px;color:#6b7280">{{ $s->student_id ?? '—' }}</td>
                            <td style="font-size:12px;color:#6b7280">{{ $ep->registered_at ? $ep->registered_at->format('d M Y') : '—' }}</td>
                            <td>
                                @if($ep->status === 'attended')
                                    <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0">Attended</span>
                                @elseif($ep->status === 'cancelled')
                                    <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb">Cancelled</span>
                                @else
                                    <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe">Registered</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <form method="POST" action="{{ route('sms.events.participants.remove', [$event, $ep]) }}"
                                      onsubmit="return confirm('Remove this participant?')" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border text-danger" title="Remove">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Staff --}}
            @if($staffParticipants->count())
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-semibold mb-0"><i class="bi bi-briefcase me-2"></i>Staff ({{ $staffParticipants->count() }})</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Staff</th>
                                <th class="py-3">Employee ID</th>
                                <th class="py-3">Registered</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-end px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($staffParticipants as $ep)
                        @php $st = $staffMap->get($ep->participant_id); @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    @if($st && $st->photo)
                                        <img src="{{ asset('storage/'.$st->photo) }}" class="rounded-circle" width="36" height="36" style="object-fit:cover">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold text-white flex-shrink-0"
                                             style="width:36px;height:36px;background:#7c3aed;font-size:13px">
                                            {{ strtoupper(substr($st->first_name ?? '?', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="fw-semibold text-dark" style="font-size:13px">{{ $st->first_name ?? '—' }} {{ $st->last_name ?? '' }}</div>
                                </div>
                            </td>
                            <td style="font-size:12px;color:#6b7280">{{ $st->employee_id ?? '—' }}</td>
                            <td style="font-size:12px;color:#6b7280">{{ $ep->registered_at ? $ep->registered_at->format('d M Y') : '—' }}</td>
                            <td>
                                @if($ep->status === 'attended')
                                    <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0">Attended</span>
                                @elseif($ep->status === 'cancelled')
                                    <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb">Cancelled</span>
                                @else
                                    <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe">Registered</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <form method="POST" action="{{ route('sms.events.participants.remove', [$event, $ep]) }}"
                                      onsubmit="return confirm('Remove this participant?')" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border text-danger"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($studentParticipants->count() === 0 && $staffParticipants->count() === 0)
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people fs-1 d-block mb-2" style="opacity:.2"></i>
                <div class="fw-semibold">No Participants Yet</div>
                <div class="small">Click "Register Participant(s)" to add students or staff.</div>
            </div>
            @endif
        </div>

        {{-- ===================== ATTENDANCE TAB ===================== --}}
        <div class="tab-pane fade" id="attendance">
            @php $attendable = $event->participants->whereIn('status', ['registered','attended']); @endphp
            @if($attendable->count())
            <form method="POST" action="{{ route('sms.events.attendance', $event) }}">
                @csrf
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-semibold mb-0">Mark Attendance</h6>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light border" id="checkAll">Check All</button>
                            <button type="button" class="btn btn-sm btn-light border" id="uncheckAll">Uncheck All</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3" style="width:50px">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th class="py-3">Participant</th>
                                    <th class="py-3">Type</th>
                                    <th class="py-3">Current Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($attendable as $ep)
                            @php
                                $isStudent = $ep->participant_type === 'student';
                                $person = $isStudent ? $studentMap->get($ep->participant_id) : $staffMap->get($ep->participant_id);
                            @endphp
                            <tr>
                                <td class="px-4">
                                    <input type="checkbox" name="attended_ids[]" value="{{ $ep->id }}"
                                           class="form-check-input attend-check"
                                           {{ $ep->status === 'attended' ? 'checked' : '' }}>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark" style="font-size:13px">
                                        {{ $person->first_name ?? '—' }} {{ $person->last_name ?? '' }}
                                    </div>
                                    <div class="text-muted" style="font-size:11px">
                                        {{ $isStudent ? ($person->student_id ?? '') : ($person->employee_id ?? '') }}
                                    </div>
                                </td>
                                <td style="font-size:12px;color:#6b7280">{{ $isStudent ? 'Student' : 'Staff' }}</td>
                                <td>
                                    @if($ep->status === 'attended')
                                        <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0">Attended</span>
                                    @else
                                        <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe">Registered</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 text-end">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-save me-2"></i>Save Attendance
                        </button>
                    </div>
                </div>
            </form>
            @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clipboard-check fs-1 d-block mb-2" style="opacity:.2"></i>
                <div class="fw-semibold">No Participants to Mark</div>
                <div class="small">Register participants first from the Participants tab.</div>
            </div>
            @endif
        </div>

        {{-- ===================== CERTIFICATES TAB ===================== --}}
        <div class="tab-pane fade" id="certificates">
            @php $attended = $event->participants->where('status', 'attended')->where('participant_type', 'student'); @endphp
            @if($attended->count())
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-semibold mb-0"><i class="bi bi-award me-2"></i>Issue Participation Certificates</h6>
                    <p class="text-muted small mb-0 mt-1">Certificates can be issued to students who attended the event. They will appear in the main Certificates module.</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Student</th>
                                <th class="py-3">ID</th>
                                <th class="py-3">Certificate Status</th>
                                <th class="py-3 text-end px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($attended as $ep)
                        @php $s = $studentMap->get($ep->participant_id); @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-semibold text-dark" style="font-size:13px">{{ $s->first_name ?? '—' }} {{ $s->last_name ?? '' }}</div>
                            </td>
                            <td style="font-size:12px;color:#6b7280">{{ $s->student_id ?? '—' }}</td>
                            <td>
                                @if($ep->certificate_issued)
                                    <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0">
                                        <i class="bi bi-check-circle me-1"></i>Issued
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-2 py-1" style="font-size:11px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb">Not Issued</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <a href="{{ route('sms.events.certificate', [$event, $ep]) }}"
                                   class="btn btn-sm {{ $ep->certificate_issued ? 'btn-light border' : 'btn-primary' }}">
                                    <i class="bi bi-award me-1"></i>
                                    {{ $ep->certificate_issued ? 'Re-print' : 'Issue Certificate' }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-award fs-1 d-block mb-2" style="opacity:.2"></i>
                <div class="fw-semibold">No Attendees Yet</div>
                <div class="small">Mark attendance first — certificates can only be issued to attendees.</div>
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ===================== REGISTER MODAL ===================== --}}
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Register Participant(s)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('sms.events.participants.store', $event) }}">
                @csrf
                <div class="modal-body pt-0">
                    {{-- Type toggle --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Participant Type <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="participant_type" id="typeStudent" value="student" checked>
                                <label class="form-check-label" for="typeStudent">Students</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="participant_type" id="typeStaff" value="staff">
                                <label class="form-check-label" for="typeStaff">Staff</label>
                            </div>
                        </div>
                    </div>

                    {{-- Student panel --}}
                    <div id="studentPanel">
                        <label class="form-label fw-semibold small">Select Students</label>
                        <select name="participant_ids[]" id="studentSelect" class="form-select" multiple style="height:200px">
                            @foreach($students as $s)
                            <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->student_id }})</option>
                            @endforeach
                        </select>
                        <div class="text-muted small mt-1">Hold Ctrl/Cmd to select multiple students.</div>
                    </div>

                    {{-- Staff panel --}}
                    <div id="staffPanel" style="display:none">
                        <label class="form-label fw-semibold small">Select Staff</label>
                        <select name="participant_ids[]" id="staffSelect" class="form-select" multiple style="height:200px">
                            @foreach($staffList as $st)
                            <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }} ({{ $st->employee_id }})</option>
                            @endforeach
                        </select>
                        <div class="text-muted small mt-1">Hold Ctrl/Cmd to select multiple staff.</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Type toggle
    document.querySelectorAll('input[name="participant_type"]').forEach(radio => {
        radio.addEventListener('change', function () {
            document.getElementById('studentPanel').style.display = this.value === 'student' ? '' : 'none';
            document.getElementById('staffPanel').style.display   = this.value === 'staff'   ? '' : 'none';
            // Disable the hidden select to avoid sending its values
            document.getElementById('studentSelect').disabled = this.value !== 'student';
            document.getElementById('staffSelect').disabled   = this.value !== 'staff';
        });
    });
    // Init state
    document.getElementById('staffSelect').disabled = true;

    // Attendance select all
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.attend-check').forEach(cb => cb.checked = this.checked);
        });
    }
    const checkAll   = document.getElementById('checkAll');
    const uncheckAll = document.getElementById('uncheckAll');
    if (checkAll)   checkAll.addEventListener('click',   () => document.querySelectorAll('.attend-check').forEach(cb => cb.checked = true));
    if (uncheckAll) uncheckAll.addEventListener('click', () => document.querySelectorAll('.attend-check').forEach(cb => cb.checked = false));

    // Activate tab from hash
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`[href="${hash}"]`);
        if (tab) new bootstrap.Tab(tab).show();
    }
});
</script>
@endpush
