@extends('backend.pages.layout.master')

@section('title', 'Generate Invoices')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0">Generate Invoices</h3>
            <p class="text-muted">Generate bulk fee invoices for a class.</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('sms.finance.invoices.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-list"></i> View All Invoices
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
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

    <!-- Step 1: Selection Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('sms.finance.invoices.generate') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Academic Year <span class="text-danger">*</span></label>
                    <select name="academic_year_id" class="form-select" required>
                        <option value="">Select Year</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Nepali Month <span class="text-danger">*</span></label>
                    <select name="nepali_month" class="form-select" required>
                        <option value="">Select Month</option>
                        @foreach(['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'] as $month)
                            <option value="{{ $month }}" {{ request('nepali_month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Class <span class="text-danger">*</span></label>
                    <select name="academic_class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('academic_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Load Students</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Step 2: Generation Form -->
    @if(isset($students) && $students->isNotEmpty())
    <form method="POST" action="{{ route('sms.finance.invoices.generate.process') }}">
        @csrf
        <input type="hidden" name="academic_year_id" value="{{ request('academic_year_id') }}">
        <input type="hidden" name="academic_class_id" value="{{ request('academic_class_id') }}">
        <input type="hidden" name="nepali_month" value="{{ request('nepali_month') }}">
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 border-bottom">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Invoice Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. Tuition Fee" value="{{ request('nepali_month') ? request('nepali_month') . ' Fee' : old('title') }}">
                        <div class="form-text">This will be shown on the student's bill.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Due Date <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control" required value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-bordered table-hover align-middle mb-0" style="min-width: 1200px;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="text-center" style="width: 50px;">
                                    <input class="form-check-input" type="checkbox" id="selectAll" checked>
                                </th>
                                <th style="width: 200px;">Student Name</th>
                                @foreach($structures as $structure)
                                    <th class="text-center" style="min-width: 150px;">{{ $structure->feeType->name }}<br><small class="text-muted">(Base: Rs.{{ $structure->amount }})</small></th>
                                @endforeach
                                <th style="width: 120px;">Discount (Rs.)</th>
                                <th style="width: 150px;">Remarks</th>
                                <th class="text-end" style="width: 150px;">Previous Due</th>
                                <th class="text-end" style="width: 150px;">Current Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr class="student-row">
                                    <td class="text-center">
                                        <input class="form-check-input student-checkbox" type="checkbox" name="students[{{ $student->id }}][generate]" value="1" checked>
                                        <input type="hidden" name="students[{{ $student->id }}][previous_due]" value="{{ $student->previous_due }}">
                                    </td>
                                    <td class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}<br><small class="text-muted">{{ $student->registration_number }}</small></td>
                                    
                                    @php $rowTotal = 0; @endphp
                                    @foreach($structures as $structure)
                                        @php $rowTotal += $structure->amount; @endphp
                                        <td class="text-center bg-light">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-text">
                                                    <input class="form-check-input mt-0 fee-checkbox" type="checkbox" name="students[{{ $student->id }}][fees][{{ $structure->feeType->id }}][include]" value="1" checked>
                                                </div>
                                                <input type="number" step="0.01" min="0" class="form-control fee-amount" name="students[{{ $student->id }}][fees][{{ $structure->feeType->id }}][amount]" value="{{ $structure->amount }}">
                                            </div>
                                        </td>
                                    @endforeach
                                    
                                    <td>
                                        <input type="number" step="0.01" min="0" class="form-control form-control-sm discount-input" name="students[{{ $student->id }}][discount]" value="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="students[{{ $student->id }}][remarks]" placeholder="Remarks">
                                    </td>
                                    <td class="text-end text-danger">Rs. {{ number_format($student->previous_due, 2) }}</td>
                                    <td class="text-end fw-bold bg-light row-total" data-base-total="{{ $rowTotal }}">Rs. {{ number_format($rowTotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end py-3">
                <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('This will generate invoices and post accounting journal entries for selected students. Are you sure?');">
                    <i class="bi bi-gear-fill"></i> Generate Invoices
                </button>
            </div>
        </div>
    </form>
    @elseif(request()->has('academic_class_id'))
        <div class="alert alert-warning">No students or fee structures found for the selected criteria.</div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAll = document.getElementById('selectAll');
    if(selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = this.checked);
        });
    }

    // Dynamic Row Total Calculation
    function calculateRowTotal(row) {
        let total = 0;
        
        // Sum included fees
        row.querySelectorAll('.fee-checkbox').forEach(cb => {
            if(cb.checked) {
                const input = cb.closest('.input-group').querySelector('.fee-amount');
                total += parseFloat(input.value) || 0;
            }
        });

        // Subtract discount
        const discountInput = row.querySelector('.discount-input');
        const discount = parseFloat(discountInput.value) || 0;
        
        const finalTotal = Math.max(0, total - discount);
        
        // Update UI
        row.querySelector('.row-total').textContent = 'Rs. ' + finalTotal.toFixed(2);
    }

    document.querySelectorAll('.student-row').forEach(row => {
        // Listen to changes on fee inclusion checkboxes
        row.querySelectorAll('.fee-checkbox').forEach(cb => {
            cb.addEventListener('change', () => calculateRowTotal(row));
        });
        
        // Listen to changes on fee amounts
        row.querySelectorAll('.fee-amount').forEach(input => {
            input.addEventListener('input', () => calculateRowTotal(row));
        });
        
        // Listen to changes on discount
        row.querySelector('.discount-input').addEventListener('input', () => calculateRowTotal(row));
    });
});
</script>
@endpush
@endsection
