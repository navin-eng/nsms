@extends('accounting.layout.master')
@push('b-title', 'Record Expense')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('accounting.expenses.index') }}" class="btn btn-light rounded-circle shadow-sm me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-1 fw-bold" style="color: var(--color-primary);">Record Expense</h4>
            <p class="text-muted mb-0">Enter a new school expense</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 max-w-4xl">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('accounting.expenses.store') }}" method="POST">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label text-muted small fw-semibold">Expense Date *</label>
                        <input type="date" name="expense_date" class="form-control form-control-lg rounded-3" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold">Reference / Receipt No. *</label>
                        <input type="text" name="reference" class="form-control form-control-lg rounded-3" placeholder="e.g. EXP-2023-001" value="EXP-{{ date('Ymd') }}-{{ rand(100,999) }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Vendor / Payee</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shop"></i></span>
                        <select name="vendor_id" class="form-select form-select-lg border-start-0 rounded-end-3">
                            <option value="">Select Vendor...</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-text mt-2"><a href="{{ route('accounting.vendors.index') }}" class="text-decoration-none"><i class="bi bi-plus"></i> Add new vendor</a></div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="form-label text-muted small fw-semibold">Expense Category *</label>
                        <select name="expense_account_id" class="form-select form-select-lg rounded-3" required>
                            <option value="">Select Category...</option>
                            @foreach($expenseAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold">Payment Source *</label>
                        <select name="payment_account_id" class="form-select form-select-lg rounded-3" required>
                            <option value="">Select Bank/Cash...</option>
                            @foreach($paymentAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Amount (Rs) *</label>
                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text bg-white border-0 fw-bold fs-4 text-muted ps-4">Rs.</span>
                        <input type="number" step="0.01" min="0" name="amount" class="form-control form-control-lg border-0 fs-3 fw-bold py-3" placeholder="0.00" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-semibold">Description / Notes</label>
                    <textarea name="description" class="form-control form-control-lg rounded-3" rows="3" placeholder="What was this expense for?"></textarea>
                </div>

                <div class="mb-4 p-4 bg-light rounded-4 border">
                    <h6 class="fw-bold mb-3">Status & Posting</h6>
                    <div class="form-check form-check-inline mb-2">
                        <input class="form-check-input" type="radio" name="status" id="statusPaid" value="paid" checked>
                        <label class="form-check-label fw-semibold" for="statusPaid">
                            <span class="badge bg-success-subtle text-success">Paid</span> (Auto-posts Journal Entry)
                        </label>
                    </div>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="statusPending" value="pending">
                        <label class="form-check-label fw-semibold" for="statusPending">
                            <span class="badge bg-warning-subtle text-warning">Pending</span> (Save as draft, no ledger impact)
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                    <a href="{{ route('accounting.expenses.index') }}" class="btn btn-light btn-lg rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">Record Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
