@extends("backend.pages.layout.master")
@section("title", "Inventory Maintenance")
@section("backend-content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Maintenance, Lost & Damaged Items</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
            <i class="bi bi-tools me-1"></i> Log Item
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
                            <th class="ps-4">Date</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Description & Cost</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenances as $log)
                            <tr>
                                <td class="ps-4">{{ \Carbon\Carbon::parse($log->date)->format('d M, Y') }}</td>
                                <td class="fw-medium text-primary">{{ $log->item->name ?? '-' }}</td>
                                <td>
                                    @if($log->type === 'Damaged')
                                        <span class="badge bg-danger">Damaged</span>
                                    @elseif($log->type === 'Lost')
                                        <span class="badge bg-dark">Lost</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Maintenance</span>
                                    @endif
                                </td>
                                <td>{{ $log->quantity }}</td>
                                <td>
                                    <div class="small text-muted">{{ $log->description ?? '-' }}</div>
                                    @if($log->cost > 0)
                                        <div class="small fw-bold text-danger mt-1">Cost: {{ number_format($log->cost, 2) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.inventory.maintenance.update', $log->id) }}" method="POST" id="statusForm{{ $log->id }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="cost" value="{{ $log->cost }}">
                                        <select name="status" class="form-select form-select-sm {{ $log->status === 'Pending' ? 'border-warning' : ($log->status === 'Repaired' ? 'border-success' : 'border-danger') }}" onchange="document.getElementById('statusForm{{ $log->id }}').submit()">
                                            <option value="Pending" {{ $log->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Repaired" {{ $log->status === 'Repaired' ? 'selected' : '' }}>Repaired (Return to Stock)</option>
                                            <option value="Discarded" {{ $log->status === 'Discarded' ? 'selected' : '' }}>Discarded / Written Off</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMaintenanceModal{{ $log->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editMaintenanceModal{{ $log->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.inventory.maintenance.update', $log->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Record</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <input type="hidden" name="status" value="{{ $log->status }}">
                                                <div class="mb-3">
                                                    <label class="form-label">Repair Cost</label>
                                                    <input type="number" step="0.01" name="cost" class="form-control" value="{{ $log->cost }}" min="0">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No maintenance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addMaintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.inventory.maintenance.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Log Item (Damage / Loss / Maintenance)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label">Item <span class="text-danger">*</span></label>
                        <select name="inventory_item_id" class="form-select" required>
                            <option value="">Select Item (In Stock)</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->current_stock }} available)</option>
                            @endforeach
                        </select>
                        <div class="form-text">Logging an item will remove it from available stock.</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="Maintenance">Maintenance / Repair</option>
                                <option value="Damaged">Damaged</option>
                                <option value="Lost">Lost</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cost (Optional)</label>
                            <input type="number" step="0.01" name="cost" class="form-control" min="0" value="0">
                        </div>
                    </div>
                    
                    <!-- New Item defaults to Pending Status -->
                    <input type="hidden" name="status" value="Pending">

                    <div class="mb-3">
                        <label class="form-label">Description / Note</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
