@extends('backend.pages.layout.master')

@section('title', 'All Invoices')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0">All Invoices</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('sms.finance.invoices.generate') }}" class="btn btn-primary">
                <i class="bi bi-magic"></i> Generate Bulk Invoices
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Title</th>
                            <th>Total (Rs.)</th>
                            <th>Paid (Rs.)</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td>#{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="fw-bold">{{ $invoice->student->first_name ?? 'Unknown' }} {{ $invoice->student->last_name ?? '' }}</div>
                                    <small class="text-muted">ID: {{ $invoice->student->registration_number ?? '' }}</small>
                                </td>
                                <td>{{ $invoice->title }}<br><small class="text-muted">{{ $invoice->academicYear->name ?? '' }}</small></td>
                                <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                                <td>
                                    @if($invoice->status == 'Paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($invoice->status == 'Partial')
                                        <span class="badge bg-warning text-dark">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Unpaid</span>
                                    @endif
                                </td>
                                <td>{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('sms.finance.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No invoices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($invoices->hasPages())
                <div class="p-3 border-top">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
