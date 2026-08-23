@extends('accounting.layout.master')
@push('b-title', 'Trial Balance')

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800"><i class="bi bi-file-earmark-spreadsheet me-2 text-primary"></i>Trial Balance</h4>
            <p class="text-muted mb-0 small">Summary of all account balances to ensure debits equal credits.</p>
        </div>
        <div class="w-100 w-md-auto">
            <form action="{{ route('accounting.reports.trial-balance') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
                <div class="d-flex align-items-center gap-2 flex-grow-1 flex-sm-grow-0">
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                    <span class="text-muted small">to</span>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-filter"></i> Filter</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Account Code</th>
                            <th>Account Name</th>
                            <th>Type</th>
                            <th class="text-end">Debit</th>
                            <th class="text-end pe-4">Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalDebit = 0;
                            $totalCredit = 0;
                        @endphp
                        @forelse($accounts as $account)
                            @php
                                $totalDebit += $account->total_debit;
                                $totalCredit += $account->total_credit;
                            @endphp
                            <tr>
                                <td class="ps-4">{{ $account->code }}</td>
                                <td>{{ $account->name }}</td>
                                <td><span class="badge bg-secondary">{{ $account->accountGroup->type }}</span></td>
                                <td class="text-end">{{ number_format($account->total_debit, 2) }}</td>
                                <td class="text-end pe-4">{{ number_format($account->total_credit, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No transactions found for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-end">Total:</td>
                            <td class="text-end {{ $totalDebit != $totalCredit ? 'text-danger' : 'text-success' }}">{{ number_format($totalDebit, 2) }}</td>
                            <td class="text-end pe-4 {{ $totalDebit != $totalCredit ? 'text-danger' : 'text-success' }}">{{ number_format($totalCredit, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
