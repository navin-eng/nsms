@extends('student.layout.master')

@section('content')
<div class="d-flex align-items-center mb-4">
    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 50px; height: 50px;">
        <i class="bi bi-collection-fill fs-4"></i>
    </div>
    <div>
        <h2 class="fw-bold mb-0 text-dark">My Library Books</h2>
        <p class="text-muted mb-0">Track your current and past book borrowings.</p>
    </div>
</div>

<ul class="nav nav-pills mb-4 nav-fill shadow-sm rounded-pill p-1 bg-white border" id="libraryTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill fw-bold" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab">
            <i class="bi bi-book-half me-1"></i> Currently Borrowed ({{ $activeIssues->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill fw-bold" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">
            <i class="bi bi-clock-history me-1"></i> Past History ({{ $pastIssues->count() }})
        </button>
    </li>
</ul>

<div class="tab-content" id="libraryTabContent">
    <div class="tab-pane fade show active" id="current" role="tabpanel">
        <div class="row g-4">
            @forelse($activeIssues as $issue)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 {{ $issue->status == 'overdue' || $issue->due_date < now() ? 'border-danger border-2 border' : '' }}">
                    <div class="card-body p-4 text-center position-relative">
                        @if($issue->status == 'overdue' || $issue->due_date < now())
                            <span class="position-absolute top-0 end-0 badge bg-danger rounded-pill m-3 shadow-sm px-3 py-2">Overdue</span>
                        @else
                            <span class="position-absolute top-0 end-0 badge bg-success rounded-pill m-3 shadow-sm px-3 py-2">Active</span>
                        @endif
                        
                        @if($issue->bookCopy->book->cover_image)
                            <img src="{{ asset('uploads/library/' . $issue->bookCopy->book->cover_image) }}" alt="Cover" class="img-fluid rounded shadow-sm mb-3" style="height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-secondary bg-opacity-10 text-secondary rounded d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 80px; height: 120px;">
                                <i class="bi bi-book fs-1"></i>
                            </div>
                        @endif
                        
                        <h5 class="fw-bold mb-1 text-truncate">{{ $issue->bookCopy->book->title ?? 'Unknown Book' }}</h5>
                        <p class="text-muted small mb-3">{{ $issue->bookCopy->book->author ?? 'Unknown Author' }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded-3 small text-start">
                            <div class="text-muted">Due Date</div>
                            <div class="fw-bold {{ $issue->status == 'overdue' || $issue->due_date < now() ? 'text-danger' : 'text-dark' }}">
                                {{ $issue->due_date->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white">
                    <i class="bi bi-inbox text-muted fs-1 mb-3"></i>
                    <h5 class="fw-bold text-dark">No Active Books</h5>
                    <p class="text-muted mb-0">You don't have any books borrowed right now.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    
    <div class="tab-pane fade" id="past" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Book Details</th>
                            <th>Borrowed On</th>
                            <th>Returned On</th>
                            <th class="text-end pe-4">Fine</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pastIssues as $issue)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $issue->bookCopy->book->title ?? 'Unknown' }}</div>
                                <div class="text-muted small font-monospace"><i class="bi bi-upc-scan me-1"></i>{{ $issue->bookCopy->barcode }}</div>
                            </td>
                            <td>{{ $issue->issue_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success fw-medium">
                                    <i class="bi bi-check-circle-fill me-1"></i> {{ $issue->return_date ? $issue->return_date->format('M d, Y') : 'N/A' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                @if($issue->fine_amount > 0)
                                    <span class="text-danger fw-bold">${{ number_format($issue->fine_amount, 2) }}</span>
                                    @if($issue->fine_status == 'paid')
                                        <div class="small text-success"><i class="bi bi-check-all"></i> Paid</div>
                                    @else
                                        <div class="small text-warning">Unpaid</div>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x d-block fs-1 mb-3"></i>
                                No past history found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
