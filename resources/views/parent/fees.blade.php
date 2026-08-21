@extends('parent.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">Fee Statements</h4>
        <p class="text-muted">View outstanding invoices and payment history for {{ $child->first_name }}.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Invoices</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Invoice #</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="ps-4 fw-medium text-primary">{{ $invoice->invoice_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                                    <td class="fw-bold">{{ number_format($invoice->net_amount, 2) }}</td>
                                    <td>
                                        @if($invoice->status == 'Paid')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Paid</span>
                                        @elseif($invoice->status == 'Partial')
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">Partial</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-receipt fs-1 d-block mb-3 text-black-50"></i>
                                        No invoices generated yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">Payment History</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($payments as $payment)
                        <li class="list-group-item p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold fs-6 text-success">+{{ number_format($payment->amount, 2) }}</span>
                                <span class="small text-muted">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="small text-muted">
                                Inv: {{ $payment->invoice->invoice_number }} | Ref: {{ $payment->reference_number ?? 'N/A' }}
                            </div>
                            <div class="small text-muted">Method: {{ $payment->payment_method }}</div>
                        </li>
                    @empty
                        <li class="list-group-item p-4 text-center text-muted">
                            No recent payments found.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
