@extends('accounting.layout.master')
@push('b-title', 'Accounting Dashboard')

@section('backend-content')
<div class="container-fluid px-2 px-sm-3 px-md-4 py-3 py-md-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: var(--color-primary);">Financial Overview</h4>
            <p class="text-muted mb-0 small">Secure Accounting & Finance Portal</p>
        </div>
        <div>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                <i class="bi bi-shield-lock-fill me-1"></i> Secure Portal
            </span>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-bank fs-5"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted small text-uppercase fw-semibold">Total Bank Balance</h6>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 stat-value">Rs. 0</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-graph-up-arrow fs-5"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted small text-uppercase fw-semibold">Income (This Month)</h6>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-success stat-value">Rs. 0</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-graph-down-arrow fs-5"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted small text-uppercase fw-semibold">Expenses (This Month)</h6>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-danger stat-value">Rs. 0</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-check-circle fs-5"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted small text-uppercase fw-semibold">Pending Reconciliations</h6>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 stat-value">0</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
