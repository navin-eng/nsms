@extends("backend.pages.layout.master")
@section("title", "Inventory Purchases (Stock In)")
@section("backend-content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Purchases / Stock In</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPurchaseModal">
            <i class="bi bi-plus-circle me-1"></i> Add Stock
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
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Supplier & Store</th>
                            <th>Invoice</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td class="ps-4">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</td>
                                <td class="fw-medium text-primary">
                                    {{ $purchase->item->name ?? '-' }}
                                    <div class="small text-muted">{{ $purchase->item->sku_code ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $purchase->quantity }} {{ $purchase->item->unit ?? '' }}</span><br>
                                    <small class="text-muted">@ {{ number_format($purchase->unit_price, 2) }} / {{ $purchase->item->unit ?? 'unit' }}</small>
                                </td>
                                <td><strong>{{ number_format($purchase->total_price, 2) }}</strong></td>
                                <td>
                                    <div class="small"><i class="bi bi-building"></i> {{ $purchase->supplier->name ?? 'N/A' }}</div>
                                    <div class="small text-muted"><i class="bi bi-shop"></i> {{ $purchase->store->name ?? 'N/A' }}</div>
                                </td>
                                <td>{{ $purchase->invoice_number ?? '-' }}</td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.inventory.purchases.destroy', $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this purchase? This will reduce the current stock of this item by {{ $purchase->quantity }}.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No stock purchases found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addPurchaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.inventory.purchases.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Item <span class="text-danger">*</span></label>
                            <select name="inventory_item_id" class="form-select" required>
                                <option value="">Select Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Purchase Date <span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control calc-total" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control calc-total" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" min="0" required readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="inventory_supplier_id" class="form-select">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Store</label>
                            <select name="inventory_store_id" class="form-select">
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="1"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Purchase</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
    document.querySelectorAll('.calc-total').forEach(input => {
        input.addEventListener('input', () => {
            const qty = parseFloat(document.getElementById('quantity').value) || 0;
            const price = parseFloat(document.getElementById('unit_price').value) || 0;
            document.getElementById('total_price').value = (qty * price).toFixed(2);
        });
    });
</script>
@endsection
