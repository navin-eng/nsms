@extends('backend.pages.layout.master')
@section('title', 'Add School Notice')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Add School Notice</h5>
        <p class="text-muted small mb-0">Create internal announcements targeted at specific school groups.</p>
    </div>
    <div>
        <a href="{{ route('sms.school-notices.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<form action="{{ route('sms.school-notices.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="row">
        {{-- Left Column: Details --}}
        <div class="col-md-7 col-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3 fw-bold">Notice Content</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" required placeholder="Enter announcement title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Image (Optional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description / Body <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" rows="7" required placeholder="Type the announcement description here...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Target Audience & Publishing --}}
        <div class="col-md-5 col-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3 fw-bold">Target Audience</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Target Roles <span class="text-danger">*</span></label>
                        <select name="target_roles[]" id="targetRoles" class="form-select select2" multiple required data-placeholder="Select Target Roles">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Class Selector - Hidden by default, shown if Student/Guardian is selected --}}
                    <div class="mb-3" id="classSelectorWrapper" style="display: none;">
                        <label class="form-label fw-semibold">Target Classes (Optional)</label>
                        <select name="target_classes[]" id="targetClasses" class="form-select select2" multiple data-placeholder="All Classes">
                            @foreach($academicClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Leave blank to target all classes.</small>
                    </div>

                    {{-- Section Selector - Hidden by default, shown if Class is selected --}}
                    <div class="mb-3" id="sectionSelectorWrapper" style="display: none;">
                        <label class="form-label fw-semibold">Target Sections (Optional)</label>
                        <select name="target_sections[]" id="targetSections" class="form-select select2" multiple data-placeholder="All Sections">
                            @foreach($sections as $section)
                                @php 
                                    $classIds = $section->academicClasses->pluck('id')->join(','); 
                                @endphp
                                <option value="{{ $section->id }}" data-class-ids="{{ $classIds }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Leave blank to target all sections.</small>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3 fw-bold">Publish & Alerts</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Publish Status</label>
                        <select name="status" class="form-select">
                            <option value="published">Published (Active & Visible)</option>
                            <option value="draft">Draft (Saved but Hidden)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block">Send Out-of-Band Notifications</label>
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="checkbox" name="notify_channels[]" value="sms" id="smsCheck">
                            <label class="form-check-label" for="smsCheck">SMS</label>
                        </div>
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="checkbox" name="notify_channels[]" value="email" id="emailCheck">
                            <label class="form-check-label" for="emailCheck">Email</label>
                        </div>
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="checkbox" name="notify_channels[]" value="push" id="pushCheck">
                            <label class="form-check-label" for="pushCheck">Push Notification</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Save Notice</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
$(document).ready(function() {
    // Enable Select2
    $('.select2').select2({
        width: '100%'
    });

    const targetRoles = $('#targetRoles');
    const targetClasses = $('#targetClasses');
    const targetSections = $('#targetSections');

    const classWrapper = $('#classSelectorWrapper');
    const sectionWrapper = $('#sectionSelectorWrapper');

    // Handle cascading selectors based on Role
    targetRoles.on('change', function() {
        const selectedRoles = $(this).val() || [];
        // Show classes if Student or Guardian (or parent) role is selected
        const showClasses = selectedRoles.includes('student') || 
                             selectedRoles.includes('guardian') || 
                             selectedRoles.includes('parent');
        
        if (showClasses) {
            classWrapper.slideDown(200);
        } else {
            classWrapper.slideUp(200);
            targetClasses.val(null).trigger('change');
            sectionWrapper.slideUp(200);
            targetSections.val(null).trigger('change');
        }
    });

    // Handle cascading sections based on Class selection
    targetClasses.on('change', function() {
        const selectedClasses = $(this).val() || [];
        if (selectedClasses.length > 0) {
            sectionWrapper.slideDown(200);
            
            // Filter section options
            $('#targetSections option').each(function() {
                const opt = $(this);
                const classIdsStr = opt.data('class-ids') || '';
                const allowedClasses = classIdsStr.split(',').map(s => s.trim());
                
                // Show if section belongs to ANY of the selected classes
                const match = allowedClasses.some(id => selectedClasses.includes(id));
                if (match || !classIdsStr) {
                    opt.prop('disabled', false);
                } else {
                    opt.prop('disabled', true);
                    opt.prop('selected', false); // Deselect if disabled
                }
            });
            targetSections.trigger('change.select2');
        } else {
            sectionWrapper.slideUp(200);
            targetSections.val(null).trigger('change');
        }
    });
});
</script>
@endpush
@endsection
