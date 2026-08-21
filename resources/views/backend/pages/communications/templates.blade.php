@extends('backend.pages.layout.master')
@section('title', 'Communication Templates')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Message & Alert Templates</h5>
        <p class="text-muted small mb-0">Manage reusable templates for automated absence notifications, fee reminders, exam results, and bulk announcements.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
            <i class="bi bi-plus-lg me-1"></i>New Template
        </button>
        <a href="{{ route('admin.communications.compose') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-send me-1"></i>Compose Message
        </a>
    </div>
</div>

{{-- Summary Counters --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 fs-4">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $templates->where('type', 'sms')->count() }}</h5>
                    <small class="text-muted">SMS Alert Templates</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3 fs-4">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $templates->where('type', 'email')->count() }}</h5>
                    <small class="text-muted">Email Notification Templates</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-info bg-opacity-10 text-info rounded-3 fs-4">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $templates->where('type', 'push')->count() }}</h5>
                    <small class="text-muted">Push Alert Templates</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($templates->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light text-muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">
                    <tr>
                        <th class="ps-4" style="width: 25%;">Template Name</th>
                        <th style="width: 10%;">Channel</th>
                        <th style="width: 45%;">Template Preview</th>
                        <th style="width: 10%;">Status</th>
                        <th class="text-end pe-4" style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $template->name }}</div>
                            <small class="text-muted">Updated {{ $template->updated_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($template->type == 'sms')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"><i class="bi bi-chat-text me-1"></i>SMS</span>
                            @elseif($template->type == 'email')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="bi bi-envelope me-1"></i>Email</span>
                            @elseif($template->type == 'push')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1"><i class="bi bi-bell me-1"></i>Push</span>
                            @endif
                        </td>
                        <td>
                            @if($template->subject)
                                <div class="fw-semibold text-dark small mb-1"><span class="text-muted fw-normal">Subject:</span> {{ $template->subject }}</div>
                            @endif
                            <div class="text-muted small" style="white-space: normal; line-height: 1.4;">
                                {{ $template->body }}
                            </div>
                        </td>
                        <td>
                            @if($template->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-light border btn-edit"
                                    data-id="{{ $template->id }}"
                                    data-name="{{ $template->name }}"
                                    data-type="{{ $template->type }}"
                                    data-subject="{{ $template->subject }}"
                                    data-body="{{ $template->body }}"
                                    data-active="{{ $template->is_active ? '1' : '0' }}"
                                    title="Edit Template">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <button type="button" class="btn btn-light border btn-delete"
                                    data-id="{{ $template->id }}"
                                    data-name="{{ $template->name }}"
                                    title="Delete Template">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-layout-text-window fs-1 d-block mb-3 opacity-50"></i>
            <h6>No Templates Found</h6>
            <p class="small mb-0">Create customized message templates for automated notifications.</p>
            <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createTemplateModal">Create First Template</button>
        </div>
        @endif
    </div>
</div>

{{-- Modal: Create Template --}}
<div class="modal fade" id="createTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('admin.communications.templates.store') }}" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Create Message Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Student Absence Alert">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Channel <span class="text-danger">*</span></label>
                        <select name="type" id="createChannelSelect" class="form-select" required>
                            <option value="sms">SMS</option>
                            <option value="email">Email</option>
                            <option value="push">Push Notification</option>
                        </select>
                    </div>

                    <div class="col-12" id="createSubjectField" style="display: none;">
                        <label class="form-label fw-semibold">Email / Push Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="createSubjectInput" class="form-control" placeholder="e.g. Notice regarding Examination Results">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Template Body <span class="text-danger">*</span></label>
                        <textarea name="body" id="createBodyInput" class="form-control" rows="4" required placeholder="Type your template text here..."></textarea>
                        
                        <div class="mt-2">
                            <small class="text-muted d-block mb-1">Click placeholder tag to insert into template:</small>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#createBodyInput">{student_name}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#createBodyInput">{name}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#createBodyInput">{class}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#createBodyInput">{date}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#createBodyInput">{amount}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#createBodyInput">{due_date}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#createBodyInput">{receipt_no}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#createBodyInput">{exam_name}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#createBodyInput">{notice_title}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch p-3 border rounded-3 bg-light">
                            <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" id="createIsActive" value="1" checked>
                            <label class="form-check-label fw-semibold" for="createIsActive">Active Template</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Create Template</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Edit Template --}}
<div class="modal fade" id="editTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editTemplateForm" method="POST" class="modal-content border-0 shadow">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Channel <span class="text-danger">*</span></label>
                        <select name="type" id="editType" class="form-select" required>
                            <option value="sms">SMS</option>
                            <option value="email">Email</option>
                            <option value="push">Push Notification</option>
                        </select>
                    </div>

                    <div class="col-12" id="editSubjectField" style="display: none;">
                        <label class="form-label fw-semibold">Email / Push Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="editSubject" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Template Body <span class="text-danger">*</span></label>
                        <textarea name="body" id="editBody" class="form-control" rows="4" required></textarea>
                        
                        <div class="mt-2">
                            <small class="text-muted d-block mb-1">Click placeholder tag to insert into template:</small>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#editBody">{student_name}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#editBody">{name}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#editBody">{class}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#editBody">{date}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#editBody">{amount}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#editBody">{due_date}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#editBody">{receipt_no}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#editBody">{exam_name}</span>
                                <span class="badge bg-light text-dark border placeholder-tag" role="button" data-target="#editBody">{notice_title}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch p-3 border rounded-3 bg-light">
                            <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" id="editIsActive" value="1">
                            <label class="form-check-label fw-semibold" for="editIsActive">Active Template</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Update Template</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Delete Template --}}
