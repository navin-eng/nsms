@extends('backend.pages.layout.master')
@section('title', 'Borrower History')

@push('styles')
<style>
    @media print {
        body { background-color: #fff; }
        .sidebar, .navbar, .action-buttons, .breadcrumb { display: none !important; }
        .backend-main { padding: 0 !important; margin: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
        .card-header, .card-body { padding: 0 !important; }
        
        .print-header { display: block !important; text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .print-signature { display: flex !important; justify-content: space-between; margin-top: 50px; }
        .signature-line { width: 200px; border-top: 1px solid #000; text-align: center; padding-top: 5px; font-weight: bold; }
        
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000 !important; padding: 8px !important; text-align: left; }
        .table th { background-color: #f8f9fa !important; font-weight: bold; }
    }
</style>
@endpush

@section('backend-content')
<div class="d-none print-header">
    <h2>{{ \App\Models\SiteSetting::first()->title ?? 'School Library' }}</h2>
    <h4>Borrower History Log</h4>
    <p>Generated on: {{ now()->format('F d, Y') }}</p>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 action-buttons">
    <div>
        <h5 class="mb-0 fw-bold">Borrower History</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('sms.library.issues.index') }}">Library Issues</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $borrower->full_name ?? 'Unknown' }}</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('sms.library.issues.index') }}" class="btn btn-light btn-sm me-2 border">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i> Print Record
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3 p-4 bg-light rounded">
                <div class="d-flex align-items-center gap-3">
                    @if(isset($borrower->photo) && $borrower->photo)
                        <img src="{{ asset('storage/' . $borrower->photo) }}" class="rounded-circle shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            {{ substr($borrower->first_name ?? 'U', 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h5 class="fw-bold mb-1">{{ $borrower->full_name ?? 'Unknown' }}</h5>
                        <div class="text-muted small">
                            <span class="badge bg-secondary">{{ ucfirst($type) }}</span>
                            @if($type === 'student')
                                <span class="ms-1">Adm: {{ $borrower->admission_no }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-end text-md-start">
                    <div class="text-muted small">Total Books Borrowed</div>
                    <div class="fw-bold fs-5 text-primary">{{ $issues->count() }}</div>
                </div>
                <div class="text-end text-md-start">
                    <div class="text-muted small">Currently Holding</div>
                    <div class="fw-bold fs-5 text-warning">{{ $issues->where('status', 'issued')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom d-print-none">
        <h6 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2 text-primary"></i>Borrowing Record</h6>
    </div>
    <div class="card-body p-0 p-print-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Book Details</th>
                        <th>Issue / Due Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Condition & Fine</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $issue)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $issue->bookCopy->book->title ?? 'N/A' }}</div>
                            <div class="text-muted small font-monospace"><i class="bi bi-upc-scan me-1"></i>{{ $issue->bookCopy->barcode ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-success"><i class="bi bi-arrow-right-circle me-1 d-print-none"></i>{{ $issue->formatted_issue_date }}</div>
                                <div class="text-danger mt-1"><i class="bi bi-calendar-x me-1 d-print-none"></i>{{ $issue->formatted_due_date }}</div>
                                @if($issue->return_date)
                                    <div class="text-primary mt-1"><i class="bi bi-arrow-left-circle me-1 d-print-none"></i>Ret: {{ $issue->formatted_return_date }}</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($issue->status === 'returned')
                                <span class="badge bg-success">Returned</span>
                            @elseif($issue->status === 'issued' && $issue->due_date < now())
                                <span class="badge bg-danger">Overdue</span>
                            @else
                                <span class="badge bg-warning text-dark">Issued</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($issue->return_date)
                                <div class="small text-muted mb-1">
                                    Cond: <strong>{{ ucfirst($issue->bookCopy->condition ?? 'N/A') }}</strong>
                                </div>
                                @if($issue->fine_amount > 0)
                                    <div class="small fw-bold text-danger">Fine: ${{ number_format($issue->fine_amount, 2) }} 
                                        @if($issue->fine_status === 'paid')
                                            <span class="badge bg-success bg-opacity-10 text-success ms-1 d-print-none">Paid</span>
                                            <span class="d-none d-print-inline">(Paid)</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger ms-1 d-print-none">Unpaid</span>
                                            <span class="d-none d-print-inline">(Unpaid)</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="small text-success">No Fine</div>
                                @endif
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="bi bi-journal-x fs-2 d-block mb-2 d-print-none"></i>
                            No library records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-none print-signature">
    <div>
        <div class="signature-line">Date</div>
    </div>
    <div>
        <div class="signature-line">Authorized Librarian Signature</div>
    </div>
</div>

@endsection
