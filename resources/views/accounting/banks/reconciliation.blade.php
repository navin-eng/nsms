@extends('accounting.layout.master')
@push('b-title', 'Bank Reconciliation')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: var(--color-primary);">Bank Reconciliation</h4>
            <p class="text-muted mb-0">Match system transactions with physical bank statements</p>
        </div>
    </div>

    <!-- Selection Area -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('accounting.banks.reconciliation') }}" method="GET" class="row align-items-end g-3">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Select Bank Account to Reconcile</label>
                    <select name="bank_id" class="form-select form-select-lg rounded-3" required onchange="this.form.submit()">
                        <option value="">Select Bank Account...</option>
                        @foreach($bankAccounts as $bank)
                            <option value="{{ $bank->id }}" {{ ($selectedBank && $selectedBank->id == $bank->id) ? 'selected' : '' }}>
                                {{ $bank->bank_name }} - {{ $bank->account_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($selectedBank)
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block bg-light px-4 py-2 rounded-3 text-start">
                        <span class="text-muted small d-block mb-1">Ledger Account Linked:</span>
                        <span class="fw-bold">{{ $selectedBank->account->name ?? 'N/A' }}</span>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    @if($selectedBank)
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Uncleared Transactions</h5>
                <span class="badge bg-primary rounded-pill">{{ $unclearedItems->count() }} Pending</span>
            </div>
            <div class="card-body p-4">
                @if($unclearedItems->count() > 0)
                    <form action="{{ route('accounting.banks.reconcile') }}" method="POST">
                        @csrf
                        <div class="table-responsive mb-4">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </th>
                                        <th>Date</th>
                                        <th>Journal Ref</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unclearedItems as $item)
                                        <tr>
                                            <td>
                                                <input class="form-check-input reconcile-checkbox" type="checkbox" name="item_ids[]" value="{{ $item->id }}" data-amount="{{ $item->amount }}" data-type="{{ $item->type }}">
                                            </td>
                                            <td>{{ $item->journalEntry->entry_date->format('M d, Y') }}</td>
                                            <td><span class="badge bg-light text-dark font-monospace">{{ $item->journalEntry->reference ?? 'SYS-'.str_pad($item->journalEntry->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                                            <td>{{ $item->journalEntry->description }}</td>
                                            <td>
                                                @if($item->type == 'debit')
                                                    <span class="badge bg-success-subtle text-success">Money In (Debit)</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Money Out (Credit)</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-semibold font-monospace">Rs. {{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3">
                            <div>
                                <span class="text-muted small d-block">Selected for Reconciliation</span>
                                <div class="d-flex gap-4 mt-1">
                                    <div><span class="text-muted">Total Money In:</span> <span class="fw-bold text-success font-monospace" id="totalDebit">Rs. 0.00</span></div>
                                    <div><span class="text-muted">Total Money Out:</span> <span class="fw-bold text-danger font-monospace" id="totalCredit">Rs. 0.00</span></div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" id="reconcileBtn" disabled>
                                Mark as Reconciled
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <div class="bg-success-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-check2-all fs-1 text-success"></i>
                        </div>
                        <h5 class="fw-bold">All Caught Up!</h5>
                        <p class="text-muted mb-0">There are no pending uncleared transactions for this bank account.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-bank2 fs-1 text-muted"></i>
            </div>
            <h5 class="fw-bold text-muted">Select a bank account above to view pending reconciliations</h5>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.reconcile-checkbox');
        const btn = document.getElementById('reconcileBtn');
        const tdEl = document.getElementById('totalDebit');
        const tcEl = document.getElementById('totalCredit');

        function updateTotals() {
            let totalDebit = 0;
            let totalCredit = 0;
            let checkedCount = 0;

            checkboxes.forEach(cb => {
                if(cb.checked) {
                    checkedCount++;
                    let amt = parseFloat(cb.dataset.amount);
                    if(cb.dataset.type === 'debit') {
                        totalDebit += amt;
                    } else {
                        totalCredit += amt;
                    }
                }
            });

            if(tdEl) tdEl.textContent = 'Rs. ' + totalDebit.toLocaleString(undefined, {minimumFractionDigits: 2});
            if(tcEl) tcEl.textContent = 'Rs. ' + totalCredit.toLocaleString(undefined, {minimumFractionDigits: 2});
            
            if(btn) btn.disabled = (checkedCount === 0);
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateTotals();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotals);
        });
    });
</script>
@endpush
@endsection