<div class="modal fade" id="deleteTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="deleteTemplateForm" method="POST" class="modal-content border-0 shadow">
            @csrf
            @method('DELETE')
            <div class="modal-body p-4 text-center">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mb-3 d-block"></i>
                <h5 class="fw-bold mb-2">Delete Template?</h5>
                <p class="text-muted small mb-4">Are you sure you want to delete template <strong id="deleteTemplateName"></strong>? This action cannot be undone.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">Yes, Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle subject field in Create modal
    $('#createChannelSelect').on('change', function() {
        if ($(this).val() === 'sms') {
            $('#createSubjectField').slideUp(150);
            $('#createSubjectInput').removeAttr('required');
        } else {
            $('#createSubjectField').slideDown(150);
            $('#createSubjectInput').attr('required', 'required');
        }
    });

    // Toggle subject field in Edit modal
    $('#editType').on('change', function() {
        if ($(this).val() === 'sms') {
            $('#editSubjectField').slideUp(150);
            $('#editSubject').removeAttr('required');
        } else {
            $('#editSubjectField').slideDown(150);
            $('#editSubject').attr('required', 'required');
        }
    });

    // Placeholder tag click helper
    $('.placeholder-tag').on('click', function() {
        var targetSelector = $(this).data('target');
        var tag = $(this).text();
        var $input = $(targetSelector);
        var curVal = $input.val();
        $input.val(curVal + ' ' + tag + ' ').focus();
    });

    // Edit modal setup
    $('.btn-edit').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var type = $(this).data('type');
        var subject = $(this).data('subject') || '';
        var body = $(this).data('body');
        var active = $(this).data('active') == '1';

        var updateUrl = "{{ url('admin/sms/communications/templates') }}/" + id;
        $('#editTemplateForm').attr('action', updateUrl);

        $('#editName').val(name);
        $('#editType').val(type).trigger('change');
        $('#editSubject').val(subject);
        $('#editBody').val(body);
        $('#editIsActive').prop('checked', active);

        $('#editTemplateModal').modal('show');
    });

    // Delete modal setup
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var deleteUrl = "{{ url('admin/sms/communications/templates') }}/" + id;

        $('#deleteTemplateForm').attr('action', deleteUrl);
        $('#deleteTemplateName').text(name);
        $('#deleteTemplateModal').modal('show');
    });
});
</script>
@endpush
@endsection
