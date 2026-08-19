@push('styles')
<style>
/* keep Summernote inside card */
.note-editor.note-frame { border: none !important; border-radius: 0 !important; }
.note-toolbar { background: var(--admin-bg) !important; border-bottom: 1px solid var(--admin-border) !important; }
</style>
@endpush

@php
    $isEdit = isset($course) && $course;
    $formAction = $isEdit ? route('course.update', $course->id) : route('course.store');
    $heading = $isEdit ? 'Edit Course' : 'Add Course';
    $buttonText = $isEdit ? 'Update Course' : 'Save Course';
    $courseData = [
        'name' => old('name', $course->name ?? ''),
        'duration' => old('duration', $course->duration ?? ''),
        'semester' => old('semester', $course->semester ?? ''),
        'requirement' => old('requirement', $course->requirement ?? ''),
        'starting_time' => old('starting_time'),
        'closing_time' => old('closing_time'),
        'description' => old('description', $course->description ?? ''),
        'fulldescription' => old('fulldescription', $course->fulldescription ?? ''),
    ];
    if ($isEdit && !old('starting_time') && !empty($course->starting_time)) {
        try { $courseData['starting_time'] = \Illuminate\Support\Carbon::parse($course->starting_time)->format('H:i'); }
        catch (\Throwable $e) { $courseData['starting_time'] = ''; }
    }
    if ($isEdit && !old('closing_time') && !empty($course->closing_time)) {
        try { $courseData['closing_time'] = \Illuminate\Support\Carbon::parse($course->closing_time)->format('H:i'); }
        catch (\Throwable $e) { $courseData['closing_time'] = ''; }
    }
@endphp

<div class="admin-page-header">
    <div>
        <h1 class="aph-title">{{ $heading }}</h1>
        <p class="aph-sub">{{ $isEdit ? 'Update course information and content.' : 'Fill in the details to add a new course.' }}</p>
    </div>
    <a href="{{ route('course.table') }}" class="btn-admin btn-admin-outline"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<form action="{{ $formAction }}" enctype="multipart/form-data" method="POST" id="courseForm">
