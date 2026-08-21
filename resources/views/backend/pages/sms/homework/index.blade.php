@extends('backend.pages.layout.master')
@section('title', 'Manage Homework')
@section('backend-content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="mb-1">Homework Management</h3>
        <p class="text-muted mb-0">Assign and manage homework for all classes.</p>
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
                <h6 class="card-title m-0 fw-bold">Assign Homework</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('sms.homework.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label class="form-label">Section <span class="text-danger">*</span></label>
                        <select name="section_id" class="form-select" required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" data-class-ids="{{ $section->academicClasses->pluck('id')->join(',') }}">{{ $section->name }}</option>
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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Marks</label>
                            <input type="number" name="total_marks" class="form-control" value="100" min="0" step="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attachment (Optional)</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Assign Homework</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="card-title m-0 fw-bold">Recent Homeworks</h6>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">{{ $homeworks->count() }} Assignments</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover align-middle mb-0">
                        <thead class="bg-body-tertiary">
                            <tr>
                                <th class="ps-4">Homework Details</th>
                                <th class="d-none d-md-table-cell">Class / Subject</th>
                                <th class="d-none d-md-table-cell">Due Date</th>
                                <th>Submissions</th>
                                <th class="text-end pe-4 text-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($homeworks as $hw)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-body">{{ $hw->title }}</div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-light text-dark border" style="font-size:0.7rem;"><i class="bi bi-award me-1"></i>{{ $hw->total_marks ?? 100 }} Marks</span>
                                            @if($hw->file_path)
                                                <a href="{{ asset($hw->file_path) }}" target="_blank" class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 text-decoration-none" style="font-size: 0.7rem;"><i class="bi bi-paperclip"></i> File</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <div class="fw-semibold text-body">{{ $hw->academicClass->name ?? '' }} ({{ $hw->section->name ?? '' }})</div>
                                        <div class="text-body-secondary small">{{ $hw->subject->name ?? '' }}</div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @php
                                            $isPast = \Carbon\Carbon::parse($hw->due_date)->isPast();
                                        @endphp
                                        <span class="badge bg-{{ $isPast ? 'danger' : 'success' }}-subtle text-{{ $isPast ? 'danger' : 'success' }} border px-2 py-1">
                                            <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($hw->due_date)->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('sms.homework.show', $hw->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 text-decoration-none" style="font-size: 0.75rem;">
                                            <i class="bi bi-people me-1"></i>{{ $hw->submissions->count() }} Submissions
                                        </a>
                                    </td>
                                    <td class="text-end pe-4 text-nowrap">
                                        <a href="{{ route('sms.homework.show', $hw->id) }}" class="btn action-btn text-primary" title="View & Grade Submissions"><i class="bi bi-eye"></i></a>
                                        <button type="button" class="btn action-btn text-info" title="Edit" 
                                            data-bs-toggle="modal" data-bs-target="#editHomeworkModal"
                                            data-id="{{ $hw->id }}"
                                            data-title="{{ $hw->title }}"
                                            data-class="{{ $hw->class_id }}"
                                            data-section="{{ $hw->section_id }}"
                                            data-subject="{{ $hw->subject_id }}"
                                            data-due="{{ \Carbon\Carbon::parse($hw->due_date)->format('Y-m-d') }}"
                                            data-marks="{{ $hw->total_marks ?? 100 }}"
                                            data-desc="{{ $hw->description }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('sms.homework.destroy', $hw->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this homework?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn action-btn text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted mb-3"><i class="bi bi-journal-x fs-1"></i></div>
                                        <h5 class="fw-bold text-body">No Homework Found</h5>
                                        <p class="text-body-secondary">Start by assigning homework using the form.</p>
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

<!-- Edit Homework Modal -->
<div class="modal fade" id="editHomeworkModal" tabindex="-1" aria-labelledby="editHomeworkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="editHomeworkModalLabel">Edit Homework</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editHomeworkForm" method="POST" enctype="multipart/form-data">
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
                            <label class="form-label">Section <span class="text-danger">*</span></label>
                            <select name="section_id" id="edit_section_id" class="form-select" required>
                                <option value="">Select Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" data-class-ids="{{ $section->academicClasses->pluck('id')->join(',') }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="edit_subject_id" class="form-select" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" id="edit_due_date" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Marks</label>
                            <input type="number" name="total_marks" id="edit_total_marks" class="form-control" min="0" step="1">
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
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Homework</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editHomeworkModal');
        if(editModal){
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                
                // Update form action
                const form = document.getElementById('editHomeworkForm');
                form.action = `{{ url('admin/sms/homework') }}/${id}`;
                
                // Populate fields
                document.getElementById('edit_title').value = button.getAttribute('data-title');
                document.getElementById('edit_due_date').value = button.getAttribute('data-due');
                document.getElementById('edit_total_marks').value = button.getAttribute('data-marks') || '100';
                document.getElementById('edit_description').value = button.getAttribute('data-desc');
                document.getElementById('edit_class_id').value = button.getAttribute('data-class');
                document.getElementById('edit_subject_id').value = button.getAttribute('data-subject');
                
                // Trigger class change to update sections via global script
                const classSelect = document.getElementById('edit_class_id');
                classSelect.dispatchEvent(new Event('change'));
                
                // Set section after a brief timeout to let the global script finish generating options
                setTimeout(() => {
                    document.getElementById('edit_section_id').value = button.getAttribute('data-section');
                }, 50);
            });
        }
    });
</script>
@endpush
@endsection
