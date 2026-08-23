@extends('accounting.layout.master')
@push('b-title', 'Invoice #' . str_pad($invoice->id, 5, '0', STR_PAD_LEFT))

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800"><i class="bi bi-receipt me-2 text-primary"></i>Invoice #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</h4>
            <p class="text-muted mb-0 small">Student fee invoice and payment history.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('accounting.fees.invoices.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Invoices
            </a>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('accounting.fees.invoices.print', ['invoice' => $invoice->id, 'size' => 'a4']) }}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-printer me-1"></i> Print A4
                </a>
                <a href="{{ route('accounting.fees.invoices.print', ['invoice' => $invoice->id, 'size' => 'a5']) }}" target="_blank" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-1"></i> Print A5
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Invoice Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row mb-4 border-bottom pb-3">
                        <div class="col-sm-6">
                            <h5 class="fw-bold mb-1">Student Details</h5>
                            <div>{{ $invoice->student->first_name ?? '' }} {{ $invoice->student->last_name ?? '' }}</div>
                            <div class="text-muted small">Reg No: {{ $invoice->student->registration_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <h5 class="fw-bold mb-1">Invoice Info</h5>
                            <div>Status: 
                                @if($invoice->status == 'Paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($invoice->status == 'Partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </div>
                            <div class="text-muted small">Due Date: {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}</div>
                            <div class="text-muted small">Academic Year: {{ $invoice->academicYear->name ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3">{{ $invoice->title }}</h5>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Fee Description</th>
                                    <th class="text-end">Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td>{{ $item->feeType->name ?? 'Fee' }}</td>
                                        <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-end fw-bold">Subtotal</td>
                                    <td class="text-end fw-bold">{{ number_format($invoice->subtotal, 2) }}</td>
                                </tr>
                                @if($invoice->discount_amount > 0)
                                <tr>
                                    <td class="text-end text-success">Discount</td>
                                    <td class="text-end text-success">- {{ number_format($invoice->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($invoice->fine_amount > 0)
                                <tr>
                                    <td class="text-end text-danger">Fine</td>
                                    <td class="text-end text-danger">+ {{ number_format($invoice->fine_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="table-light">
                                    <td class="text-end fw-bold fs-5">Total Amount</td>
                                    <td class="text-end fw-bold fs-5">Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-end text-success fw-bold">Amount Paid</td>
                                    <td class="text-end text-success fw-bold">Rs. {{ number_format($invoice->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-end text-danger fw-bold">Balance Due</td>
                                    <td class="text-end text-danger fw-bold">Rs. {{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($invoice->payments->count() > 0)
                        <h5 class="fw-bold mb-3">Payment History</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Ref No.</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}</td>
                                            <td>{{ $payment->payment_method }}</td>
                                            <td>{{ $payment->reference_number ?? '-' }}</td>
                                            <td class="text-end text-success">Rs. {{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="col-lg-4">
            @if($invoice->status != 'Paid')
                <div class="card border-0 shadow-sm d-print-none">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 py-1">Record Payment</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('accounting.fees.payments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="fee_invoice_id" value="{{ $invoice->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Amount (Rs.) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" max="{{ $invoice->total_amount - $invoice->paid_amount }}" name="amount" class="form-control" value="{{ $invoice->total_amount - $invoice->paid_amount }}" required>
                                <div class="form-text text-danger">Max: Rs. {{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="Cash">Cash</option>
                                    <option value="QR Code">QR Code</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deposit To Account <span class="text-danger">*</span></label>
                                <select name="account_id" class="form-select" required>
                                    @foreach(\App\Models\Account::whereHas('accountGroup', function($q) {
                                        $q->where('type', 'Assets');
                                    })->get() as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">The accounting ledger to receive funds.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Reference Number</label>
                                <input type="text" name="reference_number" class="form-control" placeholder="Cheque no / Transaction ID">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Save Payment
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-success d-print-none text-center p-4 shadow-sm">
                    <i class="bi bi-check-circle-fill fs-1 d-block mb-3"></i>
                    <h5 class="fw-bold mb-0">Invoice Fully Paid</h5>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        .admin-sidebar, .topbar, .d-print-none { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
        .container-fluid { padding: 0 !important; }
    }
</style>
@endsection
