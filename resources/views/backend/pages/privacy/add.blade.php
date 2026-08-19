@extends('backend.pages.layout.master')
@push('b-title', 'Privacy & Policy')

@php
    $isEdit = !is_null($privacy ?? null);
    $formAction = $isEdit ? route('privacy.update', $privacy->id) : route('privacy.store');
    $initialPrivacyHtml = old('desc', $privacy->desc ?? '');
@endphp

@push('styles')
<style>
    .privacy-editor-shell {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    .privacy-editor-hero {
        background:
            radial-gradient(circle at top right, rgba(82, 183, 136, 0.14), transparent 30%),
            linear-gradient(135deg, #f9fffb 0%, #f5f8ff 100%);
    }

    .privacy-editor-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #eaf7ef;
        color: #0d7a3e;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .privacy-editor-title {
        font-size: 2rem;
        font-weight: 800;
        color: #132238;
    }

    .privacy-editor-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .privacy-editor-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid #e6edf5;
        color: #334a68;
        font-size: 13px;
        font-weight: 600;
    }

    .privacy-workspace,
    .privacy-panel-card {
        background: #fff;
        border: 1px solid #e8eef5;
        border-radius: 20px;
        box-shadow: 0 12px 35px rgba(19, 34, 56, 0.06);
    }

    .privacy-workspace {
        padding: 22px;
    }

    .privacy-toolbar-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 18px;
    }

    .privacy-mode-switch,
    .privacy-action-switch {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .privacy-surface {
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #e8eef5;
        background: #fff;
    }

    .privacy-code-surface {
        border: 0;
        border-radius: 0;
        min-height: 560px;
        font-family: Consolas, "Courier New", monospace;
        font-size: 14px;
        line-height: 1.7;
        color: #17324d;
        background: #fbfdff;
    }

    .privacy-footer-note {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        margin-top: 14px;
        color: #57718f;
        font-size: 14px;
    }

    .privacy-panel-grid {
        display: grid;
        gap: 18px;
    }

    .privacy-panel-card {
        padding: 22px;
    }

    .privacy-panel-card h5 {
        font-weight: 800;
        color: #132238;
        margin-bottom: 10px;
    }

    .privacy-preview-panel {
        min-height: 260px;
        max-height: 560px;
        overflow: auto;
        padding: 18px;
        border-radius: 18px;
        background: linear-gradient(180deg, #fbfcfe 0%, #f5f9fd 100%);
        border: 1px solid #e6edf5;
        color: #29405b;
        line-height: 1.75;
    }

    .privacy-preview-panel h1,
    .privacy-preview-panel h2,
    .privacy-preview-panel h3,
    .privacy-preview-panel h4,
    .privacy-preview-panel h5,
    .privacy-preview-panel h6 {
        color: #10243e;
        font-weight: 800;
    }

    .privacy-checklist {
        margin: 0;
        padding-left: 1.2rem;
        color: #425d7d;
        line-height: 1.85;
    }

    .note-editor.note-frame {
        border: none !important;
        border-radius: 0 !important;
    }

    .note-toolbar {
        background: var(--admin-bg) !important;
        border-bottom: 1px solid var(--admin-border) !important;
    }
</style>
@endpush

@section('backend-content')
<div class="privacy-editor-shell card border-0 shadow-sm overflow-hidden">
    <div class="privacy-editor-hero card-body border-bottom">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
            <div>
                <span class="privacy-editor-kicker">Policy Studio</span>
                <h3 class="privacy-editor-title mb-2">{{ $isEdit ? 'Manage Privacy & Policy' : 'Create Privacy & Policy' }}</h3>
                <p class="text-muted mb-0">Write a clear privacy policy with visual editing, raw HTML editing, and live preview support.</p>
            </div>
            <div class="privacy-editor-badges">
                <span class="privacy-editor-badge"><i class="bi bi-code-slash"></i> HTML Ready</span>
                <span class="privacy-editor-badge"><i class="bi bi-eye"></i> Live Preview</span>
                <span class="privacy-editor-badge"><i class="bi bi-shield-check"></i> Policy Writing</span>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        <form action="{{ $formAction }}" method="POST" id="privacyContentForm">
            @csrf

            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="privacy-workspace">
                        <div class="privacy-toolbar-row">
                            <div class="privacy-mode-switch">
                                <button type="button" class="btn btn-primary btn-sm" id="visualPrivacyEditorBtn">
                                    <i class="bi bi-stars me-1"></i> Visual Editor
                                </button>
                                <button type="button" class="btn btn-outline-dark btn-sm" id="htmlPrivacyEditorBtn">
                                    <i class="bi bi-code-square me-1"></i> HTML Source
                                </button>
                            </div>
                            <div class="privacy-action-switch">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="insertPrivacyTemplateBtn">
                                    <i class="bi bi-magic me-1"></i> Insert Policy Template
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="syncPrivacyToHtmlBtn">
                                    <i class="bi bi-arrow-left-right me-1"></i> Sync Visual to HTML
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="syncPrivacyToVisualBtn">
                                    <i class="bi bi-arrow-left-right me-1"></i> Sync HTML to Visual
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" id="refreshPrivacyPreviewBtn">
                                    <i class="bi bi-eye me-1"></i> Refresh Preview
                                </button>
                            </div>
                        </div>

                        <div class="privacy-surface">
                            <div id="privacyVisualWrap">
                                <textarea id="privacySummernote" name="desc">{{ $initialPrivacyHtml }}</textarea>
                            </div>

                            <div id="privacyHtmlEditorWrap" class="d-none">
                                <label class="form-label fw-semibold mb-2">Raw HTML Source</label>
                                <textarea id="privacyHtmlSourceEditor" class="form-control privacy-code-surface" rows="22" spellcheck="false">{{ $initialPrivacyHtml }}</textarea>
                                <small class="text-muted d-block mt-2">Paste full HTML, headings, lists, links, and inline styles here if you want complete control.</small>
                            </div>
                        </div>

                        <div class="privacy-footer-note">
                            <i class="bi bi-info-circle"></i>
                            <span>Use HTML mode for advanced structure, then switch back to visual mode if you want to polish the policy content visually.</span>
                        </div>

                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> {{ $isEdit ? 'Update Privacy Policy' : 'Save Privacy Policy' }}
                            </button>
                            <button type="button" class="btn btn-light border" id="previewPrivacyCurrentBtn">
                                <i class="bi bi-display me-1"></i> Preview Current Content
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="privacy-panel-grid">
                        <div class="privacy-panel-card">
                            <h5>Live Preview</h5>
                            <p class="text-muted mb-3">Check how the public privacy policy page content will look before saving.</p>
                            <div id="privacyPreviewPanel" class="privacy-preview-panel"></div>
                        </div>

                        <div class="privacy-panel-card">
                            <h5>What To Cover</h5>
                            <ul class="privacy-checklist">
                                <li>What information the college collects</li>
                                <li>How form submissions and admissions data are used</li>
                                <li>Communication, notifications, and student support</li>
                                <li>Data protection and limited sharing</li>
                                <li>Contact method for privacy requests and corrections</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const initialPrivacyHtml = @json($initialPrivacyHtml);
        const starterPrivacyTemplate = `<section class="college-privacy-policy">
  <h2>Privacy Policy</h2>
  <p>Explain how the college collects, uses, stores, and protects information provided by students, guardians, and website visitors.</p>
  <h3>Information We Collect</h3>
  <p>List the details collected through contact forms, admissions, academic communication, and official inquiries.</p>
  <h3>How We Use Information</h3>
  <p>Describe how the information is used for admissions, communication, notices, academic support, and service improvement.</p>
  <h3>Data Protection</h3>
  <p>Explain that the college takes reasonable measures to protect personal information and limits unauthorized access.</p>
  <h3>Third-Party Sharing</h3>
  <p>Clarify whether information is shared only when necessary for academic, administrative, or legal reasons.</p>
  <h3>Contact For Privacy Requests</h3>
  <p>Tell students and visitors how they can contact the college for corrections, clarification, or privacy-related requests.</p>
</section>`;

        $('#privacySummernote').summernote({
            placeholder: 'Write a clear, student-friendly privacy policy here.',
            tabsize: 2,
            height: 620,
            codeviewFilter: false,
            codeviewIframeFilter: false,
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

        $('#privacySummernote').summernote('code', initialPrivacyHtml);

        const privacySummernote = $('#privacySummernote');
        const privacyVisualWrap = document.getElementById('privacyVisualWrap');
        const privacyHtmlEditorWrap = document.getElementById('privacyHtmlEditorWrap');
        const privacyHtmlSourceEditor = document.getElementById('privacyHtmlSourceEditor');
        const visualPrivacyEditorBtn = document.getElementById('visualPrivacyEditorBtn');
        const htmlPrivacyEditorBtn = document.getElementById('htmlPrivacyEditorBtn');
        const insertPrivacyTemplateBtn = document.getElementById('insertPrivacyTemplateBtn');
        const syncPrivacyToHtmlBtn = document.getElementById('syncPrivacyToHtmlBtn');
        const syncPrivacyToVisualBtn = document.getElementById('syncPrivacyToVisualBtn');
        const refreshPrivacyPreviewBtn = document.getElementById('refreshPrivacyPreviewBtn');
        const previewPrivacyCurrentBtn = document.getElementById('previewPrivacyCurrentBtn');
        const privacyPreviewPanel = document.getElementById('privacyPreviewPanel');

        const setPrivacyEditorMode = (mode) => {
            if (mode === 'html') {
                privacyHtmlEditorWrap.classList.remove('d-none');
                privacyVisualWrap.classList.add('d-none');
                privacyHtmlSourceEditor.value = privacySummernote.summernote('code');
                htmlPrivacyEditorBtn.classList.remove('btn-outline-dark');
                htmlPrivacyEditorBtn.classList.add('btn-dark');
                visualPrivacyEditorBtn.classList.remove('btn-primary');
                visualPrivacyEditorBtn.classList.add('btn-outline-primary');
            } else {
                privacyHtmlEditorWrap.classList.add('d-none');
                privacyVisualWrap.classList.remove('d-none');
                visualPrivacyEditorBtn.classList.remove('btn-outline-primary');
                visualPrivacyEditorBtn.classList.add('btn-primary');
                htmlPrivacyEditorBtn.classList.remove('btn-dark');
                htmlPrivacyEditorBtn.classList.add('btn-outline-dark');
            }
        };

        const getCurrentPrivacyHtml = () => {
            return privacyHtmlEditorWrap.classList.contains('d-none')
                ? privacySummernote.summernote('code')
                : privacyHtmlSourceEditor.value;
        };

        const renderPrivacyPreview = (html) => {
            privacyPreviewPanel.innerHTML = html && html.trim() !== ''
                ? html
                : '<p class="text-muted mb-0">Start writing your privacy policy to preview it here.</p>';
        };

        visualPrivacyEditorBtn.addEventListener('click', () => setPrivacyEditorMode('visual'));
        htmlPrivacyEditorBtn.addEventListener('click', () => setPrivacyEditorMode('html'));

        syncPrivacyToHtmlBtn.addEventListener('click', () => {
            privacyHtmlSourceEditor.value = privacySummernote.summernote('code');
            setPrivacyEditorMode('html');
            renderPrivacyPreview(privacyHtmlSourceEditor.value);
        });

        syncPrivacyToVisualBtn.addEventListener('click', () => {
            privacySummernote.summernote('code', privacyHtmlSourceEditor.value);
            setPrivacyEditorMode('visual');
            renderPrivacyPreview(privacySummernote.summernote('code'));
        });

        insertPrivacyTemplateBtn.addEventListener('click', () => {
            if (privacyHtmlEditorWrap.classList.contains('d-none')) {
                privacySummernote.summernote('code', starterPrivacyTemplate);
            } else {
                privacyHtmlSourceEditor.value = starterPrivacyTemplate;
            }
            renderPrivacyPreview(getCurrentPrivacyHtml());
        });

        refreshPrivacyPreviewBtn.addEventListener('click', () => renderPrivacyPreview(getCurrentPrivacyHtml()));
        previewPrivacyCurrentBtn.addEventListener('click', () => renderPrivacyPreview(getCurrentPrivacyHtml()));

        privacyHtmlSourceEditor.addEventListener('input', () => {
            if (!privacyHtmlEditorWrap.classList.contains('d-none')) {
                renderPrivacyPreview(privacyHtmlSourceEditor.value);
            }
        });

        $('#privacySummernote').on('summernote.change', function(_, contents) {
            if (privacyHtmlEditorWrap.classList.contains('d-none')) {
                renderPrivacyPreview(contents);
            }
        });

        document.getElementById('privacyContentForm').addEventListener('submit', function() {
            if (!privacyHtmlEditorWrap.classList.contains('d-none')) {
                privacySummernote.summernote('code', privacyHtmlSourceEditor.value);
            }
        });

        setPrivacyEditorMode('visual');
        renderPrivacyPreview(initialPrivacyHtml);
    })();
</script>
@endpush