@csrf
<div class="row g-4">

    {{-- ── LEFT: Basic Details ── --}}
    <div class="col-lg-4">
        <div class="admin-card" style="position:sticky;top:80px;">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-info-circle-fill"></i> Basic Details</span>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-group">
                    <label class="admin-label">Course Name <span style="color:#e53e3e">*</span></label>
                    <input type="text" name="name" value="{{ $courseData['name'] }}" class="admin-input" placeholder="e.g. BCA" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Short Slogan / Summary <span style="color:#e53e3e">*</span></label>
                    <input type="text" name="description" class="admin-input" value="{{ $courseData['description'] }}" placeholder="e.g. Industry-ready computing program" required>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="admin-form-group">
                            <label class="admin-label">Duration <span style="color:#e53e3e">*</span></label>
                            <input type="text" name="duration" value="{{ $courseData['duration'] }}" class="admin-input" placeholder="e.g. 4 Years" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="admin-form-group">
                            <label class="admin-label">Semester <span style="color:#e53e3e">*</span></label>
                            <input type="text" name="semester" value="{{ $courseData['semester'] }}" class="admin-input" placeholder="e.g. 8 Semesters" required>
                        </div>
                    </div>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Minimum Requirement <span style="color:#e53e3e">*</span></label>
                    <select class="admin-select" name="requirement" required>
                        <option value="">Select requirement</option>
                        @foreach(['+2', 'SEE', 'Bachelor'] as $req)
                            <option value="{{ $req }}" {{ $courseData['requirement'] === $req ? 'selected' : '' }}>{{ $req }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="admin-form-group">
                            <label class="admin-label">Start Time</label>
                            <input type="time" name="starting_time" value="{{ $courseData['starting_time'] }}" class="admin-input">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="admin-form-group">
                            <label class="admin-label">End Time</label>
                            <input type="time" name="closing_time" value="{{ $courseData['closing_time'] }}" class="admin-input">
                        </div>
                    </div>
                </div>
                <hr style="border-color:var(--admin-border);margin:4px 0 14px;">
                <div class="admin-form-group">
                    <label class="admin-label">Cover Image {{ $isEdit ? '' : '*' }}</label>
                    <input type="file" name="image" class="admin-input" accept="image/*" {{ $isEdit ? '' : 'required' }}>
                    <span class="admin-input-hint">Recommended: 800×500px</span>
                </div>
                <div class="admin-form-group mb-0">
                    <label class="admin-label">Gallery Images</label>
                    <input type="file" name="gallery[]" multiple class="admin-input" accept="image/*">
                    <span class="admin-input-hint">Select multiple images.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Content Builder + Editor ── --}}
    <div class="col-lg-8">

        {{-- Step 1: Content Sections (Tabs) --}}
        <div class="admin-card mb-3">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-layout-text-sidebar-reverse"></i> Course Content Sections</span>
                <span style="font-size:12px;color:var(--admin-muted);">Fill each section, then generate the description.</span>
            </div>
            <div class="admin-card-body">

                {{-- Progress dots --}}
                <div class="section-progress" id="sectionProgress">
                    <div class="section-progress-dot" data-section="overview"></div>
                    <div class="section-progress-dot" data-section="why_choose"></div>
                    <div class="section-progress-dot" data-section="eligibility"></div>
                    <div class="section-progress-dot" data-section="career"></div>
                    <div class="section-progress-dot" data-section="admission"></div>
                    <div class="section-progress-dot" data-section="support"></div>
                </div>

                {{-- Tab buttons --}}
                <div class="builder-tab-nav" id="builderTabNav">
                    <button type="button" class="builder-tab-btn active" data-tab="overview"><span class="tab-dot"></span><i class="bi bi-file-text"></i> Overview</button>
                    <button type="button" class="builder-tab-btn" data-tab="why_choose"><span class="tab-dot"></span><i class="bi bi-star"></i> Why Choose</button>
                    <button type="button" class="builder-tab-btn" data-tab="eligibility"><span class="tab-dot"></span><i class="bi bi-person-check"></i> Eligibility</button>
                    <button type="button" class="builder-tab-btn" data-tab="career"><span class="tab-dot"></span><i class="bi bi-briefcase"></i> Career</button>
                    <button type="button" class="builder-tab-btn" data-tab="admission"><span class="tab-dot"></span><i class="bi bi-clipboard-check"></i> Admission</button>
                    <button type="button" class="builder-tab-btn" data-tab="support"><span class="tab-dot"></span><i class="bi bi-award"></i> Support</button>
                </div>

                {{-- Tab panes --}}
                <div id="pane-overview" class="builder-tab-pane active">
                    <label class="admin-label">Overview</label>
                    <textarea class="admin-textarea course-builder-field" data-target="overview" rows="5" placeholder="What is this course about? Who is it for? Introduce it in 2–4 sentences."></textarea>
                </div>
                <div id="pane-why_choose" class="builder-tab-pane">
                    <label class="admin-label">Why Choose This Course?</label>
                    <textarea class="admin-textarea course-builder-field" data-target="why_choose" rows="5" placeholder="What makes this course special? Mention key strengths, outcomes, and industry value."></textarea>
                </div>
                <div id="pane-eligibility" class="builder-tab-pane">
                    <label class="admin-label">Eligibility Criteria</label>
                    <textarea class="admin-textarea course-builder-field" data-target="eligibility" rows="5" placeholder="Who can apply? List academic qualifications, age limits, or other entry criteria."></textarea>
                </div>
                <div id="pane-career" class="builder-tab-pane">
                    <label class="admin-label">Career Opportunities</label>
                    <textarea class="admin-textarea course-builder-field" data-target="career" rows="5" placeholder="List jobs, fields, and further study options graduates can pursue."></textarea>
                </div>
                <div id="pane-admission" class="builder-tab-pane">
                    <label class="admin-label">Admission Process</label>
                    <textarea class="admin-textarea course-builder-field" data-target="admission" rows="5" placeholder="Step-by-step: documents required, entrance test, interview, enrolment process."></textarea>
                </div>
                <div id="pane-support" class="builder-tab-pane">
                    <label class="admin-label">Scholarship / Student Support</label>
                    <textarea class="admin-textarea course-builder-field" data-target="support" rows="5" placeholder="Mention scholarships, labs, mentoring programs, internship opportunities, etc."></textarea>
                </div>

                {{-- Tab navigation --}}
                <div class="builder-nav-row">
                    <button type="button" class="btn-admin btn-admin-sm btn-admin-outline" id="tabPrevBtn" style="visibility:hidden;"><i class="bi bi-chevron-left"></i> Previous</button>
                    <span id="tabCounter" style="font-size:12px;color:var(--admin-muted);font-weight:600;">1 / 6</span>
                    <button type="button" class="btn-admin btn-admin-sm btn-admin-outline" id="tabNextBtn">Next <i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
        </div>

        {{-- Step 2: FAQs --}}
        <div class="admin-card mb-3">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-question-circle-fill"></i> Course FAQs</span>
                <button type="button" class="btn-admin btn-admin-sm btn-admin-outline" id="appendFaqBtn"><i class="bi bi-plus-lg"></i> Add FAQ</button>
            </div>
            <div class="admin-card-body">
                <p style="font-size:12.5px;color:var(--admin-muted);margin-bottom:14px;">Add common questions students ask. These will be included when you generate the description.</p>
                <div id="courseFaqList">
                    {{-- FAQ items injected here --}}
                </div>
                <div id="faqEmptyMsg" style="text-align:center;padding:18px;color:var(--admin-muted);font-size:13px;">
                    <i class="bi bi-chat-dots" style="font-size:22px;display:block;margin-bottom:6px;"></i>No FAQs added yet. Click <strong>Add FAQ</strong> to add one.
                </div>
            </div>
        </div>

        {{-- Generate button between builder and editor --}}
        <div class="generate-btn-wrap">
            <div class="generate-divider"></div>
            <button type="button" class="btn-generate" id="insertCourseTemplateBtn">
                <i class="bi bi-magic"></i> Generate Full Description
            </button>
            <div class="generate-divider"></div>
        </div>

        {{-- Step 3: Full Description Editor --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="card-title"><i class="bi bi-file-richtext-fill"></i> Full Course Description</span>
                <div style="display:flex;gap:8px;">
                    <button type="button" class="btn-admin btn-admin-sm btn-admin-primary" id="visualCourseEditorBtn"><i class="bi bi-eye"></i> Visual</button>
                    <button type="button" class="btn-admin btn-admin-sm btn-admin-outline" id="htmlCourseEditorBtn"><i class="bi bi-code-slash"></i> HTML</button>
                </div>
            </div>
            <div class="admin-card-body" style="padding:0;">
                <div id="courseVisualWrap">
                    <textarea id="courseSummernote" name="fulldescription">{{ $courseData['fulldescription'] }}</textarea>
                </div>
                <div id="courseHtmlEditorWrap" class="d-none" style="padding:16px;">
                    <label class="admin-label">Raw HTML Source</label>
                    <textarea id="courseHtmlSourceEditor" class="admin-textarea" rows="18">{{ $courseData['fulldescription'] }}</textarea>
                </div>
            </div>
        </div>

    </div>
</div>

<div style="margin-top:24px;padding-bottom:8px;">
    <button type="submit" class="btn-admin btn-admin-primary" style="padding:10px 30px;font-size:14.5px;">
        <i class="bi bi-check-lg"></i> {{ $buttonText }}
    </button>
</div>
</form>

{{-- FAQ Template --}}
<template id="courseFaqTemplate">
    <div class="faq-item-card course-faq-item">
        <div class="row g-3 align-items-start">
            <div class="col-md-5">
                <label class="admin-label">Question</label>
                <input type="text" class="admin-input course-faq-question" placeholder="e.g. Is this course suitable after +2?">
            </div>
            <div class="col-md-6">
                <label class="admin-label">Answer</label>
                <textarea class="admin-textarea course-faq-answer" rows="2" placeholder="Write the answer clearly."></textarea>
            </div>
            <div class="col-md-1 d-flex align-items-end" style="padding-top:22px;">
                <button type="button" class="btn-admin btn-admin-sm btn-admin-danger remove-course-faq" title="Remove" style="width:100%;"><i class="bi bi-trash3"></i></button>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
// ── Summernote init ──────────────────────────────
const courseInitialHtml = @json($courseData['fulldescription']);
$('#courseSummernote').summernote({
    placeholder: 'Course description will appear here after you click "Generate Full Description".',
    tabsize: 2, height: 460,
    codeviewFilter: false, codeviewIframeFilter: false,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['codeview', 'help']]
    ]
});
$('#courseSummernote').summernote('code', courseInitialHtml);

