@extends('backend.pages.layout.master')
@section('title', isset($template) ? 'Edit Template' : 'Create Template')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">
<style>
    #gjs {
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }
    .panel__top {
        padding: 0;
        width: 100%;
        display: flex;
        position: initial;
        justify-content: center;
        justify-content: space-between;
    }
    .panel__basic-actions {
        position: initial;
    }
</style>
@endpush

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">{{ isset($template) ? 'Edit ID Card Template' : 'Create ID Card Template' }}</h5>
        <p class="text-muted small mb-0">Use the visual builder to drag and drop elements, or edit the raw HTML/CSS.</p>
    </div>
    <a href="{{ route('sms.id-cards.templates.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Templates
    </a>
</div>

<form action="{{ isset($template) ? route('sms.id-cards.templates.update', $template->id) : route('sms.id-cards.templates.store') }}" method="POST" id="templateForm">
    @csrf
    @if(isset($template))
        @method('PUT')
    @endif
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $template->name ?? old('name') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Card Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="student" {{ (isset($template) && $template->type == 'student') ? 'selected' : '' }}>Student</option>
                        <option value="staff" {{ (isset($template) && $template->type == 'staff') ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Layout <span class="text-danger">*</span></label>
                    <select name="layout" class="form-select" required>
                        <option value="portrait" {{ (isset($template) && $template->layout == 'portrait') ? 'selected' : '' }}>Portrait (Vertical)</option>
                        <option value="landscape" {{ (isset($template) && $template->layout == 'landscape') ? 'selected' : '' }}>Landscape (Horizontal)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end pb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_default" id="is_default" value="1" {{ (isset($template) && $template->is_default) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_default">Set as Default Theme</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="bi bi-palette text-primary me-2"></i>Visual Template Designer</h6>
            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#placeholdersModal">
                <i class="bi bi-code-slash me-1"></i> View Data Placeholders
            </button>
        </div>
        <div class="card-body p-0">
            <div id="gjs" style="height: 600px;">
                {!! $template->html_content ?? '<div style="padding:20px; text-align:center; border:1px solid #ccc; width:100%; height:100%;"><h3>New ID Card Template</h3><p>Drag elements here or edit HTML</p></div>' !!}
                <style>
                    {!! $template->css_content ?? '' !!}
                </style>
            </div>
        </div>
    </div>
    
    <input type="hidden" name="html_content" id="html_content">
    <input type="hidden" name="css_content" id="css_content">

    <div class="text-end mb-5">
        <button type="button" id="saveTemplateBtn" class="btn btn-primary px-4 btn-lg shadow-sm">
            <i class="bi bi-save me-2"></i> Save Template Design
        </button>
    </div>
</form>

<!-- Placeholders Modal -->
<div class="modal fade" id="placeholdersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Available Data Placeholders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3">Copy and paste these exact placeholders into your design. They will be automatically replaced with real student/staff data during generation.</p>
                <ul class="list-group list-group-flush font-monospace small">
                    <li class="list-group-item d-flex justify-content-between"><span>Student/Staff Name</span> <strong>[FULL_NAME]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Admission / Emp No</span> <strong>[ID_NO]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Class Name</span> <strong>[CLASS_NAME]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Section Name</span> <strong>[SECTION_NAME]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Roll Number</span> <strong>[ROLL_NO]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Blood Group</span> <strong>[BLOOD_GROUP]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Emergency Phone</span> <strong>[PHONE]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Date of Birth</span> <strong>[DOB]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Photo URL</span> <strong>[PHOTO_URL]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>School Name</span> <strong>[SCHOOL_NAME]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>School Address</span> <strong>[SCHOOL_ADDRESS]</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>School Logo URL</span> <strong>[SCHOOL_LOGO]</strong></li>
                </ul>
                <div class="alert alert-info mt-3 small">
                    <i class="bi bi-info-circle me-1"></i> For Barcodes and QR Codes, the system will append them automatically in standard print layouts, or you can leave space for them at the bottom.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/grapesjs"></script>
<script>
$(document).ready(function() {
    const editor = grapesjs.init({
        container: '#gjs',
        height: '600px',
        width: 'auto',
        storageManager: false, // We'll handle saving manually
        panels: { defaults: [] }, // We will rely on default panels built-in to typical presets if needed, or simple UI
        plugins: ['gjs-preset-webpage'],
        pluginsOpts: {
            'gjs-preset-webpage': {
                // options
            }
        },
        blockManager: {
            appendTo: '#blocks',
            blocks: [
                {
                    id: 'image',
                    label: 'Image',
                    select: true,
                    content: { type: 'image' },
                    activate: true,
                },
                {
                    id: 'text',
                    label: 'Text',
                    content: '<div data-gjs-type="text">Insert your text here</div>',
                },
                {
                    id: 'photo-placeholder',
                    label: 'Student Photo',
                    content: '<img src="[PHOTO_URL]" alt="Student Photo" style="width:70px; height:75px; object-fit:cover; border-radius:4px; border:2px solid #ccc; background:#e5e7eb;" />',
                },
                {
                    id: 'name-placeholder',
                    label: 'Student Name',
                    content: '<h4 style="margin: 0; font-size:14px; font-weight:bold; color:#333;">[FULL_NAME]</h4>',
                },
                {
                    id: 'info-table',
                    label: 'Info Table',
                    content: `<table style="width:100%; font-size:10px; text-align:left; line-height:1.4;">
                        <tr><td style="color:#666; width:40%;">ID No:</td><td style="font-weight:bold;">[ID_NO]</td></tr>
                        <tr><td style="color:#666;">Class:</td><td style="font-weight:bold;">[CLASS_NAME] - [SECTION_NAME]</td></tr>
                        <tr><td style="color:#666;">Blood Grp:</td><td style="font-weight:bold; color:red;">[BLOOD_GROUP]</td></tr>
                        <tr><td style="color:#666;">Contact:</td><td style="font-weight:bold;">[PHONE]</td></tr>
                    </table>`
                }
            ]
        }
    });
    
    // Add basic panel buttons
    editor.Panels.addPanel({
        id: 'panel-top',
        el: '.panel__top',
    });
    editor.Panels.addPanel({
        id: 'basic-actions',
        el: '.panel__basic-actions',
        buttons: [
            {
                id: 'visibility',
                active: true,
                className: 'btn-toggle-borders',
                label: '<u>B</u>',
                command: 'sw-visibility',
            },
            {
                id: 'export',
                className: 'btn-open-export',
                label: 'Exp',
                command: 'export-template',
                context: 'export-template',
            },
            {
                id: 'show-json',
                className: 'btn-show-json',
                label: 'JSON',
                context: 'show-json',
                command(editor) {
                    editor.Modal.setTitle('Components JSON')
                    .setContent(`<textarea style="width:100%; height: 250px;">
                        ${JSON.stringify(editor.getComponents())}
                    </textarea>`)
                    .open();
                },
            }
        ],
    });

    $('#saveTemplateBtn').on('click', function(e) {
        e.preventDefault();
        
        // Get HTML and CSS from GrapesJS
        const html = editor.getHtml();
        const css = editor.getCss();
        
        $('#html_content').val(html);
        $('#css_content').val(css);
        
        $('#templateForm').submit();
    });
});
</script>
<!-- Include the preset web page plugin -->
<script src="https://unpkg.com/grapesjs-preset-webpage"></script>
@endpush
