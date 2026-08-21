@extends('backend.pages.layout.master')
@push('b-title', 'Subjects')

@section('backend-content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">Subjects</h3>
            <p class="text-muted mb-0">Manage all subjects offered by the school.</p>
        </div>
        <div class="d-grid d-md-block">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> Add Subject
            </button>
        </div>
    </div>

    <style>
        .subject-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            font-size: 1.2rem;
        }
        .class-badge {
            background: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color);
            color: var(--bs-body-color);
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 6px;
            display: inline-block;
            margin: 2px;
            font-size: 0.75rem;
        }
        .table-custom th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--bs-secondary-color);
            border-bottom: 2px solid var(--bs-border-color);
        }
        .table-custom td {
            vertical-align: middle;
            border-bottom: 1px solid var(--bs-border-color);
        }
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .classes-slider::-webkit-scrollbar {
            height: 4px;
        }
        .classes-slider::-webkit-scrollbar-track {
            background: var(--bs-tertiary-bg);
            border-radius: 4px;
        }
        .classes-slider::-webkit-scrollbar-thumb {
            background: var(--bs-secondary-bg);
            border-radius: 4px;
        }
        .classes-slider::-webkit-scrollbar-thumb:hover {
            background: var(--bs-secondary-color);
        }
        
        /* Hide number input spinners */
        .order-input::-webkit-outer-spin-button,
        .order-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .order-input {
            -moz-appearance: textfield;
        }
    </style>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <form action="{{ route('sms.subjects.update_order') }}" method="POST">
                @csrf
                <div class="table-responsive">
                <table class="table table-custom table-hover mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4">Subject Info</th>
                            <th class="d-none d-md-table-cell">Code</th>
                            <th class="d-none d-lg-table-cell">Type</th>
                            <th>Assigned Classes</th>
                            <th class="text-end pe-4 text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="subject-icon me-3 shadow-sm">
                                            <i class="bi bi-book"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-body">{{ $subject->name }}</h6>
                                            <div class="d-flex align-items-center mt-1">
                                                <small class="text-muted me-2">Order:</small>
                                                <input type="number" name="orders[{{ $subject->id }}]" value="{{ $subject->order_level }}" class="form-control form-control-sm text-center px-1 py-0 order-input" style="width: 60px; height: 24px;" min="0">
                                            </div>
                                            <div class="d-lg-none mt-2 d-flex flex-wrap gap-1">
                                                <span class="badge bg-body-tertiary text-body-secondary border px-2 py-1 font-monospace" style="font-size: 0.65rem;">{{ $subject->code }}</span>
                                                <span class="badge {{ $subject->type === 'theory' ? 'bg-primary' : ($subject->type === 'practical' ? 'bg-warning' : 'bg-success') }} bg-opacity-10 text-{{ $subject->type === 'theory' ? 'primary' : ($subject->type === 'practical' ? 'warning' : 'success') }} border border-{{ $subject->type === 'theory' ? 'primary' : ($subject->type === 'practical' ? 'warning' : 'success') }} border-opacity-25 px-2 py-1" style="font-size: 0.65rem;">{{ ucfirst($subject->type) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge bg-body-tertiary text-body-secondary border px-2 py-1 fs-7 font-monospace">
                                        {{ $subject->code }}
                                    </span>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    @if($subject->type === 'theory')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-journal-text me-1"></i>Theory</span>
                                    @elseif($subject->type === 'practical')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1"><i class="bi bi-tools me-1"></i>Practical</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-layers me-1"></i>Both</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="classes-slider d-flex gap-1" style="max-width: 150px; overflow-x: auto; white-space: nowrap; padding-bottom: 4px;">
                                        @forelse($subject->classes as $class)
                                            <span class="class-badge flex-shrink-0">{{ $class->name }}</span>
                                        @empty
                                            <span class="text-muted small fst-italic">Not assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-body-tertiary action-btn text-primary border me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $subject->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-body-tertiary action-btn text-danger border" title="Delete" onclick="if(confirm('Are you sure you want to delete this subject?')) { document.getElementById('deleteSubjectForm').action='{{ route('sms.subjects.destroy', $subject->id) }}'; document.getElementById('deleteSubjectForm').submit(); }">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted mb-3"><i class="bi bi-folder-x fs-1"></i></div>
                                    <h5 class="fw-bold">No Subjects Found</h5>
                                    <p class="text-muted">Start by adding a new subject.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($subjects->count() > 0)
                <div class="card-footer bg-white border-top text-end py-3">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-sort-numeric-up me-2"></i> Save Orders</button>
                </div>
            @endif
            </form>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sms.subjects.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Code</label>
                                <input type="text" name="code" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order Level</label>
                                <input type="number" name="order_level" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="theory">Theory Only</option>
                                <option value="practical">Practical Only</option>
                                <option value="both">Theory & Practical</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Assign Classes</label>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input check-all-classes" type="checkbox" id="checkAllAdd" data-target=".add-class-check">
                                    <label class="form-check-label small text-muted" for="checkAllAdd">Check All</label>
                                </div>
                            </div>
                            <div class="border rounded p-3 bg-body-tertiary" style="max-height: 200px; overflow-y: auto;">
                                <div class="row g-2">
                                    @foreach($classes as $class)
                                    <div class="col-6 col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input add-class-check" type="checkbox" name="classes[]" value="{{ $class->id }}" id="addClass{{ $class->id }}">
                                            <label class="form-check-label text-truncate w-100" for="addClass{{ $class->id }}" title="{{ $class->name }}">
                                                {{ $class->name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">You can assign this subject to multiple classes.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach($subjects as $subject)
        <div class="modal fade" id="editModal{{ $subject->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('sms.subjects.update', $subject->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Subject</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $subject->name }}" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Code</label>
                                    <input type="text" name="code" class="form-control" value="{{ $subject->code }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Order Level</label>
                                    <input type="number" name="order_level" class="form-control" value="{{ $subject->order_level }}" min="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="theory" {{ $subject->type === 'theory' ? 'selected' : '' }}>Theory Only</option>
                                    <option value="practical" {{ $subject->type === 'practical' ? 'selected' : '' }}>Practical Only</option>
                                    <option value="both" {{ $subject->type === 'both' ? 'selected' : '' }}>Theory & Practical</option>
                                </select>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Assign Classes</label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input check-all-classes" type="checkbox" id="checkAllEdit{{ $subject->id }}" data-target=".edit-class-check-{{ $subject->id }}">
                                        <label class="form-check-label small text-muted" for="checkAllEdit{{ $subject->id }}">Check All</label>
                                    </div>
                                </div>
                                <div class="border rounded p-3 bg-body-tertiary" style="max-height: 200px; overflow-y: auto;">
                                    <div class="row g-2">
                                        @php
                                            $assignedClassIds = $subject->classes->pluck('id')->toArray();
                                        @endphp
                                        @foreach($classes as $class)
                                        <div class="col-6 col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input edit-class-check-{{ $subject->id }}" type="checkbox" name="classes[]" value="{{ $class->id }}" id="editClass{{ $subject->id }}_{{ $class->id }}" {{ in_array($class->id, $assignedClassIds) ? 'checked' : '' }}>
                                                <label class="form-check-label text-truncate w-100" for="editClass{{ $subject->id }}_{{ $class->id }}" title="{{ $class->name }}">
                                                    {{ $class->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">You can assign this subject to multiple classes.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sms.subjects.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name (e.g. Mathematics)</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Code (e.g. MAT101)</label>
                                <input type="text" name="code" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order Level</label>
                                <input type="number" name="order_level" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="theory" selected>Theory Only</option>
                                <option value="practical">Practical Only</option>
                                <option value="both">Theory & Practical</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check all logic for classes
            document.querySelectorAll('.check-all-classes').forEach(function(checkAllBtn) {
                checkAllBtn.addEventListener('change', function() {
                    let targetClass = this.getAttribute('data-target');
                    let checkboxes = document.querySelectorAll(targetClass);
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = checkAllBtn.checked;
                    });
                });
            });

            // Handle arrow keys navigation for order inputs (Excel style)
            const orderInputs = Array.from(document.querySelectorAll('.order-input'));
            orderInputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (index > 0) {
                            orderInputs[index - 1].focus();
                            orderInputs[index - 1].select();
                        }
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (index < orderInputs.length - 1) {
                            orderInputs[index + 1].focus();
                            orderInputs[index + 1].select();
                        }
                    }
                });
            });
        });
    </script>
    @endpush
    <form id="deleteSubjectForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection
