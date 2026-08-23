@extends('accounting.layout.master')
@push('b-title', 'Expenses')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: var(--color-primary);">Expenses</h4>
            <p class="text-muted mb-0">Record and track school expenses</p>
        </div>
        <div>
            <a href="{{ route('accounting.expenses.create') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Record Expense
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">Date</th>
                            <th class="py-3">Reference</th>
                            <th class="py-3">Vendor</th>
                            <th class="py-3">Category</th>
                            <th class="py-3 text-end">Amount</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="pe-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td class="ps-4 py-3">{{ $expense->expense_date->format('M d, Y') }}</td>
                            <td class="py-3 font-monospace">{{ $expense->reference }}</td>
                            <td class="py-3">{{ $expense->vendor->name ?? '-' }}</td>
                            <td class="py-3">{{ $expense->expenseAccount->name ?? '-' }}</td>
                            <td class="py-3 text-end fw-semibold font-monospace">Rs. {{ number_format($expense->amount, 2) }}</td>
                            <td class="py-3 text-center">
                                @if($expense->status == 'paid')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Paid</span>
                                @elseif($expense->status == 'pending')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Cancelled</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <a href="#" class="btn btn-sm btn-light rounded-circle shadow-sm" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="bi bi-cart-dash-fill fs-1"></i></div>
                                No expenses recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
