@extends('backend.pages.layout.master')
@push('b-title', 'Academic Classes')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Classes</h3>
            <p class="text-muted mb-0">Manage all classes and their sections. Create sections first from the <a href="{{ route('sms.sections.index') }}">Sections page</a>.</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg"></i> Add Class
        </button>
    </div>

    <style>
        .table-custom th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; color: var(--bs-secondary-color); border-bottom: 2px solid var(--bs-border-color); }
        .table-custom td { vertical-align: middle; border-bottom: 1px solid var(--bs-border-color); }
        .section-tag { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color); color: var(--bs-body-color); }
        .section-checkbox-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 8px; }
        .section-check-item { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--bs-border-color); background: var(--bs-tertiary-bg); cursor: pointer; transition: all 0.15s; }
        .section-check-item:hover { border-color: var(--bs-primary); }
        .section-check-item input:checked ~ span { font-weight: 600; color: var(--bs-primary); }
        .section-check-item:has(input:checked) { border-color: var(--bs-primary); background: rgba(var(--bs-primary-rgb), 0.06); }
    </style>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4">Class Name</th>
                            <th>Stream</th>
                            <th>Sections</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-body">{{ $class->name }}</div>
                                    <div class="text-body-secondary small">Order: {{ $class->numeric_value }}</div>
                                </td>
                                <td>
                                    @if($class->stream)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2">{{ $class->stream->name }}</span>
                                    @else
                                        <span class="text-body-secondary fst-italic small">None</span>
                                    @endif
                                </td>
                                <td>
                                    @forelse($class->sections as $section)
                                        <span class="section-tag me-1 mb-1"><i class="bi bi-diagram-3"></i>{{ $section->name }}</span>
                                    @empty
                                        <span class="text-body-secondary small fst-italic">No sections</span>
                                    @endforelse
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                    <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $class->id }}">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </button>
                                    <form action="{{ route('sms.academic-classes.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this class?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted mb-3"><i class="bi bi-building-x fs-1"></i></div>
                                    <h5 class="fw-bold text-body">No Classes Found</h5>
                                    <p class="text-body-secondary">Add a class to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- =========== EDIT MODALS =========== --}}
    @foreach($classes as $class)
        @php $classAssignedIds = $class->sections->pluck('id')->toArray(); @endphp
        <div class="modal fade" id="editModal{{ $class->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <form action="{{ route('sms.academic-classes.update', $class->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title fw-bold">Edit Class</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Class Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $class->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Numeric Value <small class="text-muted fw-normal">(for sorting)</small></label>
                                <input type="number" name="numeric_value" class="form-control" value="{{ $class->numeric_value }}" required min="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Stream <small class="text-muted fw-normal">(Optional)</small></label>
                                <select name="stream_id" class="form-select">
                                    <option value="">-- No Stream --</option>
                                    @foreach($streams as $stream)
                                        <option value="{{ $stream->id }}" {{ $class->stream_id == $stream->id ? 'selected' : '' }}>{{ $stream->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <hr class="my-3">

                            {{-- Sections Toggle --}}
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <label class="form-label fw-semibold mb-0">Assign Sections</label>
                                    <div class="text-body-secondary small">Choose which sections belong to this class</div>
                                </div>
                                <div class="form-check form-switch ms-3 mb-0">
                                    <input class="form-check-input edit-section-toggle" type="checkbox"
                                        id="editSectionToggle{{ $class->id }}"
                                        style="width: 2.5em; height: 1.3em;"
                                        data-target="editSectionsArea{{ $class->id }}"
                                        {{ $class->sections->count() > 0 ? 'checked' : '' }}>
                                </div>
                            </div>

                            <div id="editSectionsArea{{ $class->id }}" class="{{ $class->sections->count() > 0 ? '' : 'd-none' }}">
                                @if($allSections->isEmpty())
                                    <div class="alert alert-warning border-0 small py-2">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        No sections created yet. <a href="{{ route('sms.sections.index') }}" class="fw-semibold">Create sections first</a>.
                                    </div>
                                @else
                                    <div class="section-checkbox-grid">
                                        @foreach($allSections as $section)
                                            <label class="section-check-item">
                                                <input type="checkbox" name="section_ids[]" value="{{ $section->id }}"
                                                    {{ in_array($section->id, $classAssignedIds) ? 'checked' : '' }}>
                                                <span>{{ $section->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- =========== ADD MODAL =========== --}}
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form action="{{ route('sms.academic-classes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title fw-bold">Add Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Class Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Class 10" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Numeric Value <span class="text-danger">*</span> <small class="text-muted fw-normal">(for sorting)</small></label>
                            <input type="number" name="numeric_value" class="form-control" placeholder="e.g. 10" required min="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Stream <small class="text-muted fw-normal">(Optional)</small></label>
                            <select name="stream_id" class="form-select">
                                <option value="">-- No Stream --</option>
                                @foreach($streams as $stream)
                                    <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="my-3">

                        {{-- Sections Toggle --}}
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <label class="form-label fw-semibold mb-0">Assign Sections</label>
                                <div class="text-body-secondary small">Choose which sections belong to this class</div>
                            </div>
                            <div class="form-check form-switch ms-3 mb-0">
                                <input class="form-check-input" type="checkbox" id="addSectionToggle"
                                    style="width: 2.5em; height: 1.3em;">
                            </div>
                        </div>

                        <div id="addSectionsArea" class="d-none">
                            @if($allSections->isEmpty())
                                <div class="alert alert-warning border-0 small py-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    No sections created yet. <a href="{{ route('sms.sections.index') }}" class="fw-semibold">Create sections first</a>.
                                </div>
                            @else
                                <div class="section-checkbox-grid">
                                    @foreach($allSections as $section)
                                        <label class="section-check-item">
                                            <input type="checkbox" name="section_ids[]" value="{{ $section->id }}">
                                            <span>{{ $section->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Add Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    // Add modal section toggle
    document.getElementById('addSectionToggle').addEventListener('change', function() {
        document.getElementById('addSectionsArea').classList.toggle('d-none', !this.checked);
    });

    // Edit modal section toggles
    document.querySelectorAll('.edit-section-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const target = document.getElementById(this.getAttribute('data-target'));
            if (target) target.classList.toggle('d-none', !this.checked);
        });
    });

    // On add modal close, reset
    document.getElementById('addModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('addSectionToggle').checked = false;
        document.getElementById('addSectionsArea').classList.add('d-none');
        this.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    });
</script>
@endpush
@endsection