// ── Refs ─────────────────────────────────────────
const courseSummernote     = $('#courseSummernote');
const courseHtmlEditorWrap = document.getElementById('courseHtmlEditorWrap');
const courseHtmlSourceEditor = document.getElementById('courseHtmlSourceEditor');
const visualCourseEditorBtn  = document.getElementById('visualCourseEditorBtn');
const htmlCourseEditorBtn    = document.getElementById('htmlCourseEditorBtn');
const insertCourseTemplateBtn = document.getElementById('insertCourseTemplateBtn');
const appendFaqBtn           = document.getElementById('appendFaqBtn');
const courseFaqList          = document.getElementById('courseFaqList');
const courseFaqTemplate      = document.getElementById('courseFaqTemplate');
const faqEmptyMsg            = document.getElementById('faqEmptyMsg');

// ── Tab system ───────────────────────────────────
const tabOrder = ['overview','why_choose','eligibility','career','admission','support'];
let currentTabIndex = 0;

const tabBtns  = document.querySelectorAll('.builder-tab-btn');
const tabPanes = document.querySelectorAll('.builder-tab-pane');
const tabPrevBtn  = document.getElementById('tabPrevBtn');
const tabNextBtn  = document.getElementById('tabNextBtn');
const tabCounter  = document.getElementById('tabCounter');

