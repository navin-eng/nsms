@extends('backend.pages.layout.master')

@section('title', 'Day Book')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-0">Day Book</h3>
            <p class="text-muted">Chronological record of all transactions for a specific day.</p>
        </div>
        <div class="col-md-6 text-end">
            <form action="{{ route('sms.finance.accounting.daybook') }}" method="GET" class="d-inline-block">
                <div class="input-group">
                    <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> View</button>
                </div>
            </form>
            <button class="btn btn-outline-secondary ms-2" onclick="window.print()"><i class="bi bi-printer"></i></button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%">Time / Ref</th>
                            <th style="width: 45%">Particulars</th>
                            <th style="width: 20%" class="text-end">Debit (Rs.)</th>
                            <th style="width: 20%" class="text-end">Credit (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                            <!-- Description Row -->
                            <tr class="bg-light">
                                <td class="align-middle">
                                    <div class="fw-bold">{{ $entry->created_at->format('H:i') }}</div>
                                    <div class="text-muted small">{{ $entry->reference ?? 'SYS-GEN' }}</div>
                                </td>
                                <td colspan="3" class="fst-italic text-muted">
                                    {{ $entry->description }}
                                </td>
                            </tr>
                            
                            <!-- Items Rows -->
                            @php
                                $totalDebit = 0;
                                $totalCredit = 0;
                            @endphp
                            @foreach($entry->items as $item)
                                @php
                                    if($item->type == 'debit') $totalDebit += $item->amount;
                                    else $totalCredit += $item->amount;
                                @endphp
                                <tr>
                                    <td></td>
                                    <td class="{{ $item->type == 'credit' ? 'ps-5' : 'fw-bold' }}">
                                        {{ $item->account->name }}
                                        @if($item->type == 'credit') <small class="text-muted">(Cr)</small> @else <small class="text-muted">(Dr)</small> @endif
                                    </td>
                                    <td class="text-end">{{ $item->type == 'debit' ? number_format($item->amount, 2) : '' }}</td>
                                    <td class="text-end">{{ $item->type == 'credit' ? number_format($item->amount, 2) : '' }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    No transactions recorded on this date.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .admin-sidebar, .topbar, .btn { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endsection
