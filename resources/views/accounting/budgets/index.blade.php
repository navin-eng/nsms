@extends('accounting.layout.master')
@push('b-title', 'Budgets & Forecasting')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: var(--color-primary);">Budgets & Forecasting</h4>
            <p class="text-muted mb-0">Track spending limits across different expense categories</p>
        </div>
        <div>
            <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addBudgetModal">
                <i class="bi bi-plus-lg me-1"></i> Create Budget
            </button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        @forelse($budgets as $budget)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-light text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bi bi-pie-chart fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $budget->account->name ?? 'Unknown' }}</h5>
                                <span class="badge bg-secondary-subtle text-secondary">{{ $budget->fiscal_year ?? 'Custom' }}</span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle border-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editBudgetModal{{ $budget->id }}"><i class="bi bi-pencil me-2 text-muted"></i> Edit Budget</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('accounting.budgets.destroy', $budget) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete this budget?')">
                                            <i class="bi bi-trash me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1 small text-muted">
                            <span>Period:</span>
                            <span class="fw-semibold text-dark">{{ $budget->start_date->format('M d, Y') }} - {{ $budget->end_date->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small text-muted">
                            <span>Total Budget:</span>
                            <span class="fw-bold text-dark font-monospace">Rs. {{ number_format($budget->amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Actual Spent:</span>
                            <span class="fw-bold {{ $budget->actual > $budget->amount ? 'text-danger' : 'text-primary' }} font-monospace">Rs. {{ number_format($budget->actual, 2) }}</span>
                        </div>
                    </div>

                    @php
                        $colorClass = 'bg-primary';
                        if ($budget->usage_percent >= 100) $colorClass = 'bg-danger';
                        elseif ($budget->usage_percent >= 80) $colorClass = 'bg-warning';
                    @endphp

                    <div>
                        <div class="d-flex justify-content-between mb-1 small fw-semibold">
                            <span>Usage</span>
                            <span class="{{ $budget->usage_percent >= 100 ? 'text-danger' : '' }}">{{ $budget->usage_percent }}%</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 10px;">
                            <div class="progress-bar {{ $colorClass }} rounded-pill" role="progressbar" style="width: {{ $budget->usage_percent }}%" aria-valuenow="{{ $budget->usage_percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="mt-2 text-end small text-muted">
                            Remaining: <span class="fw-bold font-monospace {{ $budget->remaining < 0 ? 'text-danger' : '' }}">Rs. {{ number_format($budget->remaining, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editBudgetModal{{ $budget->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <form action="{{ route('accounting.budgets.update', $budget) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">Edit Budget</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Expense Category *</label>
                                <select name="account_id" class="form-select form-select-lg rounded-3" required>
                                    <option value="">Select Category...</option>
                                    @foreach($expenseAccounts as $acc)
                                        <option value="{{ $acc->id }}" {{ $budget->account_id == $acc->id ? 'selected' : '' }}>{{ $acc->name }} ({{ $acc->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">Start Date *</label>
                                    <input type="date" name="start_date" class="form-control form-control-lg rounded-3" value="{{ $budget->start_date->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">End Date *</label>
                                    <input type="date" name="end_date" class="form-control form-control-lg rounded-3" value="{{ $budget->end_date->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">Fiscal Year</label>
                                    <input type="text" name="fiscal_year" class="form-control form-control-lg rounded-3" value="{{ $budget->fiscal_year }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">Budget Amount (Rs) *</label>
                                    <input type="number" step="0.01" min="0" name="amount" class="form-control form-control-lg rounded-3" value="{{ $budget->amount }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Notes</label>
                                <textarea name="notes" class="form-control form-control-lg rounded-3" rows="2">{{ $budget->notes }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Update Budget</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-pie-chart fs-1 text-muted"></i>
                    </div>
                    <h5 class="fw-bold">No Budgets Setup</h5>
                    <p class="text-muted mb-4">Set spending limits on your expense categories to keep your school's finances on track.</p>
                    <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addBudgetModal">
                        <i class="bi bi-plus-lg me-1"></i> Create First Budget
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addBudgetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('accounting.budgets.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Create New Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Expense Category *</label>
                        <select name="account_id" class="form-select form-select-lg rounded-3" required>
                            <option value="">Select Category...</option>
                            @foreach($expenseAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">Start Date *</label>
                            <input type="date" name="start_date" class="form-control form-control-lg rounded-3" value="{{ date('Y-m-01') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">End Date *</label>
                            <input type="date" name="end_date" class="form-control form-control-lg rounded-3" value="{{ date('Y-m-t') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">Fiscal Year</label>
                            <input type="text" name="fiscal_year" class="form-control form-control-lg rounded-3" value="{{ date('Y') }}-{{ date('Y')+1 }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">Budget Amount (Rs) *</label>
                            <input type="number" step="0.01" min="0" name="amount" class="form-control form-control-lg rounded-3" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control form-control-lg rounded-3" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Budget</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
