@extends('accounting.layout.master')
@push('b-title', 'Fee Types')

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800"><i class="bi bi-tag me-2 text-primary"></i>Fee Types</h4>
            <p class="text-muted mb-0 small">Define fee categories (Tuition, Bus, Exam, etc.).</p>
        </div>
        <div>
            <button class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#typeModal" id="addTypeBtn">
                <i class="bi bi-plus-lg me-1"></i> Add Fee Type
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($types as $type)
                            <tr>
                                <td class="fw-bold">{{ $type->name }}</td>
                                <td>{{ $type->description ?? '-' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary edit-btn"
                                            data-id="{{ $type->id }}"
                                            data-name="{{ $type->name }}"
                                            data-description="{{ $type->description }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('accounting.fees.fee-types.destroy', $type->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this fee type?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No fee types found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="typeModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('accounting.fees.fee-types.store') }}" method="POST" id="typeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Fee Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="dynamicTypesWrapper">
                    <div class="type-row mb-3 border-bottom pb-3">
                        <div class="mb-2">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="types[0][name]" class="form-control type-name-input" required placeholder="e.g. Tuition Fee">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea name="types[0][description]" class="form-control type-desc-input" rows="2" placeholder="Optional notes..."></textarea>
                        </div>
                        <div class="text-end remove-row-btn-wrapper d-none">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn"><i class="bi bi-trash"></i> Remove</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn" style="display: none;"><i class="bi bi-plus"></i> Add Another</button>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('typeModal'));
    const form = document.getElementById('typeForm');
    const modalTitle = document.getElementById('modalTitle');
    const addBtn = document.getElementById('addTypeBtn');
    const wrapper = document.getElementById('dynamicTypesWrapper');
    const addRowBtn = document.getElementById('addRowBtn');
    let rowCount = 1;
    let isEditMode = false;

    addBtn.addEventListener('click', function () {
        isEditMode = false;
        form.reset();
        form.action = "{{ route('accounting.fees.fee-types.store') }}";
        
        let methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
        
        modalTitle.textContent = 'Add Fee Type(s)';
        addRowBtn.style.display = 'block';
        
        // Reset rows to 1
        wrapper.innerHTML = `
            <div class="type-row mb-3 border-bottom pb-3">
                <div class="mb-2">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="types[0][name]" class="form-control type-name-input" required placeholder="e.g. Tuition Fee">
                </div>
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea name="types[0][description]" class="form-control type-desc-input" rows="2" placeholder="Optional notes..."></textarea>
                </div>
                <div class="text-end remove-row-btn-wrapper d-none">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn"><i class="bi bi-trash"></i> Remove</button>
                </div>
            </div>
        `;
        rowCount = 1;
    });

    addRowBtn.addEventListener('click', function() {
        if (isEditMode) return;
        
        const newRow = document.createElement('div');
        newRow.className = 'type-row mb-3 border-bottom pb-3';
        newRow.innerHTML = `
            <div class="mb-2">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="types[${rowCount}][name]" class="form-control type-name-input" required placeholder="e.g. Transport Fee">
            </div>
            <div class="mb-2">
                <label class="form-label">Description</label>
                <textarea name="types[${rowCount}][description]" class="form-control type-desc-input" rows="2" placeholder="Optional notes..."></textarea>
            </div>
            <div class="text-end remove-row-btn-wrapper">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn"><i class="bi bi-trash"></i> Remove</button>
            </div>
        `;
        wrapper.appendChild(newRow);
        rowCount++;
        
        // Add event listener to new remove button
        newRow.querySelector('.remove-row-btn').addEventListener('click', function() {
            newRow.remove();
        });
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            isEditMode = true;
            form.reset();
            const id = this.dataset.id;
            form.action = `{{ url('admin/sms/finance/fee-types') }}/${id}`;
            
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            
            modalTitle.textContent = 'Edit Fee Type';
            addRowBtn.style.display = 'none'; // Hide "Add Another" in edit mode
            
            // Set single edit layout (not using array syntax for edit)
            wrapper.innerHTML = `
                <div class="mb-2">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            `;
            
            wrapper.querySelector('input[name="name"]').value = this.dataset.name;
            wrapper.querySelector('textarea[name="description"]').value = this.dataset.description;
            
            modal.show();
        });
    });
});
</script>
@endpush
@endsection
