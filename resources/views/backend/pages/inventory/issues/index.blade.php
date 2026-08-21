@extends("backend.pages.layout.master")
@section("title", "Inventory Issues")
@section("backend-content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Issue Asset / Item</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIssueModal">
            <i class="bi bi-box-arrow-up-right me-1"></i> Issue Item
        </button>
    </div>

    @if(session("success"))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session("success") }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session("error"))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session("error") }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Item</th>
                            <th>Issued To</th>
                            <th>Quantity</th>
                            <th>Issue Date</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issues as $issue)
                            <tr>
                                <td class="ps-4 fw-medium text-primary">
                                    {{ $issue->item->name ?? '-' }}
                                </td>
                                <td>
                                    @if($issue->issue_to_type === 'App\\Models\\Staff')
                                        <div class="fw-medium"><i class="bi bi-person-badge"></i> {{ $issue->issueTo->full_name ?? 'Unknown Staff' }}</div>
                                        <div class="small text-muted">Staff</div>
                                    @else
                                        <div class="fw-medium"><i class="bi bi-diagram-3"></i> {{ $issue->issueTo->name ?? 'Unknown Dept' }}</div>
                                        <div class="small text-muted">Department</div>
                                    @endif
                                </td>
                                <td>{{ $issue->quantity }} {{ $issue->item->unit ?? '' }}</td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($issue->issue_date)->format('d M, Y') }}</div>
                                    @if($issue->due_date && $issue->status === 'Issued')
                                        <div class="small text-danger">Due: {{ \Carbon\Carbon::parse($issue->due_date)->format('d M, Y') }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($issue->status === 'Issued')
                                        <span class="badge bg-warning text-dark">Issued</span>
                                    @elseif($issue->status === 'Returned')
                                        <span class="badge bg-success">Returned</span>
                                        <div class="small text-muted mt-1">{{ \Carbon\Carbon::parse($issue->return_date)->format('d M, Y') }}</div>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($issue->status === 'Issued')
                                        <form action="{{ route('admin.inventory.issues.return', $issue->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to mark this item as returned? This will return {{ $issue->quantity }} to the available stock.');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-arrow-return-left"></i> Return
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No issued items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addIssueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inventory.issues.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Issue Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label">Item to Issue <span class="text-danger">*</span></label>
                        <select name="inventory_item_id" class="form-select" required>
                            <option value="">Select Item (In Stock)</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->current_stock }} available)</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Issue To (Type) <span class="text-danger">*</span></label>
                        <select name="issue_to_type" id="issue_to_type" class="form-select" required>
                            <option value="App\Models\Staff">Staff Member</option>
                            <option value="App\Models\Department">Department</option>
                        </select>
                    </div>

                    <div class="mb-3" id="staff_select_group">
                        <label class="form-label">Select Staff <span class="text-danger">*</span></label>
                        <select name="issue_to_id_staff" id="issue_to_id_staff" class="form-select" required>
                            <option value="">Select Staff</option>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }} ({{ $staff->employee_id ?? '#' . $staff->id }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="dept_select_group">
                        <label class="form-label">Select Department <span class="text-danger">*</span></label>
                        <select name="issue_to_id_dept" id="issue_to_id_dept" class="form-select">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Hidden input to hold the actual ID based on selection -->
                    <input type="hidden" name="issue_to_id" id="issue_to_id" value="">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date / Expected Return</label>
                        <input type="date" name="due_date" class="form-control">
                        <div class="form-text">Leave blank for permanent issue (e.g. consumables).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" onclick="setIssueToId()">Issue Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
    document.getElementById('issue_to_type').addEventListener('change', function() {
        if(this.value === 'App\\Models\\Staff') {
            document.getElementById('staff_select_group').classList.remove('d-none');
            document.getElementById('issue_to_id_staff').setAttribute('required', 'required');
            
            document.getElementById('dept_select_group').classList.add('d-none');
            document.getElementById('issue_to_id_dept').removeAttribute('required');
        } else {
            document.getElementById('dept_select_group').classList.remove('d-none');
            document.getElementById('issue_to_id_dept').setAttribute('required', 'required');
            
            document.getElementById('staff_select_group').classList.add('d-none');
            document.getElementById('issue_to_id_staff').removeAttribute('required');
        }
    });

    function setIssueToId() {
        const type = document.getElementById('issue_to_type').value;
        const targetInput = document.getElementById('issue_to_id');
        
        if (type === 'App\\Models\\Staff') {
            targetInput.value = document.getElementById('issue_to_id_staff').value;
        } else {
            targetInput.value = document.getElementById('issue_to_id_dept').value;
        }
    }
</script>
@endsection
