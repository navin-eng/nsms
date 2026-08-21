@extends('backend.pages.layout.master')
@section('title', 'Study Materials')
@section('backend-content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="mb-1">Study Materials</h3>
        <p class="text-muted mb-0">Upload and manage study resources for classes.</p>
    </div>
</div>

<style>
    .table-custom th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; color: var(--bs-secondary-color); border-bottom: 2px solid var(--bs-border-color); }
    .table-custom td { vertical-align: middle; border-bottom: 1px solid var(--bs-border-color); }
    .action-btn { background: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: all 0.2s; }
    .action-btn:hover { transform: translateY(-2px); }
</style>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="card-title m-0 fw-bold">Upload Material</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('sms.materials.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select name="class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attachment <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Upload Material</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="card-title m-0 fw-bold">Uploaded Materials</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead class="bg-body-tertiary">
                            <tr>
                                <th class="ps-4">Material Details</th>
                                <th class="d-none d-md-table-cell">Class / Subject</th>
                                <th class="d-none d-lg-table-cell">Attachment</th>
                                <th class="text-end pe-4 text-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materials as $mat)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-body">{{ $mat->title }}</div>
                                        <div class="d-md-none mt-1 d-flex flex-column gap-1">
                                            <span class="badge bg-body-tertiary text-body-secondary border text-start w-100" style="font-size: 0.65rem;"><i class="bi bi-diagram-3"></i> {{ $mat->academicClass->name ?? '' }}</span>
                                            <span class="badge bg-body-tertiary text-body-secondary border text-start w-100" style="font-size: 0.65rem;"><i class="bi bi-book"></i> {{ $mat->subject->name ?? '' }}</span>
                                            @if($mat->file_path)
                                                <a href="{{ asset($mat->file_path) }}" target="_blank" class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 text-start w-100 text-decoration-none" style="font-size: 0.65rem;"><i class="bi bi-paperclip"></i> View File</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <div class="fw-semibold text-body">{{ $mat->academicClass->name ?? '' }}</div>
                                        <div class="text-body-secondary small">{{ $mat->subject->name ?? '' }}</div>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        @if($mat->file_path)
                                            <a href="{{ asset($mat->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3 py-1" style="font-size: 0.75rem;"><i class="bi bi-paperclip"></i> Download</a>
                                        @else
                                            <span class="text-body-secondary small fst-italic">None</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 text-nowrap">
                                        <a href="{{ route('sms.materials.show', $mat->id) }}" class="btn action-btn text-primary" title="View/Print"><i class="bi bi-eye"></i></a>
                                        <button type="button" class="btn action-btn text-info" title="Edit" 
                                            data-bs-toggle="modal" data-bs-target="#editMaterialModal"
                                            data-id="{{ $mat->id }}"
                                            data-title="{{ $mat->title }}"
                                            data-class="{{ $mat->class_id }}"
                                            data-subject="{{ $mat->subject_id }}"
                                            data-desc="{{ $mat->description }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('sms.materials.destroy', $mat->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this material?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn action-btn text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted mb-3"><i class="bi bi-folder-x fs-1"></i></div>
                                        <h5 class="fw-bold text-body">No Materials Found</h5>
                                        <p class="text-body-secondary">Upload the first study material using the form.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Material Modal -->
<div class="modal fade" id="editMaterialModal" tabindex="-1" aria-labelledby="editMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="editMaterialModalLabel">Edit Study Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMaterialForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <select name="class_id" id="edit_class_id" class="form-select" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="edit_subject_id" class="form-select" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Attachment <small class="text-muted">(Leaves old file if blank)</small></label>
                        <input type="file" name="file" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Material</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editMaterialModal');
        if(editModal){
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                
                // Update form action
                const form = document.getElementById('editMaterialForm');
                form.action = `{{ url('admin/sms/materials') }}/${id}`;
                
                // Populate fields
                document.getElementById('edit_title').value = button.getAttribute('data-title');
                document.getElementById('edit_description').value = button.getAttribute('data-desc');
                document.getElementById('edit_class_id').value = button.getAttribute('data-class');
                document.getElementById('edit_subject_id').value = button.getAttribute('data-subject');
            });
        }
    });
</script>
@endpush
@endsection
