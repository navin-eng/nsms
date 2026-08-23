@extends('accounting.layout.master')
@push('b-title', 'Fee Structures')

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800"><i class="bi bi-diagram-3 me-2 text-primary"></i>Fee Structures</h4>
            <p class="text-muted mb-0 small">Manage class-wise fee structures and amounts.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-outline-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#copyModal">
                <i class="bi bi-copy me-1"></i> Copy Structure
            </button>
            <button class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#structureModal" id="addStructureBtn">
                <i class="bi bi-plus-lg me-1"></i> Add Structure
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('accounting.fees.fee-structures.index') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Academic Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="academic_class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('academic_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="fee_type_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Fee Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ request('fee_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Academic Year</th>
                            <th>Class</th>
                            <th>Fee Type</th>
                            <th>Billing Cycle</th>
                            <th>Amount (Rs.)</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($structures as $structure)
                            <tr>
                                <td>{{ $structure->academicYear->name ?? 'N/A' }}</td>
                                <td>{{ $structure->academicClass->name ?? 'All Classes' }}</td>
                                <td class="fw-bold">{{ $structure->feeType->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-info text-dark">{{ $structure->billing_cycle }}</span></td>
                                <td>Rs. {{ number_format($structure->amount, 2) }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary edit-btn"
                                            data-id="{{ $structure->id }}"
                                            data-year="{{ $structure->academic_year_id }}"
                                            data-class="{{ $structure->academic_class_id }}"
                                            data-type="{{ $structure->fee_type_id }}"
                                            data-cycle="{{ $structure->billing_cycle }}"
                                            data-amount="{{ $structure->amount }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('accounting.fees.fee-structures.destroy', $structure->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this fee structure?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No fee structures found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="structureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('accounting.fees.fee-structures.store') }}" method="POST" id="structureForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Bulk Add Fee Structure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Academic Year <span class="text-danger">*</span></label>
                            <select name="academic_year_id" class="form-select" required>
                                <option value="">Select Year</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <select name="academic_class_id" class="form-select" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="mt-2 fw-bold">Set Fee Amounts</h6>
                    <p class="text-muted small mb-2">Leave amount blank or 0 for fee types that do not apply to this class. <br>
                        <em>Missing a fee type? <a href="{{ route('accounting.fees.fee-types.index') }}" class="text-primary">Click here to add more fee types</a> first.</em>
                    </p>
                    
                    <div class="table-responsive border rounded" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Fee Type</th>
                                    <th>Billing Cycle</th>
                                    <th>Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody id="bulkAddTbody">
                                @foreach($types as $index => $type)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $type->name }}
                                            <input type="hidden" name="fees[{{ $index }}][fee_type_id]" value="{{ $type->id }}">
                                        </td>
                                        <td>
                                            <select name="fees[{{ $index }}][billing_cycle]" class="form-select form-select-sm">
                                                <option value="Monthly">Monthly</option>
                                                <option value="Semesterly">Semesterly</option>
                                                <option value="Annually">Annually</option>
                                                <option value="One-Time">One-Time</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="fees[{{ $index }}][amount]" class="form-control form-control-sm" placeholder="0.00">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Edit Mode Fallback fields (hidden initially, only used in JS edit mode) -->
                    <div id="editModeFields" class="d-none mt-3">
                        <div class="mb-3">
                            <label class="form-label">Fee Type</label>
                            <select name="fee_type_id" class="form-select" id="editFeeType">
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Billing Cycle</label>
                            <select name="billing_cycle" class="form-select" id="editBillingCycle">
                                <option value="Monthly">Monthly</option>
                                <option value="Semesterly">Semesterly</option>
                                <option value="Annually">Annually</option>
                                <option value="One-Time">One-Time</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount (Rs.)</label>
                            <input type="number" step="0.01" min="0" name="amount" id="editAmount" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Copy Structure Modal -->
<div class="modal fade" id="copyStructureModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('accounting.fees.fee-structures.copy') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Copy Fee Structure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Copy From (Source)</h6>
                    <div class="mb-3">
                        <label class="form-label">Source Academic Year <span class="text-danger">*</span></label>
                        <select name="source_academic_year_id" class="form-select" required>
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Source Class <span class="text-danger">*</span></label>
                        <select name="source_academic_class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">All fee amounts assigned to this class will be copied.</div>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2 text-primary">Copy To (Destination)</h6>
                    <div class="mb-3">
                        <label class="form-label">Target Academic Year <span class="text-danger">*</span></label>
                        <select name="target_academic_year_id" class="form-select" required>
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target Classes <span class="text-danger">*</span></label>
                        <select name="target_academic_class_id[]" class="form-select" multiple required style="height: 120px;">
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Hold Cmd (Mac) or Ctrl (Windows) to select multiple classes. Existing fee structures for these classes will be updated.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-files"></i> Copy Fees</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('structureModal'));
    const form = document.getElementById('structureForm');
    const modalTitle = document.getElementById('modalTitle');
    const addBtn = document.getElementById('addStructureBtn');
    
    // UI Elements for toggling modes
    const bulkAddTbody = document.getElementById('bulkAddTbody').parentElement.parentElement;
    const editModeFields = document.getElementById('editModeFields');
    const feeAmountTitle = document.querySelector('h6.mt-2.fw-bold');
    const feeAmountHelp = document.querySelector('p.text-muted.small.mb-2');

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = "{{ route('accounting.fees.fee-structures.store') }}";
        
        let methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
        
        modalTitle.textContent = 'Bulk Add Fee Structure';
        
        // Show bulk table, hide edit fields
        bulkAddTbody.classList.remove('d-none');
        feeAmountTitle.classList.remove('d-none');
        feeAmountHelp.classList.remove('d-none');
        editModeFields.classList.add('d-none');
        
        // Enable bulk inputs, disable edit inputs
        document.querySelectorAll('#bulkAddTbody input, #bulkAddTbody select').forEach(el => el.disabled = false);
        document.querySelectorAll('#editModeFields input, #editModeFields select').forEach(el => el.disabled = true);
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            form.reset();
            const id = this.dataset.id;
            form.action = `{{ url('admin/sms/finance/fee-structures') }}/${id}`;
            
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            
            modalTitle.textContent = 'Edit Fee Structure';
            
            // Hide bulk table, show edit fields
            bulkAddTbody.classList.add('d-none');
            feeAmountTitle.classList.add('d-none');
            feeAmountHelp.classList.add('d-none');
            editModeFields.classList.remove('d-none');
            
            // Disable bulk inputs, enable edit inputs
            document.querySelectorAll('#bulkAddTbody input, #bulkAddTbody select').forEach(el => el.disabled = true);
            document.querySelectorAll('#editModeFields input, #editModeFields select').forEach(el => el.disabled = false);
            
            // Populate form
            form.academic_year_id.value = this.dataset.year;
            form.academic_class_id.value = this.dataset.class;
            
            // Populate edit fields
            document.getElementById('editFeeType').value = this.dataset.type;
            document.getElementById('editBillingCycle').value = this.dataset.cycle;
            document.getElementById('editAmount').value = this.dataset.amount;
            
            modal.show();
        });
    });
});
</script>
@endpush
@endsection
