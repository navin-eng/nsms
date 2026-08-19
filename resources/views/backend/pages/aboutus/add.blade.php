@extends('backend.pages.layout.master')
@push('b-title', 'About Us')

@section('backend-content')
    @php
        $condition = !is_null($aboutus);
        $contentAction = $condition ? route('aboutus.update', $aboutus->id) : route('aboutus.store');
    @endphp

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="about-editor-shell card border-0 shadow-sm overflow-hidden">
                <div class="about-editor-hero card-body border-bottom">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                        <div>
                            <span class="about-editor-kicker">Content Studio</span>
                            <h3 class="about-editor-title mb-2">Redesigned About Us Editor</h3>
                            <p class="text-muted mb-0">Create a more informative About Us page with visual editing, raw HTML editing, and a live preview for students and parents.</p>
                        </div>
                        <div class="about-editor-badges">
                            <span class="about-editor-badge"><i class="bi bi-code-slash"></i> HTML Ready</span>
                            <span class="about-editor-badge"><i class="bi bi-eye"></i> Live Preview</span>
                            <span class="about-editor-badge"><i class="bi bi-brush"></i> Visual Builder</span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ $contentAction }}" method="POST" id="aboutContentForm">
                        @csrf

                        <div class="row g-4">
                            <div class="col-xl-8">
                                <div class="editor-workspace">
                                    <div class="editor-toolbar-row">
                                        <div class="editor-mode-switch">
                                            <button type="button" class="btn btn-primary btn-sm" id="visualEditorBtn">
                                                <i class="bi bi-stars me-1"></i> Visual Editor
                                            </button>
                                            <button type="button" class="btn btn-outline-dark btn-sm" id="htmlEditorBtn">
                                                <i class="bi bi-code-square me-1"></i> HTML Source
                                            </button>
                                        </div>
                                        <div class="editor-action-switch">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="loadTemplateBtn">
                                                <i class="bi bi-magic me-1"></i> Insert Informative Template
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="syncToHtmlBtn">
                                                <i class="bi bi-arrow-left-right me-1"></i> Sync Visual to HTML
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="syncToVisualBtn">
                                                <i class="bi bi-arrow-left-right me-1"></i> Sync HTML to Visual
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" id="refreshPreviewBtn">
                                                <i class="bi bi-eye me-1"></i> Refresh Preview
                                            </button>
                                        </div>
                                    </div>

                                    <div class="editor-surface">
                                        <div id="visualEditorWrap">
                                            <textarea id="summernote" name="desc">{{ old('desc', $aboutus->desc ?? '') }}</textarea>
                                        </div>

                                        <div id="htmlEditorWrap" class="d-none">
                                            <label class="form-label fw-semibold mb-2">Raw HTML Source</label>
                                            <textarea id="htmlSourceEditor" class="form-control editor-code-surface" rows="22" spellcheck="false">{{ old('desc', $aboutus->desc ?? '') }}</textarea>
                                            <small class="text-muted d-block mt-2">Paste complete HTML, inline styles, embed blocks, or custom sections here. This editor now supports direct HTML authoring similar to WordPress and Blogger code mode.</small>
                                        </div>
                                    </div>

                                    <div class="editor-footer-note">
                                        <i class="bi bi-info-circle"></i>
                                        <span>Tip: Use the HTML mode for advanced layouts, then switch back to visual mode to fine-tune text and media.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="editor-side-panel">
                                    <div class="editor-panel-card">
                                        <h5>Live Preview</h5>
                                        <p class="text-muted mb-3">Preview how the About Us content will render on the public page before saving.</p>
                                        <div id="aboutPreviewPanel" class="about-preview-panel"></div>
                                    </div>

                                    <div class="editor-panel-card">
                                        <h5>What To Include</h5>
                                        <ul class="editor-checklist">
                                            <li>College overview and mission</li>
                                            <li>Programs, affiliation, and facilities</li>
                                            <li>Admission support and student services</li>
                                            <li>Career outcomes, labs, and activities</li>
                                            <li>Strong FAQs for common student questions</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Save About Us
                            </button>
                            <button type="button" class="btn btn-light border" id="previewFromCurrentModeBtn">
                                <i class="bi bi-display me-1"></i> Preview Current Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">Manage FAQs</h4>
                    <p class="text-muted mb-0">These FAQs appear on the public About Us page for students. You can add, edit, delete, hide, and show them here.</p>
                </div>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addFaqModal">+ Add FAQ</button>
            </div>

            @forelse($faqs as $faq)
                @if($loop->first)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th style="width: 90px;">Order</th>
                                    <th style="width: 110px;">Status</th>
                                    <th style="width: 260px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                @endif
                                <tr>
                                    <td>{{ $faq->id }}</td>
                                    <td class="fw-semibold">{{ $faq->question }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($faq->answer, 120) }}</td>
                                    <td>{{ $faq->sort_order }}</td>
                                    <td>
                                        <span class="badge {{ $faq->status ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $faq->status ? 'Visible' : 'Hidden' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editFaqModal{{ $faq->id }}">Edit</button>
                                            <a href="{{ route('aboutus.faq.status', $faq->id) }}" class="btn btn-sm {{ $faq->status ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                {{ $faq->status ? 'Hide' : 'Show' }}
                                            </a>
                                            <a href="{{ route('aboutus.faq.destroy', $faq->id) }}" class="btn btn-danger btn-sm deleteBtn" data-href="{{ route('aboutus.faq.destroy', $faq->id) }}">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                @if($loop->last)
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                <p class="text-muted mb-0">No FAQs added yet.</p>
            @endforelse
        </div>
    </div>

    <div class="modal fade" id="addFaqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('aboutus.faq.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Question</label>
                            <input type="text" name="question" class="form-control" placeholder="Example: Is GPLC affiliated with Lincoln University Malaysia?" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Answer</label>
                            <textarea name="answer" class="form-control" rows="6" placeholder="Write a clear answer for students..." required></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Display Order</label>
                            <input type="text" name="sort_order" class="form-control no-spinner" inputmode="numeric" pattern="[0-9]*" value="{{ old('sort_order', $faqs->count() + 1) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Add FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($faqs as $faq)
        <div class="modal fade" id="editFaqModal{{ $faq->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('aboutus.faq.update', $faq->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit FAQ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Question</label>
                                <input type="text" name="question" class="form-control" value="{{ $faq->question }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Answer</label>
                                <textarea name="answer" class="form-control" rows="6" required>{{ $faq->answer }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Display Order</label>
                                <input type="text" name="sort_order" class="form-control no-spinner" inputmode="numeric" pattern="[0-9]*" value="{{ $faq->sort_order }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@push('styles')
    <style>
        .about-editor-shell {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .about-editor-hero {
            background:
                radial-gradient(circle at top right, rgba(13, 122, 62, 0.10), transparent 26%),
                linear-gradient(135deg, #f9fffb 0%, #f5f8ff 100%);
        }

        .about-editor-kicker {
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

        .about-editor-title {
            font-size: 2rem;
            font-weight: 800;
            color: #132238;
        }

        .about-editor-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .about-editor-badge {
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

        .editor-workspace,
        .editor-panel-card {
            background: #fff;
            border: 1px solid #e8eef5;
            border-radius: 20px;
            box-shadow: 0 12px 35px rgba(19, 34, 56, 0.06);
        }

        .editor-workspace {
            padding: 22px;
        }

        .editor-toolbar-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .editor-mode-switch,
        .editor-action-switch {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .editor-surface {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e8eef5;
            background: #fff;
        }

        .editor-code-surface {
            border: 0;
            border-radius: 0;
            min-height: 560px;
            font-family: Consolas, "Courier New", monospace;
            font-size: 14px;
            line-height: 1.7;
            color: #17324d;
            background: #fbfdff;
        }

        .editor-footer-note {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-top: 14px;
            color: #57718f;
            font-size: 14px;
        }

        .editor-footer-note i {
            color: #0d6efd;
            margin-top: 2px;
        }

        .editor-side-panel {
            display: grid;
            gap: 18px;
        }

        .editor-panel-card {
            padding: 22px;
        }

        .editor-panel-card h5 {
            font-weight: 800;
            color: #132238;
            margin-bottom: 10px;
        }

        .about-preview-panel {
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

        .about-preview-panel h1,
        .about-preview-panel h2,
        .about-preview-panel h3,
        .about-preview-panel h4,
        .about-preview-panel h5,
        .about-preview-panel h6 {
            color: #10243e;
            font-weight: 800;
        }

        .editor-checklist {
            margin: 0;
            padding-left: 1.2rem;
            color: #425d7d;
            line-height: 1.85;
        }

        input.no-spinner::-webkit-outer-spin-button,
        input.no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input.no-spinner {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const initialAboutHtml = @json(old('desc', $aboutus->desc ?? ''));
        const starterTemplate = `<section class="gplc-about-block">
  <style>
    .gplc-about-block {font-family: Arial, sans-serif; color: #17324d; line-height: 1.8;}
    .gplc-about-hero {padding: 28px; border-radius: 20px; background: linear-gradient(135deg, #eefbf3 0%, #f4f8ff 100%); margin-bottom: 24px;}
    .gplc-about-grid {display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin: 24px 0;}
    .gplc-about-card {padding: 18px; border-radius: 16px; background: #ffffff; border: 1px solid #e6edf5; box-shadow: 0 10px 24px rgba(19, 34, 56, 0.06);}
    .gplc-about-highlight {margin-top: 14px; padding-left: 18px;}
  </style>
  <div class="gplc-about-hero">
    <h2>Welcome to Green Peace Lincoln College</h2>
    <p>Introduce the college with a clear and trustworthy overview. Explain what makes the institution valuable for students, guardians, and future applicants.</p>
  </div>
  <div class="gplc-about-grid">
    <div class="gplc-about-card">
      <h3>Our Mission</h3>
      <p>Write how the college supports academic excellence, practical learning, and student growth.</p>
    </div>
    <div class="gplc-about-card">
      <h3>Why Students Choose Us</h3>
      <p>Describe facilities, teaching quality, affiliation, scholarships, industry exposure, and support systems.</p>
    </div>
    <div class="gplc-about-card">
      <h3>Student Support</h3>
      <p>Explain admission guidance, counseling, library access, labs, career help, internships, and extracurricular opportunities.</p>
    </div>
  </div>
  <h3>What Students Usually Want to Know</h3>
  <ul class="gplc-about-highlight">
    <li>Affiliation and program recognition</li>
    <li>Admission requirements and process</li>
    <li>Facilities, labs, transportation, and hostel details</li>
    <li>Scholarship opportunities and fee support</li>
    <li>Career pathways and further study options</li>
  </ul>
</section>`;

        $('#summernote').summernote({
            placeholder: 'Write a strong, informative About Us page for students and parents',
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
                ['insert', ['hr']],
                ['misc', ['fullscreen', 'codeview']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']]
            ]
        });

        $('#summernote').summernote('code', initialAboutHtml);

        const summernoteEl = $('#summernote');
        const htmlEditorWrap = document.getElementById('htmlEditorWrap');
        const htmlSourceEditor = document.getElementById('htmlSourceEditor');
        const visualEditorBtn = document.getElementById('visualEditorBtn');
        const htmlEditorBtn = document.getElementById('htmlEditorBtn');
        const syncToHtmlBtn = document.getElementById('syncToHtmlBtn');
        const syncToVisualBtn = document.getElementById('syncToVisualBtn');
        const loadTemplateBtn = document.getElementById('loadTemplateBtn');
        const refreshPreviewBtn = document.getElementById('refreshPreviewBtn');
        const previewFromCurrentModeBtn = document.getElementById('previewFromCurrentModeBtn');
        const aboutPreviewPanel = document.getElementById('aboutPreviewPanel');
        const visualEditorWrap = document.getElementById('visualEditorWrap');

        const setEditorMode = (mode) => {
            if (mode === 'html') {
                htmlEditorWrap.classList.remove('d-none');
                visualEditorWrap.classList.add('d-none');
                htmlSourceEditor.value = summernoteEl.summernote('code');
                htmlEditorBtn.classList.remove('btn-outline-dark');
                htmlEditorBtn.classList.add('btn-dark');
                visualEditorBtn.classList.remove('btn-primary');
                visualEditorBtn.classList.add('btn-outline-primary');
            } else {
                htmlEditorWrap.classList.add('d-none');
                visualEditorWrap.classList.remove('d-none');
                visualEditorBtn.classList.remove('btn-outline-primary');
                visualEditorBtn.classList.add('btn-primary');
                htmlEditorBtn.classList.remove('btn-dark');
                htmlEditorBtn.classList.add('btn-outline-dark');
            }
        };

        const renderPreview = (html) => {
            aboutPreviewPanel.innerHTML = html && html.trim() !== '' ? html : '<p class="text-muted mb-0">Start writing to preview your About Us content here.</p>';
        };

        const getCurrentEditorHtml = () => {
            return htmlEditorWrap.classList.contains('d-none')
                ? summernoteEl.summernote('code')
                : htmlSourceEditor.value;
        };

        visualEditorBtn.addEventListener('click', () => setEditorMode('visual'));
        htmlEditorBtn.addEventListener('click', () => setEditorMode('html'));

        syncToHtmlBtn.addEventListener('click', () => {
            htmlSourceEditor.value = summernoteEl.summernote('code');
            setEditorMode('html');
            renderPreview(htmlSourceEditor.value);
        });

        syncToVisualBtn.addEventListener('click', () => {
            summernoteEl.summernote('code', htmlSourceEditor.value);
            setEditorMode('visual');
            renderPreview(summernoteEl.summernote('code'));
        });

        loadTemplateBtn.addEventListener('click', () => {
            if (htmlEditorWrap.classList.contains('d-none')) {
                summernoteEl.summernote('code', starterTemplate);
            } else {
                htmlSourceEditor.value = starterTemplate;
            }
            renderPreview(getCurrentEditorHtml());
        });

        refreshPreviewBtn.addEventListener('click', () => renderPreview(getCurrentEditorHtml()));
        previewFromCurrentModeBtn.addEventListener('click', () => renderPreview(getCurrentEditorHtml()));

        htmlSourceEditor.addEventListener('input', () => {
            if (!htmlEditorWrap.classList.contains('d-none')) {
                renderPreview(htmlSourceEditor.value);
            }
        });

        $('#summernote').on('summernote.change', function(_, contents) {
            if (htmlEditorWrap.classList.contains('d-none')) {
                renderPreview(contents);
            }
        });

        document.getElementById('aboutContentForm').addEventListener('submit', function () {
            if (!htmlEditorWrap.classList.contains('d-none')) {
                summernoteEl.summernote('code', htmlSourceEditor.value);
            }
        });

        setEditorMode('visual');
        renderPreview(initialAboutHtml);
    </script>
@endpush
@endsection