function switchTab(index) {
    currentTabIndex = index;
    tabBtns.forEach((btn, i) => btn.classList.toggle('active', i === index));
    tabPanes.forEach((pane, i) => pane.classList.toggle('active', i === index));
    tabPrevBtn.style.visibility = index === 0 ? 'hidden' : 'visible';
    tabNextBtn.textContent = '';
    if (index === tabOrder.length - 1) {
        tabNextBtn.innerHTML = 'Done <i class="bi bi-check2"></i>';
    } else {
        tabNextBtn.innerHTML = 'Next <i class="bi bi-chevron-right"></i>';
    }
    tabCounter.textContent = (index + 1) + ' / ' + tabOrder.length;
}

tabBtns.forEach((btn, i) => btn.addEventListener('click', () => switchTab(i)));
tabPrevBtn.addEventListener('click', () => { if (currentTabIndex > 0) switchTab(currentTabIndex - 1); });
tabNextBtn.addEventListener('click', () => { if (currentTabIndex < tabOrder.length - 1) switchTab(currentTabIndex + 1); });

// Mark tab filled + progress dots
document.querySelectorAll('.course-builder-field').forEach(ta => {
    ta.addEventListener('input', function() {
        const target = this.dataset.target;
        const idx = tabOrder.indexOf(target);
        const filled = this.value.trim().length > 0;
        if (tabBtns[idx]) tabBtns[idx].classList.toggle('filled', filled);
        const dot = document.querySelector('.section-progress-dot[data-section="' + target + '"]');
        if (dot) dot.classList.toggle('done', filled);
    });
});

switchTab(0);

// ── Visual / HTML editor toggle ───────────────────
const setCourseEditorMode = (mode) => {
    if (mode === 'html') {
        courseHtmlEditorWrap.classList.remove('d-none');
        courseHtmlSourceEditor.value = courseSummernote.summernote('code');
        htmlCourseEditorBtn.classList.replace('btn-admin-outline','btn-admin-primary');
        visualCourseEditorBtn.classList.replace('btn-admin-primary','btn-admin-outline');
    } else {
        courseHtmlEditorWrap.classList.add('d-none');
        visualCourseEditorBtn.classList.replace('btn-admin-outline','btn-admin-primary');
        htmlCourseEditorBtn.classList.replace('btn-admin-primary','btn-admin-outline');
    }
};
visualCourseEditorBtn.addEventListener('click', () => setCourseEditorMode('visual'));
htmlCourseEditorBtn.addEventListener('click', () => setCourseEditorMode('html'));

// ── Helpers ──────────────────────────────────────
const escapeHtml = v => { const d = document.createElement('div'); d.textContent = v||''; return d.innerHTML; };
const paragraphize = v => escapeHtml(v).replace(/\n/g,'<br>');

const faqHtml = () => {
    const items = [...document.querySelectorAll('.course-faq-item')];
    if (!items.length) return '';
    const blocks = items.map(item => {
        const q = item.querySelector('.course-faq-question').value.trim();
        const a = item.querySelector('.course-faq-answer').value.trim();
        if (!q || !a) return '';
        return `<div style="margin-bottom:18px;padding:18px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
            <h4 style="margin:0 0 8px;color:#14532d;font-size:18px;">${escapeHtml(q)}</h4>
            <p style="margin:0;line-height:1.8;color:#475569;">${paragraphize(a)}</p>
        </div>`;
    }).filter(Boolean).join('');
    return blocks ? `<h3>Frequently Asked Questions</h3>${blocks}` : '';
};

