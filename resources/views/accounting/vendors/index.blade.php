@extends('accounting.layout.master')
@push('b-title', 'Vendor Management')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: var(--color-primary);">Vendors</h4>
            <p class="text-muted mb-0">Manage suppliers and service providers</p>
        </div>
        <div>
            <button class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addVendorModal">
                <i class="bi bi-plus-lg me-1"></i> Add Vendor
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">Vendor Name</th>
                            <th class="py-3">Contact Person</th>
                            <th class="py-3">Contact Details</th>
                            <th class="py-3">Tax Number</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="pe-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                        <tr>
                            <td class="ps-4 py-3 fw-semibold">{{ $vendor->name }}</td>
                            <td class="py-3">{{ $vendor->contact_person ?? '-' }}</td>
                            <td class="py-3">
                                @if($vendor->phone)
                                    <div><i class="bi bi-telephone text-muted me-1"></i> <a href="tel:{{ $vendor->phone }}" class="text-decoration-none text-dark">{{ $vendor->phone }}</a></div>
                                @endif
                                @if($vendor->email)
                                    <div><i class="bi bi-envelope text-muted me-1"></i> <a href="mailto:{{ $vendor->email }}" class="text-decoration-none text-dark">{{ $vendor->email }}</a></div>
                                @endif
                                @if(!$vendor->phone && !$vendor->email)
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-3">{{ $vendor->tax_number ?? '-' }}</td>
                            <td class="py-3 text-center">
                                @if($vendor->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Inactive</span>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <button class="btn btn-sm btn-light rounded-circle shadow-sm me-1" data-bs-toggle="modal" data-bs-target="#editVendorModal{{ $vendor->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('accounting.vendors.destroy', $vendor) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editVendorModal{{ $vendor->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <form action="{{ route('accounting.vendors.update', $vendor) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h5 class="modal-title fw-bold">Edit Vendor</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-semibold">Vendor / Company Name *</label>
                                                <input type="text" name="name" class="form-control form-control-lg rounded-3" value="{{ $vendor->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-semibold">Contact Person</label>
                                                <input type="text" name="contact_person" class="form-control form-control-lg rounded-3" value="{{ $vendor->contact_person }}">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label text-muted small fw-semibold">Phone</label>
                                                    <input type="text" name="phone" class="form-control form-control-lg rounded-3" value="{{ $vendor->phone }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label text-muted small fw-semibold">Email</label>
                                                    <input type="email" name="email" class="form-control form-control-lg rounded-3" value="{{ $vendor->email }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-semibold">Address</label>
                                                <textarea name="address" class="form-control form-control-lg rounded-3" rows="2">{{ $vendor->address }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-semibold">Tax Number (VAT/PAN)</label>
                                                <input type="text" name="tax_number" class="form-control form-control-lg rounded-3" value="{{ $vendor->tax_number }}">
                                            </div>
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="activeSwitch{{$vendor->id}}" {{ $vendor->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="activeSwitch{{$vendor->id}}">Active</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Update Vendor</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="bi bi-shop fs-1"></i></div>
                                No vendors found. Add one to start tracking supplier expenses.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addVendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('accounting.vendors.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Vendor / Company Name *</label>
                        <input type="text" name="name" class="form-control form-control-lg rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control form-control-lg rounded-3">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control form-control-lg rounded-3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg rounded-3">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Address</label>
                        <textarea name="address" class="form-control form-control-lg rounded-3" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Tax Number (VAT/PAN)</label>
                        <input type="text" name="tax_number" class="form-control form-control-lg rounded-3">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="activeSwitchNew" checked>
                        <label class="form-check-label" for="activeSwitchNew">Active</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
