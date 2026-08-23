@extends('accounting.layout.master')
@push('b-title', 'Generate Invoices')

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800"><i class="bi bi-magic me-2 text-primary"></i>Generate Invoices</h4>
            <p class="text-muted mb-0 small">Generate bulk fee invoices for a class or student.</p>
        </div>
        <div>
            <a href="{{ route('accounting.fees.invoices.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-list me-1"></i> View All Invoices
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
            <form method="GET" action="{{ route('accounting.fees.invoices.generate') }}" class="row g-3 align-items-end">
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
                <div class="col-md-2">
                    <label class="form-label fw-bold">Class</label>
                    <select name="academic_class_id" class="form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('academic_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Search Student</label>
                    <input type="text" name="search_term" class="form-control" placeholder="Name or Adm No" value="{{ request('search_term') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search"></i> Load</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Step 2: Generation Form -->
    @if(isset($students) && $students->isNotEmpty())
    <form method="POST" action="{{ route('accounting.fees.invoices.generate.process') }}">
        @csrf
        <input type="hidden" name="academic_year_id" value="{{ request('academic_year_id') }}">
        <input type="hidden" name="academic_class_id" value="{{ request('academic_class_id') }}">
        <input type="hidden" name="nepali_month" value="{{ request('nepali_month') }}">
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 border-bottom">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Invoice Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. Tuition Fee" value="{{ request('nepali_month') ? request('nepali_month') . ' Fee' : old('title') }}">
                        <div class="form-text">This will be shown on the student's bill.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Due Date <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control" required value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Search Student</label>
                        <input type="text" id="studentSearch" class="form-control" placeholder="Search by name or admission no...">
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
                                <th class="text-center" style="min-width: 150px;">Hostel Fee<br><small class="text-muted">(Per Bed)</small></th>
                                <th style="width: 120px;">Discount (Rs.)</th>
                                <th style="width: 150px;">Remarks</th>
                                <th class="text-end" style="width: 150px;">Previous Due</th>
                                <th class="text-end" style="width: 150px;">Current Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $hostelFeeType = \App\Models\FeeType::where('name', 'Hostel Fee')->first();
                            @endphp
                            @foreach($students as $student)
                                <tr class="student-row">
                                    <td class="text-center">
                                        <input class="form-check-input student-checkbox" type="checkbox" name="students[{{ $student->id }}][generate]" value="1" checked>
                                        <input type="hidden" name="students[{{ $student->id }}][previous_due]" value="{{ $student->previous_due }}">
                                    </td>
                                    <td class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}<br><small class="text-muted">{{ $student->registration_number }}</small></td>
                                    
                                    @php $rowTotal = 0; @endphp
                                    @foreach($structures as $structure)
                                        @php
                                            // Find specific structure for this student's class, or fallback to 0
                                            $studentClassId = $student->enrollments->where('academic_year_id', request('academic_year_id'))->first()->academic_class_id ?? null;
                                            $studentStructure = \App\Models\FeeStructure::where('academic_year_id', request('academic_year_id'))
                                                ->where('fee_type_id', $structure->feeType->id)
                                                ->where(function($q) use ($studentClassId) {
                                                    $q->where('academic_class_id', $studentClassId)
                                                      ->orWhereNull('academic_class_id');
                                                })->first();
                                            $amt = $studentStructure ? $studentStructure->amount : 0;
                                            if ($amt > 0) $rowTotal += $amt;
                                        @endphp
                                        <td class="text-center bg-light">
                                            @if($amt > 0)
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-text">
                                                        <input class="form-check-input mt-0 fee-checkbox" type="checkbox" name="students[{{ $student->id }}][fees][{{ $structure->feeType->id }}][include]" value="1" checked>
                                                    </div>
                                                    <input type="number" step="0.01" min="0" class="form-control fee-amount" name="students[{{ $student->id }}][fees][{{ $structure->feeType->id }}][amount]" value="{{ $amt }}">
                                                </div>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    <!-- Hostel Fee -->
                                    <td class="text-center bg-light">
                                        @if($student->hostel_fee > 0 && $hostelFeeType)
                                            @php $rowTotal += $student->hostel_fee; @endphp
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-text">
                                                    <input class="form-check-input mt-0 fee-checkbox" type="checkbox" name="students[{{ $student->id }}][fees][{{ $hostelFeeType->id }}][include]" value="1" checked>
                                                </div>
                                                <input type="number" step="0.01" min="0" class="form-control fee-amount" name="students[{{ $student->id }}][fees][{{ $hostelFeeType->id }}][amount]" value="{{ $student->hostel_fee }}">
                                            </div>
                                        @else
                                            <span class="text-muted small">Not Allocated</span>
                                        @endif
                                    </td>

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
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                // Only select visible rows
                if(cb.closest('.student-row').style.display !== 'none') {
                    cb.checked = this.checked;
                }
            });
        });
    }

    // Student Search
    const searchInput = document.getElementById('studentSearch');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.student-row').forEach(row => {
                const text = row.cells[1].textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
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
