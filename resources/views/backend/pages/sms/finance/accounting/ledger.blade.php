@extends('backend.pages.layout.master')

@section('title', 'General Ledger')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0">General Ledger</h3>
            <p class="text-muted">View detailed transactions and running balance for any account.</p>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 d-print-none">
        <div class="card-body p-4">
            <form action="{{ route('sms.finance.accounting.ledger') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Select Account <span class="text-danger">*</span></label>
                    <select name="account_id" class="form-select" required>
                        <option value="">Choose an account...</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $selectedAccount == $acc->id ? 'selected' : '' }}>
                                {{ $acc->code ? $acc->code . ' - ' : '' }}{{ $acc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedAccount)
        @php
            $account = \App\Models\Account::find($selectedAccount);
            $isDebitNormal = in_array($account->accountGroup->type, ['Assets', 'Expenses']);
        @endphp
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">{{ $account->name }} <span class="text-muted fs-6">({{ $account->accountGroup->name }})</span></h5>
                <small class="text-muted">Ledger Period: {{ date('M d, Y', strtotime($startDate)) }} to {{ date('M d, Y', strtotime($endDate)) }}</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Ref</th>
                                <th>Description</th>
                                <th class="text-end">Debit (Rs.)</th>
                                <th class="text-end">Credit (Rs.)</th>
                                <th class="text-end">Balance (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Opening Balance Row -->
                            <tr class="table-secondary">
                                <td colspan="3" class="fw-bold text-end">Opening Balance</td>
                                <td></td>
                                <td></td>
                                <td class="text-end fw-bold">
                                    {{ number_format(abs($openingBalance), 2) }} 
                                    @if($openingBalance > 0) ({{ $isDebitNormal ? 'Dr' : 'Cr' }}) 
                                    @elseif($openingBalance < 0) ({{ $isDebitNormal ? 'Cr' : 'Dr' }}) 
                                    @endif
                                </td>
                            </tr>

                            <!-- Transactions -->
                            @php $currentBalance = $openingBalance; @endphp
                            @forelse($items as $item)
                                @php
                                    $amount = $item->amount;
                                    if ($item->type == 'debit') {
                                        $currentBalance += $isDebitNormal ? $amount : -$amount;
                                    } else {
                                        $currentBalance += $isDebitNormal ? -$amount : $amount;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $item->journalEntry->entry_date->format('Y-m-d') }}</td>
                                    <td>{{ $item->journalEntry->reference }}</td>
                                    <td>{{ $item->journalEntry->description }}</td>
                                    <td class="text-end">{{ $item->type == 'debit' ? number_format($item->amount, 2) : '' }}</td>
                                    <td class="text-end">{{ $item->type == 'credit' ? number_format($item->amount, 2) : '' }}</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format(abs($currentBalance), 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No transactions found for this period.</td>
                                </tr>
                            @endforelse

                            <!-- Closing Balance -->
                            <tr class="table-primary fw-bold">
                                <td colspan="3" class="text-end">Closing Balance</td>
                                @php
                                    $totalDebits = $items->where('type', 'debit')->sum('amount');
                                    $totalCredits = $items->where('type', 'credit')->sum('amount');
                                @endphp
                                <td class="text-end">{{ number_format($totalDebits, 2) }}</td>
                                <td class="text-end">{{ number_format($totalCredits, 2) }}</td>
                                <td class="text-end fs-5">
                                    {{ number_format(abs($currentBalance), 2) }}
                                    @if($currentBalance > 0) ({{ $isDebitNormal ? 'Dr' : 'Cr' }}) 
                                    @elseif($currentBalance < 0) ({{ $isDebitNormal ? 'Cr' : 'Dr' }}) 
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-book fs-1 d-block mb-3"></i>
            Please select an account and date range to view its ledger.
        </div>
    @endif
</div>

<style>
    @media print {
        .admin-sidebar, .topbar, .btn, .d-print-none { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endsection
