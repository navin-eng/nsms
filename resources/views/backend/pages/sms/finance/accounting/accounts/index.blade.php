@extends('backend.pages.layout.master')

@section('title', 'Chart of Accounts')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h3 class="mb-0">Chart of Accounts</h3>
            <p class="text-muted">Manage your accounting ledgers.</p>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#accountModal" id="addAccountBtn">
                <i class="bi bi-plus-lg"></i> Add Account
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Account Name</th>
                            <th>Group</th>
                            <th>Type</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $account)
                            <tr>
                                <td class="fw-bold">{{ $account->code ?? '-' }}</td>
                                <td>
                                    {{ $account->name }}
                                    @if($account->is_default)
                                        <span class="badge bg-secondary ms-2" style="font-size: 0.65rem">System</span>
                                    @endif
                                </td>
                                <td>{{ $account->accountGroup->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $account->accountGroup->type ?? '-' }}</span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary edit-btn"
                                            data-id="{{ $account->id }}"
                                            data-name="{{ $account->name }}"
                                            data-code="{{ $account->code }}"
                                            data-group="{{ $account->account_group_id }}"
                                            data-description="{{ $account->description }}"
                                            {{ $account->is_default ? 'disabled title="System accounts cannot be edited"' : '' }}>
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('sms.finance.accounting.accounts.destroy', $account->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this account?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" {{ $account->is_default ? 'disabled title="System accounts cannot be deleted"' : '' }}><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No accounts found. Run the seeder to get started.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="accountModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('sms.finance.accounting.accounts.store') }}" method="POST" id="accountForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Account Group <span class="text-danger">*</span></label>
                        <select name="account_group_id" class="form-select" required>
                            <option value="">Select Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Maintenance Expense">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Code</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. 5200">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('accountModal'));
    const form = document.getElementById('accountForm');
    const modalTitle = document.getElementById('modalTitle');
    const addBtn = document.getElementById('addAccountBtn');

    addBtn.addEventListener('click', function () {
        form.reset();
        form.action = "{{ route('sms.finance.accounting.accounts.store') }}";
        
        let methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
        
        modalTitle.textContent = 'Add Account';
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            form.reset();
            const id = this.dataset.id;
            form.action = `{{ url('admin/sms/accounting/accounts') }}/${id}`;
            
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            
            modalTitle.textContent = 'Edit Account';
            form.name.value = this.dataset.name;
            form.code.value = this.dataset.code;
            form.account_group_id.value = this.dataset.group;
            form.description.value = this.dataset.description;
            
            modal.show();
        });
    });
});
</script>
@endpush
@endsection
