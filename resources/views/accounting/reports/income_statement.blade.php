@extends('accounting.layout.master')
@push('b-title', 'Income Statement')

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800"><i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i>Income Statement</h4>
            <p class="text-muted mb-0 small">Profit and Loss for the selected period.</p>
        </div>
        <div class="w-100 w-md-auto">
            <form action="{{ route('accounting.reports.income-statement') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
                <div class="d-flex align-items-center gap-2 flex-grow-1 flex-sm-grow-0">
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                    <span class="text-muted small">to</span>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-filter"></i> Filter</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        <!-- Income Section -->
                        <tr class="table-light">
                            <td colspan="2" class="fw-bold ps-4 text-success"><i class="bi bi-arrow-down-circle-fill me-2"></i> Income</td>
                        </tr>
                        @forelse($incomeAccounts as $account)
                        <tr>
                            <td class="ps-5">{{ $account->name }} <span class="text-muted small">({{ $account->code }})</span></td>
                            <td class="text-end pe-4">{{ number_format($account->total_credit - $account->total_debit, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-3 text-muted">No income entries found.</td>
                        </tr>
                        @endforelse
                        <tr class="fw-bold">
                            <td class="text-end pe-4">Total Income:</td>
                            <td class="text-end pe-4 text-success border-top border-2">{{ number_format($totalIncome, 2) }}</td>
                        </tr>

                        <!-- Expenses Section -->
                        <tr class="table-light">
                            <td colspan="2" class="fw-bold ps-4 text-danger"><i class="bi bi-arrow-up-circle-fill me-2"></i> Expenses</td>
                        </tr>
                        @forelse($expenseAccounts as $account)
                        <tr>
                            <td class="ps-5">{{ $account->name }} <span class="text-muted small">({{ $account->code }})</span></td>
                            <td class="text-end pe-4">{{ number_format($account->total_debit - $account->total_credit, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-3 text-muted">No expense entries found.</td>
                        </tr>
                        @endforelse
                        <tr class="fw-bold">
                            <td class="text-end pe-4">Total Expenses:</td>
                            <td class="text-end pe-4 text-danger border-top border-2">{{ number_format($totalExpenses, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-dark">
                        <tr class="fs-5">
                            <td class="text-end pe-4 py-3">Net Income:</td>
                            <td class="text-end pe-4 py-3 {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($netIncome, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
