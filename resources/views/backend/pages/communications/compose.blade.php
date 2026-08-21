@extends('backend.pages.layout.master')
@section('title', 'Compose Message')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Compose Message</h5>
        <p class="text-muted small mb-0">Send SMS, Email or Push Notifications to a targeted audience.</p>
    </div>
    <div>
        <a href="{{ route('admin.communications.logs') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-clock-history me-1"></i>View Logs
        </a>
    </div>
</div>

<form action="{{ route('admin.communications.send') }}" method="POST" id="composeForm">
    @csrf
    <div class="row g-4">

        {{-- STEP 1: Channel --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><span class="badge bg-primary me-2">1</span>Select Channel</span>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check channel-card p-3 border rounded-3 flex-fill" style="min-width:140px; cursor:pointer;">
                            <input class="form-check-input" type="radio" name="channel" id="chSms" value="sms" required checked>
                            <label class="form-check-label d-block ms-2" for="chSms" style="cursor:pointer;">
                                <i class="bi bi-chat-text-fill text-primary fs-4 d-block mb-1"></i>
                                <strong>SMS</strong>
                                <div class="small text-muted">Text message to phone</div>
                            </label>
                        </div>
                        <div class="form-check channel-card p-3 border rounded-3 flex-fill" style="min-width:140px; cursor:pointer;">
                            <input class="form-check-input" type="radio" name="channel" id="chEmail" value="email">
                            <label class="form-check-label d-block ms-2" for="chEmail" style="cursor:pointer;">
                                <i class="bi bi-envelope-fill text-danger fs-4 d-block mb-1"></i>
                                <strong>Email</strong>
                                <div class="small text-muted">Send to email address</div>
                            </label>
                        </div>
                        <div class="form-check channel-card p-3 border rounded-3 flex-fill" style="min-width:140px; cursor:pointer;">
                            <input class="form-check-input" type="radio" name="channel" id="chPush" value="push">
                            <label class="form-check-label d-block ms-2" for="chPush" style="cursor:pointer;">
                                <i class="bi bi-bell-fill text-warning fs-4 d-block mb-1"></i>
                                <strong>Push</strong>
                                <div class="small text-muted">App push notification</div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 2: Target Audience --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><span class="badge bg-primary me-2">2</span>Target Audience</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        {{-- Step 2a: Role --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Who are you sending to? <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-2" id="roleCards">
                                @foreach($roles as $role)
                                <div class="form-check p-0">
                                    <input class="btn-check" type="radio" name="target_role" id="role_{{ $role->id }}" value="{{ $role->name }}" required>
                                    <label class="btn btn-outline-secondary px-4 py-2" for="role_{{ $role->id }}">
                                        @if($role->name == 'student') <i class="bi bi-person-video me-1"></i>
                                        @elseif(in_array($role->name, ['guardian', 'parent'])) <i class="bi bi-people me-1"></i>
                                        @elseif($role->name == 'teacher') <i class="bi bi-easel me-1"></i>
                                        @else <i class="bi bi-person-badge me-1"></i>
                                        @endif
                                        {{ ucfirst($role->name) }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            {{-- hidden field relayed by JS --}}
                            <input type="hidden" name="target_roles[]" id="hiddenRole">
                        </div>

                        {{-- Step 2b: Class (shown when student/guardian selected) --}}
                        <div class="col-md-6" id="classWrapper" style="display:none;">
                            <label class="form-label fw-semibold">Select Class</label>
                            <select name="target_classes[]" id="classSelect" class="form-select select2" multiple data-placeholder="All classes (leave blank for all)">
                                @foreach($academicClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Leave blank to target all classes.</small>
                        </div>

                        {{-- Step 2c: Section (dynamically loaded when class selected) --}}
                        <div class="col-md-6" id="sectionWrapper" style="display:none;">
                            <label class="form-label fw-semibold">Select Section</label>
                            <select name="target_sections[]" id="sectionSelect" class="form-select select2" multiple data-placeholder="All sections (leave blank for all)">
                                {{-- Options loaded dynamically by AJAX --}}
                            </select>
                            <small class="text-muted">Leave blank to target all sections of selected class.</small>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 3: Message --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold"><span class="badge bg-primary me-2">3</span>Message Content</span>
                </div>
                <div class="card-body">
                    <div id="subjectField" class="mb-3" style="display:none;">
                        <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="subjectInput" class="form-control" placeholder="e.g. School Exam Results Published">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message Body <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="message" rows="5" required placeholder="Type your message here... Use {name} for recipient's name."></textarea>
                        <small class="text-muted d-block mt-1">Available placeholders: <code>{name}</code>, <code>{class}</code>, <code>{section}</code></small>
                    </div>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold">
                        <i class="bi bi-send-fill me-2"></i>Send Now
                    </button>
                </div>
            </div>
        </div>

    </div>
</form>

<style>
.channel-card:has(input:checked) {
    border-color: #0d6efd !important;
    background: rgba(13, 110, 253, 0.05);
}
.btn-check:checked + label.btn-outline-secondary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    const SECTIONS_URL = "{{ route('admin.communications.sections-by-class') }}";
    const ROLES_NEEDING_CLASS = ['student', 'guardian', 'parent'];

    // Init Select2
    $('.select2').select2({ width: '100%' });

    // --- Channel toggle: show/hide subject ---
    $('input[name="channel"]').on('change', function() {
        const ch = $(this).val();
        if (ch === 'sms') {
            $('#subjectField').slideUp(150);
            $('#subjectInput').removeAttr('required');
        } else {
            $('#subjectField').slideDown(150);
            $('#subjectInput').attr('required', 'required');
        }
    });

    // --- Role selection: relay value to hidden field & cascade ---
    $('input[name="target_role"]').on('change', function() {
        const role = $(this).val();
        $('#hiddenRole').val(role);

        if (ROLES_NEEDING_CLASS.includes(role)) {
            $('#classWrapper').slideDown(200);
        } else {
            $('#classWrapper').slideUp(200);
            $('#sectionWrapper').slideUp(200);
            $('#classSelect').val(null).trigger('change');
            $('#sectionSelect').empty().trigger('change');
        }
    });

    // --- Class selection: dynamically load sections ---
    $('#classSelect').on('change', function() {
        const classIds = $(this).val() || [];

        if (classIds.length === 0) {
            $('#sectionWrapper').slideUp(200);
            $('#sectionSelect').empty().trigger('change');
            return;
        }

        // Fetch matching sections
        $.get(SECTIONS_URL, { class_ids: classIds }, function(sections) {
            const $sectionSelect = $('#sectionSelect');
            const previousVals = $sectionSelect.val() || [];

            $sectionSelect.empty();
            sections.forEach(function(s) {
                const selected = previousVals.includes(String(s.id)) ? 'selected' : '';
                $sectionSelect.append(`<option value="${s.id}" ${selected}>${s.name}</option>`);
            });

            $sectionSelect.trigger('change.select2');
            $('#sectionWrapper').slideDown(200);
        }).fail(function() {
            $('#sectionWrapper').slideUp(200);
        });
    });
});
</script>
@endpush
@endsection
