@extends('backend.pages.layout.master')
@push('b-title', 'Hostel Rooms & Beds')

@section('backend-content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 text-dark mb-0"><i class="bi bi-door-open me-2"></i>Rooms & Beds</h2>
            <p class="text-muted mb-0">Manage rooms and bed capacities within hostels.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            <i class="bi bi-plus-lg"></i> Add Room
        </button>
    </div>

    <!-- Rooms Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Hostel</th>
                            <th class="py-3">Room No.</th>
                            <th class="py-3">Type</th>
                            <th class="py-3 text-center">Beds (Capacity)</th>
                            <th class="py-3">Cost / Bed</th>
                            <th class="py-3 text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr>
                            <td class="px-4">
                                <span class="fw-medium text-dark">{{ $room->hostel->name }}</span>
                                <div class="small text-muted">{{ $room->hostel->type }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">{{ $room->room_number }}</span>
                            </td>
                            <td>{{ $room->room_type ?? 'Standard' }}</td>
                            <td class="text-center">
                                @php
                                    $available = $room->beds->where('status', 'Available')->count();
                                    $allocated = $room->beds->where('status', 'Allocated')->count();
                                @endphp
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <span class="fw-bold fs-5">{{ $room->capacity }}</span>
                                    <div class="d-flex flex-column text-start small lh-1">
                                        <span class="text-success"><i class="bi bi-circle-fill" style="font-size: 0.5rem"></i> {{ $available }} Free</span>
                                        <span class="text-danger mt-1"><i class="bi bi-circle-fill" style="font-size: 0.5rem"></i> {{ $allocated }} Used</span>
                                    </div>
                                </div>
                            </td>
                            <td>Rs. {{ number_format($room->cost_per_bed, 2) }}</td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('sms.hostel.rooms.destroy', $room->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this room and all its beds?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-0 bg-light">
                                        <h5 class="modal-title">Edit Room {{ $room->room_number }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('sms.hostel.rooms.update', $room->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Hostel <span class="text-danger">*</span></label>
                                                <select name="hostel_id" class="form-select" required>
                                                    <option value="">-- Select Hostel --</option>
                                                    @foreach($hostels as $hostel)
                                                        <option value="{{ $hostel->id }}" {{ $room->hostel_id == $hostel->id ? 'selected' : '' }}>{{ $hostel->name }} ({{ $hostel->type }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Room Number <span class="text-danger">*</span></label>
                                                    <input type="text" name="room_number" class="form-control" value="{{ $room->room_number }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Room Type</label>
                                                    <input type="text" name="room_type" class="form-control" placeholder="e.g. AC, Non-AC" value="{{ $room->room_type }}">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Bed Capacity <span class="text-danger">*</span></label>
                                                    <input type="number" name="capacity" class="form-control" min="1" value="{{ $room->capacity }}" required>
                                                    <small class="text-muted d-block mt-1"><i class="bi bi-info-circle"></i> Beds are auto-generated.</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Cost per Bed <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rs.</span>
                                                        <input type="number" name="cost_per_bed" class="form-control" min="0" step="0.01" value="{{ $room->cost_per_bed }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="2">{{ $room->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Room</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-door-open fs-1 text-muted opacity-50 mb-3 d-block"></i>
                                <h5 class="text-muted">No Rooms Found</h5>
                                <p class="text-muted mb-0">Start by adding a new room to a hostel.</p>
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
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title">Add New Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sms.hostel.rooms.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hostel <span class="text-danger">*</span></label>
                        <select name="hostel_id" class="form-select" required>
                            <option value="">-- Select Hostel --</option>
                            @foreach($hostels as $hostel)
                                <option value="{{ $hostel->id }}">{{ $hostel->name }} ({{ $hostel->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Room Number <span class="text-danger">*</span></label>
                            <input type="text" name="room_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Room Type</label>
                            <input type="text" name="room_type" class="form-control" placeholder="e.g. AC, Non-AC">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Bed Capacity <span class="text-danger">*</span></label>
                            <input type="number" name="capacity" class="form-control" min="1" required>
                            <small class="text-muted d-block mt-1"><i class="bi bi-info-circle"></i> Beds are auto-generated.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cost per Bed <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number" name="cost_per_bed" class="form-control" min="0" step="0.01" value="0.00" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Room</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
