@extends('backend.pages.layout.master')
@section('title', 'Book Travel History')

@push('styles')
    <style>
        @media print {
            body {
                background-color: #fff;
            }

            .sidebar,
            .navbar,
            .action-buttons,
            .breadcrumb {
                display: none !important;
            }

            .backend-main {
                padding: 0 !important;
                margin: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-header,
            .card-body {
                padding: 0 !important;
            }

            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
            }

            .print-signature {
                display: flex !important;
                justify-content: space-between;
                margin-top: 50px;
            }

            .signature-line {
                width: 200px;
                border-top: 1px solid #000;
                text-align: center;
                padding-top: 5px;
                font-weight: bold;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .table th,
            .table td {
                border: 1px solid #000 !important;
                padding: 8px !important;
                text-align: left;
            }

            .table th {
                background-color: #f8f9fa !important;
                font-weight: bold;
            }
        }

        .timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 20px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #3b82f6;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #3b82f6;
        }

        .timeline-date {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .timeline-content {
            background: #fff;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }
    </style>
@endpush

@section('backend-content')
    <div class="d-none print-header">
        <h2>{{ \App\Models\SiteSetting::first()->title ?? 'School Library' }}</h2>
        <h4>Book Travel History Log</h4>
        <p>Generated on: {{ now()->format('F d, Y') }}</p>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 action-buttons">
        <div>
            <h5 class="mb-0 fw-bold">Book Travel History</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('sms.library.books.index') }}">Library</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('sms.library.books.show', $copy->book->id) }}">{{ $copy->book->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $copy->barcode }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('sms.library.books.show', $copy->book->id) }}" class="btn btn-light btn-sm me-2 border">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Print History
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div
                    class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3 p-4 bg-light rounded">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $copy->book->title }}</h5>
                        <div class="text-muted small">By {{ $copy->book->author ?? 'Unknown' }}</div>
                    </div>
                    <div class="text-end text-md-start">
                        <div class="text-muted small">Barcode Number</div>
                        <div class="fw-bold font-monospace fs-5 text-primary">{{ $copy->barcode }}</div>
                    </div>
                    <div class="text-end text-md-start">
                        <div class="text-muted small">Current Status</div>
                        <div>
                            @if($copy->status === 'available')
                                <span class="badge bg-success">Available</span>
                            @elseif($copy->status === 'issued')
                                <span class="badge bg-warning text-dark">Issued</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($copy->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-end text-md-start">
                        <div class="text-muted small">Current Condition</div>
                        <div class="fw-medium">{{ ucfirst($copy->condition ?? 'N/A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- For Print Output -->
    <div class="d-none d-print-block">
        <table class="table">
            <thead>
                <tr>
                    <th>Date Issued</th>
                    <th>Borrower</th>
                    <th>Date Returned</th>
                    <th>Condition Returned</th>
                    <th>Fine Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($copy->issues as $issue)
                    <tr>
                        <td>{{ $issue->formatted_issue_date }}</td>
                        <td>
                            {{ $issue->borrower ? $issue->borrower->full_name : 'Unknown' }}
                            ({{ str_replace('App\\Models\\', '', $issue->borrower_type) }})
                        </td>
                        <td>{{ $issue->return_date ? $issue->formatted_return_date : 'Not Returned Yet' }}</td>
                        <td>{{ $issue->return_date ? ucfirst($copy->condition) : '-' }}</td>
                        <td>
                            @if($issue->fine_amount > 0)
                                ${{ number_format($issue->fine_amount, 2) }} ({{ ucfirst($issue->fine_status) }})
                            @else
                                No Fine
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No issue records found for this book copy.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- For Web Display -->
    <div class="card border-0 shadow-sm d-print-none">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold">Chronological Log</h6>
        </div>
        <div class="card-body p-4">

            <div class="timeline">
                <!-- Initial Entry -->
                <div class="timeline-item">
                    <div class="timeline-date">{{ \Carbon\Carbon::parse($copy->created_at)->format('M d, Y - h:i A') }}
                    </div>
                    <div class="timeline-content">
                        <div class="fw-bold text-success"><i class="bi bi-box-seam me-2"></i>Added to Library Inventory
                        </div>
                        <div class="text-muted small mt-1">Barcode <strong>{{ $copy->barcode }}</strong> was registered into
                            the system in <strong>New</strong> condition.</div>
                    </div>
                </div>

                <!-- Issue Entries -->
                @foreach($copy->issues as $issue)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ $issue->formatted_issue_date }}</div>
                        <div class="timeline-content">
                            <div class="fw-bold text-primary"><i class="bi bi-person-check me-2"></i>Issued Book</div>
                            <div class="mt-2 text-muted small">
                                Issued to:
                                <strong
                                    class="text-dark">{{ $issue->borrower ? $issue->borrower->full_name : 'Unknown' }}</strong>
                                <span
                                    class="badge bg-secondary ms-1">{{ str_replace('App\\Models\\', '', $issue->borrower_type) }}</span>
                            </div>
                            <div class="mt-1 text-muted small">
                                Due Date: <span
                                    class="fw-medium">{{ $issue->formatted_due_date }}</span>
                            </div>
                        </div>
                    </div>

                    @if($issue->return_date)
                        <div class="timeline-item">
                            <div class="timeline-date">{{ $issue->formatted_return_date }}</div>
                            <div class="timeline-content">
                                <div class="fw-bold text-info"><i class="bi bi-arrow-return-left me-2"></i>Book Returned</div>
                                <div class="mt-2 text-muted small">
                                    Condition marked as: <strong class="text-dark">{{ ucfirst($copy->condition) }}</strong>
                                </div>

                                @if($issue->fine_amount > 0)
                                    <div class="mt-2 p-2 bg-danger bg-opacity-10 text-danger rounded small fw-medium">
                                        <i class="bi bi-exclamation-circle me-1"></i>
                                        Fine applied: ${{ number_format($issue->fine_amount, 2) }} -
                                        Status: {{ ucfirst($issue->fine_status) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach

                @if($copy->status === 'issued')
                    <div class="timeline-item">
                        <div class="timeline-date">Currently</div>
                        <div class="timeline-content border-warning bg-warning bg-opacity-10">
                            <div class="fw-bold text-warning-emphasis"><i class="bi bi-hourglass-split me-2"></i>Awaiting Return
                            </div>
                            <div class="text-muted small mt-1">This book is currently with the borrower.</div>
                        </div>
                    </div>
                @endif
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