@extends('backend.pages.layout.master')
@push('b-title', 'Hostel Bed Allocation Report')

@section('backend-content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 text-dark mb-0"><i class="bi bi-file-earmark-bar-graph me-2"></i>Bed Allocation Report</h2>
            <p class="text-muted mb-0">View and export current bed allocations.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'print']) }}" class="btn btn-secondary" target="_blank">
                <i class="bi bi-printer"></i> Print
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('sms.hostel.reports.allocation') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Hostel</label>
                    <select name="hostel_id" class="form-select">
                        <option value="">All Hostels</option>
                        @foreach($hostels as $hostel)
                            <option value="{{ $hostel->id }}" {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>{{ $hostel->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Room Type</label>
                    <select name="room_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="AC" {{ request('room_type') == 'AC' ? 'selected' : '' }}>AC</option>
                        <option value="Non-AC" {{ request('room_type') == 'Non-AC' ? 'selected' : '' }}>Non-AC</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Vacated" {{ request('status') == 'Vacated' ? 'selected' : '' }}>Vacated</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Hostel</th>
                            <th>Room No.</th>
                            <th>Room Type</th>
                            <th>Bed No.</th>
                            <th>Allocated Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allocations as $allocation)
                        <tr>
                            <td><span class="badge bg-light text-dark">{{ $allocation->student->registration_number ?? '-' }}</span></td>
                            <td class="fw-medium">{{ $allocation->student->first_name }} {{ $allocation->student->last_name }}</td>
                            <td>{{ $allocation->bed->room->hostel->name ?? '-' }}</td>
                            <td>{{ $allocation->bed->room->room_number ?? '-' }}</td>
                            <td>{{ $allocation->bed->room->room_type ?? '-' }}</td>
                            <td>{{ $allocation->bed->bed_number ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($allocation->allocation_date)->format('M d, Y') }}</td>
                            <td>
                                @if($allocation->status === 'Active')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Vacated</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">No bed allocation records found for the selected filters.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
