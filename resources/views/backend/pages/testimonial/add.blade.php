@extends('backend.pages.layout.master')
@push('b-title', 'Add Testimonials')

@push('styles')
<style>
    .testimonial-bulk-hero {
        background:
            radial-gradient(circle at top right, rgba(82, 183, 136, 0.18), transparent 28%),
            linear-gradient(135deg, #f9fffb 0%, #f4f7ff 100%);
    }

    .testimonial-bulk-kicker {
        display: inline-flex;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eaf7ef;
        color: #157347;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .testimonial-bulk-title {
        font-size: 2rem;
        font-weight: 800;
        color: #132238;
    }

    .testimonial-bulk-card,
    .testimonial-bulk-side {
        border-radius: 24px;
        overflow: hidden;
    }

    .testimonial-item-card {
        border: 1px solid #e3ebf4;
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        box-shadow: 0 12px 30px rgba(19, 34, 56, 0.05);
        padding: 22px;
    }

    .testimonial-item-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .testimonial-item-head h5 {
        margin: 0;
        font-weight: 800;
        color: #17324d;
    }

    .testimonial-item-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .testimonial-item-grid .full-span {
        grid-column: 1 / -1;
    }

    .testimonial-form-label {
        font-weight: 700;
        color: #17324d;
        margin-bottom: 10px;
    }

    .testimonial-form-control {
        border-radius: 16px;
        border: 1px solid #dbe6f2;
        box-shadow: none;
        padding: 14px 16px;
    }

    .testimonial-form-control:focus {
        border-color: #7cb4ff;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.08);
    }

    .testimonial-form-textarea {
        min-height: 170px;
        resize: vertical;
    }

    .testimonial-side-title,
    .testimonial-side-box h6 {
        font-weight: 800;
        color: #132238;
    }

    .testimonial-side-box {
        padding: 18px 20px;
        border-radius: 18px;
        background: linear-gradient(135deg, #f7fbff 0%, #f8fffb 100%);
        border: 1px solid #e5edf6;
    }

    .testimonial-side-list {
        margin: 12px 0 0;
        padding-left: 18px;
        color: #48627e;
        line-height: 1.8;
    }

    .testimonial-counter-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        border-radius: 999px;
        background: #edf6ff;
        color: #0d6efd;
        font-weight: 800;
    }

    @media (max-width: 767.98px) {
        .testimonial-item-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('backend-content')
<form action="{{ route('testimonial.store') }}" enctype="multipart/form-data" method="POST" id="bulkTestimonialForm">
    @csrf

    <div class="card border-0 shadow-sm testimonial-bulk-hero mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <span class="testimonial-bulk-kicker">Bulk Entry</span>
                    <h3 class="testimonial-bulk-title mb-2">Add Multiple Testimonials</h3>
                    <p class="text-muted mb-0">Create several testimonial cards at once instead of saving them one by one.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('testimonial.table') }}" class="btn btn-success px-4">
                        <i class="bi bi-table me-1"></i> Table
                    </a>
                    <button type="button" class="btn btn-outline-primary px-4" id="addTestimonialItemBtn">
                        <i class="bi bi-plus-circle me-1"></i> Add Another Testimonial
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm testimonial-bulk-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                        <div>
                            <h4 class="mb-1">Testimonial Items</h4>
                            <p class="text-muted mb-0">Each card below creates one testimonial on the public website.</p>
                        </div>
                        <span class="testimonial-counter-badge" id="testimonialCountBadge">1</span>
                    </div>

                    <div id="testimonialItemsWrap" class="d-grid gap-4"></div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Save All Testimonials
                        </button>
                        <button type="button" class="btn btn-light border px-4" id="duplicateFirstItemBtn">
                            <i class="bi bi-files me-1"></i> Duplicate First Layout
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm testimonial-bulk-side h-100">
                <div class="card-body p-4">
                    <h5 class="testimonial-side-title mb-3">Bulk Upload Guide</h5>

                    <div class="testimonial-side-box mb-3">
                        <h6>Best use of this page</h6>
                        <ul class="testimonial-side-list">
                            <li>Add several approved testimonials in one visit</li>
                            <li>Use clear role labels like Student, Parent, Alumni</li>
                            <li>Upload one portrait photo per testimonial item</li>
                            <li>Keep the message sincere and short enough to read comfortably</li>
                        </ul>
                    </div>

                    <div class="testimonial-side-box">
                        <h6>Good message ideas</h6>
                        <ul class="testimonial-side-list">
                            <li>Teaching quality and supportive faculty</li>
                            <li>Facilities, labs, and practical learning</li>
                            <li>Admission guidance and student care</li>
                            <li>Confidence, growth, and future opportunities</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    (() => {
        const itemsWrap = document.getElementById('testimonialItemsWrap');
        const addBtn = document.getElementById('addTestimonialItemBtn');
        const duplicateBtn = document.getElementById('duplicateFirstItemBtn');
        const countBadge = document.getElementById('testimonialCountBadge');

        let itemIndex = 0;

        const buildItem = (values = {}) => {
            const currentIndex = itemIndex++;
            const card = document.createElement('div');
            card.className = 'testimonial-item-card';
            card.dataset.index = currentIndex;
            card.innerHTML = `
                <div class="testimonial-item-head">
                    <div>
                        <h5>Testimonial <span class="item-number"></span></h5>
                        <small class="text-muted">One student, parent, or alumni feedback block</small>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-testimonial-item">
                        <i class="bi bi-trash3 me-1"></i> Remove
                    </button>
                </div>
                <div class="testimonial-item-grid">
                    <div>
                        <label class="form-label testimonial-form-label">Full Name</label>
                        <input type="text" name="testimonials[${currentIndex}][name]" class="form-control testimonial-form-control" placeholder="Student or guardian name" value="${values.name || ''}" required>
                    </div>
                    <div>
                        <label class="form-label testimonial-form-label">Role / Identity</label>
                        <input type="text" name="testimonials[${currentIndex}][role]" class="form-control testimonial-form-control" placeholder="Example: BBA Student, Parent, Alumni" value="${values.role || ''}" required>
                    </div>
                    <div class="full-span">
                        <label class="form-label testimonial-form-label">Photo</label>
                        <input type="file" name="testimonials[${currentIndex}][image]" class="form-control testimonial-form-control" accept=".jpg,.jpeg,.png" required>
                    </div>
                    <div class="full-span">
                        <label class="form-label testimonial-form-label">Testimonial Message</label>
                        <textarea name="testimonials[${currentIndex}][description]" class="form-control testimonial-form-control testimonial-form-textarea" placeholder="Write the actual testimonial here..." required>${values.description || ''}</textarea>
                    </div>
                </div>
            `;

            itemsWrap.appendChild(card);
            refreshItemNumbers();
        };

        const refreshItemNumbers = () => {
            const cards = itemsWrap.querySelectorAll('.testimonial-item-card');
            cards.forEach((card, index) => {
                card.querySelector('.item-number').textContent = index + 1;
                const removeBtn = card.querySelector('.remove-testimonial-item');
                removeBtn.disabled = cards.length === 1;
                removeBtn.classList.toggle('disabled', cards.length === 1);
            });
            countBadge.textContent = cards.length;
        };

        addBtn.addEventListener('click', () => buildItem());

        duplicateBtn.addEventListener('click', () => {
            const firstCard = itemsWrap.querySelector('.testimonial-item-card');
            if (!firstCard) {
                buildItem();
                return;
            }

            buildItem({
                name: firstCard.querySelector('input[name*="[name]"]').value,
                role: firstCard.querySelector('input[name*="[role]"]').value,
                description: firstCard.querySelector('textarea[name*="[description]"]').value
            });
        });

        itemsWrap.addEventListener('click', (event) => {
            const removeBtn = event.target.closest('.remove-testimonial-item');
            if (!removeBtn) {
                return;
            }

            const cards = itemsWrap.querySelectorAll('.testimonial-item-card');
            if (cards.length <= 1) {
                return;
            }

            removeBtn.closest('.testimonial-item-card').remove();
            refreshItemNumbers();
        });

        buildItem({
            name: @json(old('testimonials.0.name', '')),
            role: @json(old('testimonials.0.role', '')),
            description: @json(old('testimonials.0.description', ''))
        });
    })();
</script>
@endpush

