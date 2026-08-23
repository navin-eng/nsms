@extends('accounting.layout.master')
@push('b-title', 'Outstanding Fee Report')

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800"><i class="bi bi-clock-history text-danger me-2"></i>Outstanding Fee Report</h4>
            <p class="text-muted mb-0 small">Track and manage unpaid student fees, arrears, and partial balances across classes.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm px-3 shadow-sm">
                <i class="bi bi-printer me-1"></i> Print Report
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-3 p-2 p-md-3 bg-danger bg-opacity-10 text-danger me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-exclamation-octagon fs-5"></i>
                        </div>
                        <div class="min-w-0 flex-grow-1">
                            <span class="text-muted small text-uppercase fw-semibold d-block text-truncate">Total Outstanding</span>
                            <h4 class="mb-0 fw-bold text-danger stat-value text-truncate">Rs. {{ number_format($totalOutstanding, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-3 p-2 p-md-3 bg-primary bg-opacity-10 text-primary me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-receipt fs-5"></i>
                        </div>
                        <div class="min-w-0 flex-grow-1">
                            <span class="text-muted small text-uppercase fw-semibold d-block text-truncate">Total Billed</span>
                            <h4 class="mb-0 fw-bold text-gray-800 stat-value text-truncate">Rs. {{ number_format($totalBilled, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-3 p-2 p-md-3 bg-success bg-opacity-10 text-success me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-check2-circle fs-5"></i>
                        </div>
                        <div class="min-w-0 flex-grow-1">
                            <span class="text-muted small text-uppercase fw-semibold d-block text-truncate">Total Collected</span>
                            <h4 class="mb-0 fw-bold text-success stat-value text-truncate">Rs. {{ number_format($totalPaid, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-3 p-2 p-md-3 bg-warning bg-opacity-10 text-warning me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-people fs-5"></i>
                        </div>
                        <div class="min-w-0 flex-grow-1">
                            <span class="text-muted small text-uppercase fw-semibold d-block text-truncate">Pending Invoices</span>
                            <h4 class="mb-0 fw-bold text-gray-800 stat-value text-truncate">{{ number_format($totalCount) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-transparent border-0 pt-3 pb-1 px-3 px-md-4">
            <h6 class="fw-bold mb-0 text-gray-800"><i class="bi bi-funnel me-1 text-primary"></i> Filter Invoices</h6>
        </div>
        <div class="card-body p-3 p-md-4 pt-2">
            <form method="GET" action="{{ route('accounting.fees.reports.outstanding') }}" class="row g-2 g-md-3 align-items-end">
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Academic Year</label>
                    <select name="academic_year_id" class="form-select form-select-sm rounded-3">
                        <option value="">All Academic Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-lg-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Class</label>
                    <select name="academic_class_id" class="form-select form-select-sm rounded-3">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('academic_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-lg-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Month</label>
                    <select name="nepali_month" class="form-select form-select-sm rounded-3">
                        <option value="">All Months</option>
                        @foreach($months as $m)
                            <option value="{{ $m }}" {{ request('nepali_month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm rounded-3">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Outstanding</option>
                        <option value="Unpaid" {{ request('status') == 'Unpaid' ? 'selected' : '' }}>Unpaid Only</option>
                        <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>Partial Only</option>
                    </select>
                </div>
                <div class="col-12 col-lg-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Search Student</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control rounded-start-3" placeholder="Name or Reg No..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary px-3"><i class="bi bi-search me-1"></i> Filter</button>
                        @if(request()->hasAny(['academic_year_id', 'academic_class_id', 'nepali_month', 'status', 'search']))
                            <a href="{{ route('accounting.fees.reports.outstanding') }}" class="btn btn-outline-secondary" title="Reset Filters"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Outstanding Invoices Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 ps-md-4 text-nowrap">Invoice #</th>
                            <th>Student</th>
                            <th class="text-nowrap">Class / Section</th>
                            <th>Title & Month</th>
                            <th class="text-end text-nowrap">Total</th>
                            <th class="text-end text-nowrap">Paid</th>
                            <th class="text-end text-danger text-nowrap">Outstanding</th>
                            <th class="text-center text-nowrap">Status</th>
                            <th class="text-nowrap">Due Date</th>
                            <th class="pe-3 pe-md-4 text-end text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            @php
                                $due = $invoice->total_amount - $invoice->paid_amount;
                                $enrollment = $invoice->student?->enrollments?->where('academic_year_id', $invoice->academic_year_id)->first() ?? $invoice->student?->enrollments?->first();
                            @endphp
                            <tr>
                                <td class="ps-3 ps-md-4 fw-bold font-monospace text-nowrap">#{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="fw-bold">{{ $invoice->student->first_name ?? 'Unknown' }} {{ $invoice->student->last_name ?? '' }}</div>
                                    <small class="text-muted">Reg: {{ $invoice->student->registration_number ?? 'N/A' }}</small>
                                </td>
                                <td class="text-nowrap">
                                    @if($enrollment && $enrollment->academicClass)
                                        <span class="badge bg-light text-dark border">{{ $enrollment->academicClass->name }}</span>
                                        @if($enrollment->section)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border">{{ $enrollment->section->name }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $invoice->title }}</div>
                                    <small class="text-muted">{{ $invoice->nepali_month ?? '' }} {{ $invoice->academicYear->name ?? '' }}</small>
                                </td>
                                <td class="text-end fw-semibold text-nowrap font-monospace">Rs. {{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="text-end text-success fw-semibold text-nowrap font-monospace">Rs. {{ number_format($invoice->paid_amount, 2) }}</td>
                                <td class="text-end text-danger fw-bold text-nowrap font-monospace">Rs. {{ number_format($due, 2) }}</td>
                                <td class="text-center text-nowrap">
                                    @if($invoice->status == 'Paid')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Paid</span>
                                    @elseif($invoice->status == 'Partial')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Partial</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Unpaid</span>
                                    @endif
                                </td>
                                <td class="text-nowrap small text-muted">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}</td>
                                <td class="pe-3 pe-md-4 text-end text-nowrap">
                                    <a href="{{ route('accounting.fees.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> Collect / View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="bi bi-check-circle fs-1 text-success d-block mb-2"></i>
                                    No outstanding invoices matching your criteria!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($invoices->hasPages())
                <div class="p-3 border-top d-flex justify-content-center">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
