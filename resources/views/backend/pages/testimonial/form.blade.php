@php
    $isEdit = isset($testimonial);
    $formTitle = $isEdit ? 'Edit Testimonial' : 'Add Testimonial';
    $formSubtitle = $isEdit
        ? 'Refine the student or guardian feedback, update the role, and replace the photo if needed.'
        : 'Show authentic student and guardian feedback with a polished card-style form.';
@endphp

<div class="testimonial-editor-shell">
    <div class="testimonial-editor-hero card border-0 shadow-sm overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <span class="testimonial-editor-kicker">Testimonials</span>
                    <h3 class="testimonial-editor-title mb-2">{{ $formTitle }}</h3>
                    <p class="text-muted mb-0">{{ $formSubtitle }}</p>
                </div>
                <a href="{{ route('testimonial.table') }}" class="btn btn-success px-4">
                    <i class="bi bi-table me-1"></i> Table
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm testimonial-form-card">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $testimonial->name ?? '') }}" class="form-control form-control-lg" placeholder="Student or guardian name" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label class="form-label">Role / Identity</label>
                                <input type="text" name="role" value="{{ old('role', $testimonial->role ?? '') }}" class="form-control form-control-lg" placeholder="Example: BBA Student, Parent, Alumni" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group-modern">
                                <label class="form-label">Photo</label>
                                <input type="file" name="image" class="form-control form-control-lg" {{ $isEdit ? '' : 'required' }}>
                                <small class="text-muted d-block mt-2">Use a clear portrait image in JPG or PNG format for the testimonial card.</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group-modern">
                                <label class="form-label">Testimonial Message</label>
                                <textarea class="form-control testimonial-textarea" name="description" rows="8" placeholder="Write what the student or guardian says about the college experience..." required>{{ old('description', $testimonial->description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> {{ $isEdit ? 'Update Testimonial' : 'Save Testimonial' }}
                        </button>
                        <a href="{{ route('testimonial.table') }}" class="btn btn-light border px-4">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm testimonial-side-card h-100">
                <div class="card-body p-4">
                    <h5 class="testimonial-side-title">Preview & Tips</h5>

                    <div class="testimonial-preview-card mb-4">
                        @if(!empty($testimonial->image))
                            <img src="{{ asset($testimonial->image) }}" alt="{{ $testimonial->name }}" class="testimonial-preview-image">
                        @else
                            <div class="testimonial-preview-placeholder">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        @endif

                        <div class="testimonial-preview-body">
                            <h6>{{ old('name', $testimonial->name ?? 'Preview Name') }}</h6>
                            <span>{{ old('role', $testimonial->role ?? 'Preview Role') }}</span>
                            <p>{{ \Illuminate\Support\Str::limit(trim(old('description', $testimonial->description ?? 'A short, sincere testimonial will look great here once you fill in the message.')), 180) }}</p>
                        </div>
                    </div>

                    <div class="testimonial-guide-card">
                        <h6>What makes a strong testimonial?</h6>
                        <ul class="testimonial-guide-list">
                            <li>Use a real name and clear role</li>
                            <li>Keep the message personal and believable</li>
                            <li>Mention teaching quality, support, facilities, or outcomes</li>
                            <li>Use a clean face photo for trust and credibility</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

