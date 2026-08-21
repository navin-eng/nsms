@extends('backend.pages.layout.master')
@section('title', 'Issue History')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Library Issue History</h5>
        <p class="text-muted small mb-0">Track all borrowed and returned books.</p>
    </div>
    <a href="{{ route('sms.library.issues.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-book-half me-1"></i> Issue New Book
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('sms.library.issues.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by barcode or book title..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Currently Issued</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filter Records</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Book Details</th>
                        <th>Borrower</th>
                        <th>Issue / Due Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
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
                            <div class="fw-semibold text-primary">
                                @php
                                    $borrowerTypeUrl = $issue->borrower_type === 'App\Models\Student' ? 'student' : 'staff';
                                @endphp
                                <a href="{{ route('sms.library.borrowers.history', ['type' => $borrowerTypeUrl, 'id' => $issue->borrower_id]) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                    {{ $issue->borrower->full_name ?? 'Unknown' }}
                                    <i class="bi bi-box-arrow-up-right ms-1 text-primary" style="font-size: 0.75rem;"></i>
                                </a>
                            </div>
                            <div class="text-muted small">
                                @if($issue->borrower_type === 'App\Models\Student')
                                    <span class="badge bg-info bg-opacity-10 text-info px-1">Student</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-1">Staff</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-success"><i class="bi bi-arrow-right-circle me-1"></i>{{ $issue->formatted_issue_date }}</div>
                                <div class="text-danger mt-1"><i class="bi bi-calendar-x me-1"></i>{{ $issue->formatted_due_date }}</div>
                                @if($issue->return_date)
                                    <div class="text-primary mt-1"><i class="bi bi-arrow-left-circle me-1"></i>Returned: {{ $issue->formatted_return_date }}</div>
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
                            
                            @if($issue->fine_amount > 0)
                                <div class="mt-1 small fw-bold text-danger">Fine: ${{ number_format($issue->fine_amount, 2) }}</div>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($issue->status !== 'returned')
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#returnModal{{ $issue->id }}">
                                    Return Book
                                </button>
                                
                                <!-- Return Modal -->
                                <div class="modal fade text-start" id="returnModal{{ $issue->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Return Book</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('sms.library.issues.return', $issue->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body p-4">
                                                    <p class="mb-4">Are you sure you want to mark <strong>{{ $issue->bookCopy->book->title }}</strong> as returned?</p>
                                                    
                                                    @php
                                                        $daysLate = 0;
                                                        if(now() > $issue->due_date) {
                                                            $daysLate = now()->diffInDays($issue->due_date);
                                                        }
                                                        // Fallback fine to $5 if setting missing just for view logic
                                                        $finePerDay = \App\Models\LibrarySetting::first()->fine_per_day ?? 5;
                                                        $calculatedFine = $daysLate * $finePerDay;
                                                    @endphp
                                                    
                                                    @if($daysLate > 0)
                                                        <div class="alert alert-danger">
                                                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Book is <strong>{{ $daysLate }} days</strong> overdue!
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Book Return Condition <span class="text-danger">*</span></label>
                                                        <select name="condition" class="form-select" required>
                                                            <option value="good" {{ $issue->bookCopy->condition === 'good' ? 'selected' : '' }}>Good / As Issued</option>
                                                            <option value="new" {{ $issue->bookCopy->condition === 'new' ? 'selected' : '' }}>New</option>
                                                            <option value="fair" {{ $issue->bookCopy->condition === 'fair' ? 'selected' : '' }}>Fair</option>
                                                            <option value="poor" {{ $issue->bookCopy->condition === 'poor' ? 'selected' : '' }}>Poor / Torn</option>
                                                            <option value="damaged">Damaged (Unusable)</option>
                                                            <option value="lost">Lost</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Fine Amount ($)</label>
                                                        <input type="number" step="0.01" name="fine_amount" class="form-control text-danger fw-bold" value="{{ $calculatedFine }}">
                                                        <div class="form-text">You can manually override the calculated fine. (e.g. charge extra if lost)</div>
                                                    </div>
                                                    
                                                    @if($calculatedFine > 0)
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Fine Status</label>
                                                        <select name="fine_status" class="form-select">
                                                            <option value="paid">Paid Immediately</option>
                                                            <option value="unpaid">Unpaid (Add to Dues)</option>
                                                        </select>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Confirm Return</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small"><i class="bi bi-check-all fs-5"></i></span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox d-block fs-1 mb-3"></i>
                            No issue records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($issues->hasPages())
    <div class="card-footer bg-white border-top border-light py-3">
        {{ $issues->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