const buildCourseTemplateHtml = () => {
    const name        = document.querySelector('input[name="name"]').value.trim() || 'This Course';
    const duration    = document.querySelector('input[name="duration"]').value.trim();
    const semester    = document.querySelector('input[name="semester"]').value.trim();
    const requirement = document.querySelector('select[name="requirement"]').value.trim();
    const startTime   = document.querySelector('input[name="starting_time"]').value.trim();
    const closeTime   = document.querySelector('input[name="closing_time"]').value.trim();
    const sec = {};
    document.querySelectorAll('.course-builder-field').forEach(ta => { sec[ta.dataset.target] = ta.value.trim(); });

    return `<h2>${escapeHtml(name)}</h2>
<p>${paragraphize(sec.overview || 'Write a strong introduction to this course here.')}</p>
<h3>Quick Highlights</h3>
<ul>
  ${duration ? `<li><strong>Duration:</strong> ${escapeHtml(duration)}</li>` : ''}
  ${semester ? `<li><strong>Structure:</strong> ${escapeHtml(semester)}</li>` : ''}
  ${requirement ? `<li><strong>Eligibility:</strong> ${escapeHtml(requirement)}</li>` : ''}
  ${(startTime||closeTime) ? `<li><strong>Class Time:</strong> ${escapeHtml(startTime||'-')} – ${escapeHtml(closeTime||'-')}</li>` : ''}
</ul>
<h3>Why Choose This Course?</h3>
<p>${paragraphize(sec.why_choose || 'Explain why students should choose this course.')}</p>
<h3>Eligibility Criteria</h3>
<p>${paragraphize(sec.eligibility || 'Explain academic requirements, entry criteria, and who should apply.')}</p>
<h3>Career Opportunities</h3>
<p>${paragraphize(sec.career || 'Describe possible jobs, higher studies, and long-term prospects.')}</p>
<h3>Admission Process</h3>
<p>${paragraphize(sec.admission || 'List the admission steps, required documents, and selection process.')}</p>
<h3>Student Support and Learning Environment</h3>
<p>${paragraphize(sec.support || 'Mention labs, faculty support, scholarships, mentoring, internships, or project work.')}</p>
${faqHtml()}`;
};

// ── Generate button ───────────────────────────────
insertCourseTemplateBtn.addEventListener('click', () => {
    const html = buildCourseTemplateHtml();
    courseSummernote.summernote('code', html);
    courseHtmlSourceEditor.value = html;
    setCourseEditorMode('visual');
    insertCourseTemplateBtn.innerHTML = '<i class="bi bi-check2-circle"></i> Generated!';
    setTimeout(() => { insertCourseTemplateBtn.innerHTML = '<i class="bi bi-magic"></i> Generate Full Description'; }, 2000);
    insertCourseTemplateBtn.closest('.admin-card') || document.getElementById('courseSummernote')?.scrollIntoView({behavior:'smooth',block:'start'});
});

// ── FAQ management ────────────────────────────────
const updateFaqEmpty = () => {
    faqEmptyMsg.style.display = document.querySelectorAll('.course-faq-item').length ? 'none' : 'block';
};

const addFaqItem = (question='', answer='') => {
    const fragment = courseFaqTemplate.content.cloneNode(true);
    const item = fragment.querySelector('.course-faq-item');
    item.querySelector('.course-faq-question').value = question;
    item.querySelector('.course-faq-answer').value = answer;
    item.querySelector('.remove-course-faq').addEventListener('click', () => { item.remove(); updateFaqEmpty(); });
    courseFaqList.appendChild(fragment);
    updateFaqEmpty();
};

appendFaqBtn.addEventListener('click', () => addFaqItem());

// ── Form submit ───────────────────────────────────
document.getElementById('courseForm').addEventListener('submit', function() {
    if (!courseHtmlEditorWrap.classList.contains('d-none')) {
        courseSummernote.summernote('code', courseHtmlSourceEditor.value);
    }
});

// ── Init ──────────────────────────────────────────
addFaqItem('What kind of students should choose this course?', 'Add a short answer here for students.');
setCourseEditorMode('visual');
updateFaqEmpty();
</script>
@endpush


