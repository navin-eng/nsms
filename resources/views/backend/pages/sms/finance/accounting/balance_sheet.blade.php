@extends('backend.pages.layout.master')

@section('title', 'Balance Sheet')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0">Balance Sheet</h3>
            <p class="text-muted">Statement of financial position.</p>
        </div>
        <div class="col-md-6 text-end">
            <form action="{{ route('sms.finance.accounting.balance-sheet') }}" method="GET" class="d-inline-block d-print-none">
                <div class="input-group">
                    <span class="input-group-text">As of</span>
                    <input type="date" name="date" class="form-control" value="{{ $asOfDate }}" onchange="this.form.submit()">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
            </form>
            <button class="btn btn-outline-secondary ms-2 d-print-none" onclick="window.print()"><i class="bi bi-printer"></i></button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white text-center py-4 border-bottom">
            <h4 class="fw-bold mb-1">Balance Sheet</h4>
            <div class="text-muted">As of {{ date('F d, Y', strtotime($asOfDate)) }}</div>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <!-- Assets -->
                <div class="col-md-6 border-end pe-md-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-primary">Assets</h5>
                    @if(isset($report['Assets']))
                        @foreach($report['Assets'] as $group)
                            <div class="mb-4">
                                <h6 class="fw-bold text-muted">{{ $group['group_name'] }}</h6>
                                <table class="table table-sm table-borderless mb-1">
                                    @foreach($group['accounts'] as $account)
                                        <tr>
                                            <td class="ps-3">{{ $account['name'] }}</td>
                                            <td class="text-end">{{ number_format($account['balance'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="border-top fw-semibold">
                                        <td class="ps-3 text-end">Total {{ $group['group_name'] }}</td>
                                        <td class="text-end">{{ number_format($group['total'], 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    @else
                        <div class="text-muted fst-italic ps-3">No asset balances.</div>
                    @endif
                    
                    <div class="mt-auto pt-4 border-top">
                        <table class="table table-borderless mb-0">
                            <tr class="fs-5 fw-bold text-primary">
                                <td>Total Assets</td>
                                <td class="text-end">Rs. {{ number_format($totals['Assets'], 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Liabilities and Equity -->
                <div class="col-md-6 ps-md-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-danger">Liabilities & Equity</h5>
                    
                    <!-- Liabilities -->
                    @if(isset($report['Liabilities']))
                        @foreach($report['Liabilities'] as $group)
                            <div class="mb-4">
                                <h6 class="fw-bold text-muted">{{ $group['group_name'] }}</h6>
                                <table class="table table-sm table-borderless mb-1">
                                    @foreach($group['accounts'] as $account)
                                        <tr>
                                            <td class="ps-3">{{ $account['name'] }}</td>
                                            <td class="text-end">{{ number_format($account['balance'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="border-top fw-semibold">
                                        <td class="ps-3 text-end">Total {{ $group['group_name'] }}</td>
                                        <td class="text-end">{{ number_format($group['total'], 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    @else
                        <div class="mb-4 text-muted fst-italic ps-3">No liability balances.</div>
                    @endif

                    <!-- Equity -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted">Equity</h6>
                        <table class="table table-sm table-borderless mb-1">
                            @if(isset($report['Equity']))
                                @foreach($report['Equity'] as $group)
                                    @foreach($group['accounts'] as $account)
                                        <tr>
                                            <td class="ps-3">{{ $account['name'] }}</td>
                                            <td class="text-end">{{ number_format($account['balance'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endif
                            <!-- Add Net Income from P&L to Equity -->
                            <tr>
                                <td class="ps-3">Current Year Earnings (Net Income)</td>
                                <td class="text-end {{ $netIncome < 0 ? 'text-danger' : '' }}">{{ number_format($netIncome, 2) }}</td>
                            </tr>
                            <tr class="border-top fw-semibold">
                                <td class="ps-3 text-end">Total Equity</td>
                                <td class="text-end">{{ number_format($totals['Equity'] + $netIncome, 2) }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="mt-auto pt-4 border-top">
                        <table class="table table-borderless mb-0">
                            <tr class="fs-5 fw-bold text-danger">
                                <td>Total Liabilities & Equity</td>
                                <td class="text-end">Rs. {{ number_format($totalEquityAndLiabilities, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Balance Check -->
            @if(round($totals['Assets'], 2) != round($totalEquityAndLiabilities, 2))
                <div class="alert alert-danger mt-4 text-center">
                    <i class="bi bi-exclamation-triangle-fill"></i> Warning: Balance Sheet is out of balance by Rs. {{ number_format(abs($totals['Assets'] - $totalEquityAndLiabilities), 2) }}
                </div>
            @endif

            <!-- Mini Income Statement summary at the bottom for reference -->
            <div class="mt-5 p-4 bg-light rounded d-print-none">
                <h6 class="fw-bold mb-3 text-muted border-bottom pb-2">Income & Expenses Summary (Rolls into Equity)</h6>
                <div class="row">
                    <div class="col-sm-4">
                        <div>Total Income: <span class="fw-bold text-success">{{ number_format($totals['Income'], 2) }}</span></div>
                    </div>
                    <div class="col-sm-4">
                        <div>Total Expenses: <span class="fw-bold text-danger">{{ number_format($totals['Expenses'], 2) }}</span></div>
                    </div>
                    <div class="col-sm-4 text-end">
                        <div>Net Income: <span class="fw-bold fs-5 {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($netIncome, 2) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .admin-sidebar, .topbar, .btn { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
        .bg-light { background-color: transparent !important; }
    }
</style>
@endsection
