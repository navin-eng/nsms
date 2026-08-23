@extends('backend.pages.layout.master')
@push('b-title', 'Hostel Buildings')

@section('backend-content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 text-dark mb-0"><i class="bi bi-building me-2"></i>Hostel Buildings</h2>
            <p class="text-muted mb-0">Manage hostels, types, and wardens.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHostelModal">
            <i class="bi bi-plus-lg"></i> Add Hostel
        </button>
    </div>

    <!-- Hostel List -->
    <div class="row g-4">
        @forelse($hostels as $hostel)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header border-0 bg-{{ $hostel->type === 'Boys' ? 'info' : ($hostel->type === 'Girls' ? 'danger' : 'warning') }} bg-opacity-10 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">{{ $hostel->name }}</h5>
                        <span class="badge bg-{{ $hostel->type === 'Boys' ? 'info' : ($hostel->type === 'Girls' ? 'danger' : 'warning') }} rounded-pill">{{ $hostel->type }} Hostel</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="bi bi-geo-alt me-1"></i> {{ $hostel->address ?? 'No Address' }}
                    </p>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-light rounded-circle p-2 me-3">
                            <i class="bi bi-person-badge fs-4 text-secondary"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Warden</p>
                            <p class="fw-semibold mb-0">{{ optional($hostel->warden)->full_name ?? $hostel->warden_name ?? 'Not Assigned' }}</p>
                        </div>
                    </div>
                    
                    @if($hostel->description)
                        <p class="small text-muted">{{ Str::limit($hostel->description, 60) }}</p>
                    @endif
                </div>
                <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#editHostelModal{{ $hostel->id }}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <form action="{{ route('sms.hostel.hostels.destroy', $hostel->id) }}" method="POST" class="d-inline flex-grow-1" onsubmit="return confirm('Delete this hostel?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editHostelModal{{ $hostel->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 bg-light">
                        <h5 class="modal-title">Edit Hostel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('sms.hostel.hostels.update', $hostel->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Hostel Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $hostel->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="Boys" {{ $hostel->type === 'Boys' ? 'selected' : '' }}>Boys</option>
                                    <option value="Girls" {{ $hostel->type === 'Girls' ? 'selected' : '' }}>Girls</option>
                                    <option value="Mixed" {{ $hostel->type === 'Mixed' ? 'selected' : '' }}>Mixed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $hostel->address }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>Warden</span>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input toggle-warden-type" type="checkbox" role="switch" id="wardenTypeToggle{{ $hostel->id }}" data-target="{{ $hostel->id }}" {{ $hostel->warden_name ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="wardenTypeToggle{{ $hostel->id }}">Enter Custom Name</label>
                                    </div>
                                </label>
                                <div id="wardenSelectContainer{{ $hostel->id }}" class="{{ $hostel->warden_name ? 'd-none' : '' }}">
                                    <select name="warden_id" class="form-select">
                                        <option value="">-- Select Staff --</option>
                                        @foreach($wardens as $warden)
                                            <option value="{{ $warden->id }}" {{ $hostel->warden_id == $warden->id ? 'selected' : '' }}>{{ $warden->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="wardenNameContainer{{ $hostel->id }}" class="{{ $hostel->warden_name ? '' : 'd-none' }}">
                                    <input type="text" name="warden_name" class="form-control" placeholder="Enter Warden Name" value="{{ $hostel->warden_name }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ $hostel->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Hostel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-building fs-1 text-muted opacity-50 mb-3 d-block"></i>
                    <h5 class="text-muted">No Hostels Found</h5>
                    <p class="text-muted mb-0">Start by adding a new hostel building.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addHostelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title">Add New Hostel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sms.hostel.hostels.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hostel Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="Boys">Boys</option>
                            <option value="Girls">Girls</option>
                            <option value="Mixed">Mixed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>Warden</span>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input toggle-warden-type" type="checkbox" role="switch" id="wardenTypeToggleAdd" data-target="Add">
                                <label class="form-check-label small" for="wardenTypeToggleAdd">Enter Custom Name</label>
                            </div>
                        </label>
                        <div id="wardenSelectContainerAdd">
                            <select name="warden_id" class="form-select">
                                <option value="">-- Select Staff --</option>
                                @foreach($wardens as $warden)
                                    <option value="{{ $warden->id }}">{{ $warden->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="wardenNameContainerAdd" class="d-none">
                            <input type="text" name="warden_name" class="form-control" placeholder="Enter Warden Name">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Hostel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-warden-type').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const target = this.getAttribute('data-target');
                const selectContainer = document.getElementById('wardenSelectContainer' + target);
                const nameContainer = document.getElementById('wardenNameContainer' + target);
                
                if (this.checked) {
                    selectContainer.classList.add('d-none');
                    nameContainer.classList.remove('d-none');
                    selectContainer.querySelector('select').value = '';
                } else {
                    selectContainer.classList.remove('d-none');
                    nameContainer.classList.add('d-none');
                    nameContainer.querySelector('input').value = '';
                }
            });
        });
    });
</script>
@endsection
