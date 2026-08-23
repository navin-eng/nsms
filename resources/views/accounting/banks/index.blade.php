@extends('accounting.layout.master')
@push('b-title', 'Bank Accounts')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: var(--color-primary);">Bank Accounts</h4>
            <p class="text-muted mb-0">Manage official school bank accounts</p>
        </div>
        <div>
            <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addBankModal">
                <i class="bi bi-plus-lg me-1"></i> Add Bank Account
            </button>
        </div>
    </div>

    <!-- Bank Accounts Grid -->
    <div class="row g-4">
        @forelse($bankAccounts as $bank)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                @if(!$bank->is_active)
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge bg-danger rounded-pill">Inactive</span>
                    </div>
                @endif
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-bank2 fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0 fw-bold">{{ $bank->bank_name }}</h5>
                            <p class="text-muted small mb-0">{{ $bank->branch ?? 'Main Branch' }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <p class="mb-1 text-muted small">Account Name</p>
                        <h6 class="mb-2">{{ $bank->account_name }}</h6>
                        
                        <p class="mb-1 text-muted small">Account Number</p>
                        <h6 class="mb-2 font-monospace">{{ $bank->account_number }}</h6>
                        
                        @if($bank->ifsc_code)
                            <p class="mb-1 text-muted small">IFSC / Swift Code</p>
                            <h6 class="mb-0 font-monospace">{{ $bank->ifsc_code }}</h6>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary border-opacity-10">
                        <div class="small text-muted">
                            Linked Ledger: <span class="fw-semibold text-dark">{{ $bank->account->name ?? 'None' }}</span>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-light rounded-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#editBankModal{{ $bank->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('accounting.banks.destroy', $bank) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" onclick="return confirm('Are you sure you want to delete this bank account?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Bank Modal -->
        <div class="modal fade" id="editBankModal{{ $bank->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <form action="{{ route('accounting.banks.update', $bank) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">Edit Bank Account</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Ledger Account (Assets)</label>
                                <select name="account_id" class="form-select form-select-lg rounded-3" required>
                                    <option value="">Select Ledger Account...</option>
                                    @foreach($ledgerAccounts as $acc)
                                        <option value="{{ $acc->id }}" {{ $bank->account_id == $acc->id ? 'selected' : '' }}>{{ $acc->code }} - {{ $acc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-semibold">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control form-control-lg rounded-3" value="{{ $bank->bank_name }}" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">Account Name</label>
                                    <input type="text" name="account_name" class="form-control form-control-lg rounded-3" value="{{ $bank->account_name }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">Account Number</label>
                                    <input type="text" name="account_number" class="form-control form-control-lg rounded-3" value="{{ $bank->account_number }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">Branch (Optional)</label>
                                    <input type="text" name="branch" class="form-control form-control-lg rounded-3" value="{{ $bank->branch }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-semibold">IFSC / Swift (Optional)</label>
                                    <input type="text" name="ifsc_code" class="form-control form-control-lg rounded-3" value="{{ $bank->ifsc_code }}">
                                </div>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="activeSwitch{{$bank->id}}" {{ $bank->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="activeSwitch{{$bank->id}}">Active Account</label>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Update Account</button>
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
                        <i class="bi bi-bank2 fs-1 text-muted"></i>
                    </div>
                    <h5 class="fw-bold">No Bank Accounts Found</h5>
                    <p class="text-muted mb-4">Add your school's bank accounts to start recording transactions and reconciliations.</p>
                    <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addBankModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Bank Account
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Bank Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('accounting.banks.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Bank Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info border-0 rounded-3 small">
                        <i class="bi bi-info-circle me-1"></i> Bank accounts must be linked to an existing Ledger Account (Asset group).
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Ledger Account (Assets)</label>
                        <select name="account_id" class="form-select form-select-lg rounded-3" required>
                            <option value="">Select Ledger Account...</option>
                            @foreach($ledgerAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control form-control-lg rounded-3" required placeholder="e.g. Everest Bank">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">Account Name</label>
                            <input type="text" name="account_name" class="form-control form-control-lg rounded-3" required placeholder="e.g. GPLC Main Fund">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">Account Number</label>
                            <input type="text" name="account_number" class="form-control form-control-lg rounded-3" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">Branch (Optional)</label>
                            <input type="text" name="branch" class="form-control form-control-lg rounded-3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">IFSC / Swift (Optional)</label>
                            <input type="text" name="ifsc_code" class="form-control form-control-lg rounded-3">
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="activeSwitchNew" checked>
                        <label class="form-check-label" for="activeSwitchNew">Active Account</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
