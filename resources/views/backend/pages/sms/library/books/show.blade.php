@extends('backend.pages.layout.master')
@section('title', 'Book Details')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0 fw-bold">Book Details</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('sms.library.books.index') }}">Library</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('sms.library.books.edit', $book->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Book
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                @if($book->cover_image)
                    <img src="{{ asset('uploads/library/' . $book->cover_image) }}" alt="Cover"
                        class="img-fluid rounded shadow-sm mx-auto mb-3" style="max-width: 180px;">
                @else
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width: 150px; height: 200px;">
                        <i class="bi bi-book fs-1"></i>
                    </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $book->title }}</h5>
                <p class="text-muted mb-3">{{ $book->author ?? 'Unknown Author' }}</p>

                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span
                        class="badge bg-primary px-3 py-2 rounded-pill">{{ $book->category->name ?? 'Uncategorized' }}</span>
                </div>

                <div class="row g-2 text-start small">
                    <div class="col-6 text-muted">ISBN</div>
                    <div class="col-6 fw-semibold text-end">{{ $book->isbn ?? 'N/A' }}</div>
                    <div class="col-6 text-muted">Publisher</div>
                    <div class="col-6 fw-semibold text-end">{{ $book->publisher ?? 'N/A' }}</div>
                    <div class="col-6 text-muted">Rack No.</div>
                    <div class="col-6 fw-semibold text-end">{{ $book->rack_number ?? 'N/A' }}</div>
                    <div class="col-6 text-muted">Price</div>
                    <div class="col-6 fw-semibold text-end">${{ number_format($book->price, 2) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Synopsis / Description</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-muted">{{ $book->description ?? 'No description available for this book.' }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Physical Copies</h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary">Total: {{ $book->total_copies }}</span>
                        <span class="badge bg-success">Available: {{ $book->available_copies }}</span>
                        <a href="{{ route('sms.library.books.print-barcodes', $book->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm" style="padding: 0.1rem 0.5rem; font-size: 0.75rem;">
                            <i class="bi bi-printer"></i> Print Barcodes
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Copy Barcode</th>
                                    <th>Condition</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($book->copies as $copy)
                                    <tr>
                                        <td class="ps-4 fw-medium font-monospace">
                                            <a href="{{ route('sms.library.books.copy.history', $copy->id) }}" class="text-primary text-decoration-none">
                                                {{ $copy->barcode }}
                                                <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.75rem;"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ ucfirst($copy->condition) }}</span>
                                        </td>
                                        <td>
                                            @if($copy->status === 'available')
                                                <span class="badge bg-success bg-opacity-10 text-success"><i
                                                        class="bi bi-check-circle me-1"></i>Available</span>
                                            @elseif($copy->status === 'issued')
                                                <div>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning mb-1"><i
                                                            class="bi bi-person me-1"></i>Issued</span>
                                                </div>
                                                @if($copy->activeIssue && $copy->activeIssue->borrower)
                                                    <div class="text-muted" style="font-size: 0.7rem;">
                                                        To: {{ $copy->activeIssue->borrower->full_name }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger"><i
                                                        class="bi bi-x-circle me-1"></i>{{ ucfirst($copy->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection