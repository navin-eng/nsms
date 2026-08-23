@extends('accounting.layout.master')
@push('b-title', 'Balance Sheet')

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800"><i class="bi bi-bank me-2 text-primary"></i>Balance Sheet</h4>
            <p class="text-muted mb-0 small">Financial position as of the selected date.</p>
        </div>
        <div class="w-100 w-md-auto">
            <form action="{{ route('accounting.reports.balance-sheet') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
                <div class="d-flex align-items-center gap-2 flex-grow-1 flex-sm-grow-0">
                    <span class="text-muted small">As of:</span>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-filter"></i> Filter</button>
            </form>
        </div>
    </div>

    <div class="row g-3 g-md-4">
        <!-- Assets Column -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Assets</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @forelse($assetAccounts as $account)
                            <tr>
                                <td class="ps-4">{{ $account->name }}</td>
                                <td class="text-end pe-4">{{ number_format($account->total_debit - $account->total_credit, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-3 text-muted">No asset entries found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold fs-5">
                            <tr>
                                <td class="text-end pe-4 py-3">Total Assets:</td>
                                <td class="text-end pe-4 py-3 border-top border-2 border-primary">{{ number_format($totalAssets, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Liabilities & Equity Column -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-bank"></i> Liabilities & Equity</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            <tr class="table-light"><td colspan="2" class="fw-bold ps-4 text-danger">Liabilities</td></tr>
                            @forelse($liabilityAccounts as $account)
                            <tr>
                                <td class="ps-5">{{ $account->name }}</td>
                                <td class="text-end pe-4">{{ number_format($account->total_credit - $account->total_debit, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center py-2 text-muted">No liability entries found.</td></tr>
                            @endforelse
                            <tr class="fw-bold">
                                <td class="text-end pe-4">Total Liabilities:</td>
                                <td class="text-end pe-4 border-top border-2">{{ number_format($totalLiabilities, 2) }}</td>
                            </tr>

                            <tr class="table-light"><td colspan="2" class="fw-bold ps-4 text-success">Equity</td></tr>
                            @foreach($equityAccounts as $account)
                            <tr>
                                <td class="ps-5">{{ $account->name }}</td>
                                <td class="text-end pe-4">{{ number_format($account->total_credit - $account->total_debit, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="ps-5">Retained Earnings <span class="text-muted small">(Net Income)</span></td>
                                <td class="text-end pe-4">{{ number_format($retainedEarnings, 2) }}</td>
                            </tr>
                            <tr class="fw-bold">
                                <td class="text-end pe-4">Total Equity:</td>
                                <td class="text-end pe-4 border-top border-2">{{ number_format($totalEquity + $retainedEarnings, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light fw-bold fs-5">
                            <tr>
                                <td class="text-end pe-4 py-3">Total L & E:</td>
                                <td class="text-end pe-4 py-3 border-top border-2 border-danger">{{ number_format($totalLiabilities + $totalEquity + $retainedEarnings, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Balance Check -->
    @php
        $isBalanced = round($totalAssets, 2) == round($totalLiabilities + $totalEquity + $retainedEarnings, 2);
    @endphp
    <div class="alert {{ $isBalanced ? 'alert-success' : 'alert-danger' }} d-flex align-items-center" role="alert">
        @if($isBalanced)
            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
            <div><strong>Balanced:</strong> Total Assets equal Total Liabilities and Equity.</div>
        @else
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div><strong>Out of Balance:</strong> The accounting equation does not match. Difference: {{ number_format(abs($totalAssets - ($totalLiabilities + $totalEquity + $retainedEarnings)), 2) }}</div>
        @endif
    </div>
</div>
@endsection
