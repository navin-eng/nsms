{{-- Shared form partial for create & edit events --}}
@php $isEdit = isset($event); @endphp

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <h6 class="fw-semibold text-dark mb-3 pb-2 border-bottom">Basic Information</h6>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold small">Event Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $event->name ?? '') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="1" {{ old('status', $event->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $event->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Event Type <span class="text-danger">*</span></label>
                <select name="event_type" class="form-select" required>
                    @foreach(['event' => 'Event', 'holiday' => 'Holiday', 'exam' => 'Exam', 'test' => 'Test', 'cca_eca' => 'CCA / ECA', 'result' => 'Result'] as $val => $label)
                        <option value="{{ $val }}" {{ old('event_type', $event->event_type ?? 'event') == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                <select name="category" class="form-select" required>
                    @foreach(['sports' => 'Sports', 'cultural' => 'Cultural', 'academic' => 'Academic', 'seminar' => 'Seminar', 'workshop' => 'Workshop', 'health' => 'Health & Wellness', 'other' => 'Other'] as $val => $label)
                        <option value="{{ $val }}" {{ old('category', $event->category ?? 'other') == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Venue</label>
                <input type="text" name="venue" class="form-control" placeholder="e.g. School Auditorium"
                       value="{{ old('venue', $event->venue ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Result Link</label>
                <input type="url" name="result_link" class="form-control" placeholder="https://..."
                       value="{{ old('result_link', $event->result_link ?? '') }}">
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <h6 class="fw-semibold text-dark mb-3 pb-2 border-bottom">Date & Time</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Start Date <span class="text-danger">*</span></label>
                <input type="date" name="visit_date" class="form-control @error('visit_date') is-invalid @enderror"
                       value="{{ old('visit_date', isset($event) ? $event->visit_date->format('Y-m-d') : '') }}" required>
                @error('visit_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">End Date <span class="text-muted">(for multi-day events)</span></label>
                <input type="date" name="end_date" class="form-control"
                       value="{{ old('end_date', isset($event) && $event->end_date ? $event->end_date->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Start Time</label>
                <input type="time" name="start_time" class="form-control"
                       value="{{ old('start_time', $event->start_time ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">End Time</label>
                <input type="time" name="end_time" class="form-control"
                       value="{{ old('end_time', $event->end_time ?? '') }}">
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <h6 class="fw-semibold text-dark mb-3 pb-2 border-bottom">Registration Settings</h6>
        <div class="row g-3">
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" name="registration_open" id="reg_open" value="1"
                           {{ old('registration_open', $event->registration_open ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="reg_open">
                        Open Registration
                        <span class="text-muted fw-normal"> — allow participants to be added to this event</span>
                    </label>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Registration Deadline</label>
                <input type="date" name="registration_deadline" class="form-control"
                       value="{{ old('registration_deadline', isset($event) && $event->registration_deadline ? $event->registration_deadline->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Max Participants <span class="text-muted fw-normal">(leave blank for unlimited)</span></label>
                <input type="number" name="max_participants" class="form-control" min="1" placeholder="e.g. 100"
                       value="{{ old('max_participants', $event->max_participants ?? '') }}">
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-4">
        <h6 class="fw-semibold text-dark mb-3 pb-2 border-bottom">Description & Image</h6>
        <div class="mb-3">
            <label class="form-label fw-semibold small">Description</label>
            <textarea name="description" class="form-control" rows="4"
                      placeholder="Describe the event...">{{ old('description', $event->description ?? '') }}</textarea>
        </div>
        <div>
            <label class="form-label fw-semibold small">Cover Image</label>
            @if($isEdit && $event->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $event->image) }}" class="rounded-3" style="max-height:120px">
                </div>
            @endif
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
    </div>
</div>
