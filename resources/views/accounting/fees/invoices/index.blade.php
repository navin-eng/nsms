@extends('accounting.layout.master')
@push('b-title', 'Fee Invoices')

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800"><i class="bi bi-receipt me-2 text-primary"></i>All Invoices</h4>
            <p class="text-muted mb-0 small">Manage, track and generate student invoices.</p>
        </div>
        <div>
            <a href="{{ route('accounting.fees.invoices.generate') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="bi bi-magic me-1"></i> Generate Bulk Invoices
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
                                    <a href="{{ route('accounting.fees.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary">
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
