@extends('backend.pages.layout.master')
@push('b-title', 'Hostel Allocations')

@section('backend-content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-start mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-0">Bed Allocations</h2>
            <p class="text-muted small mb-0">Assign students to hostel beds and manage occupancy.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-sm btn-light border" target="_blank">
                <i class="bi bi-file-earmark-pdf text-danger me-1"></i> PDF
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-sm btn-light border" title="Export Excel">
                <i class="bi bi-file-earmark-excel text-success me-1"></i> Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'print']) }}" class="btn btn-sm btn-light border" target="_blank">
                <i class="bi bi-printer text-secondary me-1"></i> Print
            </a>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#allocateBedModal">
                <i class="bi bi-plus-lg me-1"></i> Allocate Bed
            </button>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="row g-3 mb-4">
        @php
            $activeCount  = $allocations->where('status', 'Active')->count();
            $vacatedCount = $allocations->where('status', 'Vacated')->count();
        @endphp
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Total</div>
                    <div class="fs-4 fw-bold text-dark">{{ $allocations->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Active</div>
                    <div class="fs-4 fw-bold text-success">{{ $activeCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Vacated</div>
                    <div class="fs-4 fw-bold" style="color:#6c757d">{{ $vacatedCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Hostels</div>
                    <div class="fs-4 fw-bold text-dark">{{ $hostels->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Desktop Table (hidden on mobile) --}}
    <div class="card border-0 shadow-sm rounded-3 d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3" style="min-width:200px">Student</th>
                        <th class="py-3">Hostel</th>
                        <th class="py-3">Room</th>
                        <th class="py-3">Bed</th>
                        <th class="py-3" style="min-width:160px">Duration</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allocations as $allocation)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                @if($allocation->student->photo)
                                    <img src="{{ asset('storage/'.$allocation->student->photo) }}"
                                         class="rounded-circle flex-shrink-0"
                                         width="38" height="38" style="object-fit:cover;">
                                @else
                                    <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center fw-semibold text-white"
                                         style="width:38px;height:38px;background:#6366f1;font-size:14px;">
                                        {{ strtoupper(substr($allocation->student->first_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size:14px">
                                        {{ $allocation->student->first_name }} {{ $allocation->student->last_name }}
                                    </div>
                                    <div class="text-muted" style="font-size:12px">
                                        {{ $allocation->student->registration_number ?? $allocation->student->student_id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 text-dark" style="font-size:14px">
                            {{ $allocation->bed->room->hostel->name ?? '—' }}
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded-2 border text-dark" style="font-size:12px;background:#f8f9fa">
                                {{ $allocation->bed->room->room_number ?? '—' }}
                            </span>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded-2 border text-dark" style="font-size:12px;background:#f8f9fa">
                                {{ $allocation->bed->bed_number ?? '—' }}
                            </span>
                        </td>
                        <td class="py-3" style="font-size:12px">
                            <div class="d-flex align-items-center gap-1 text-success fw-medium">
                                <i class="bi bi-arrow-up-right"></i>
                                {{ \Carbon\Carbon::parse($allocation->start_date)->format('d M Y') }}
                            </div>
                            @if($allocation->end_date)
                                <div class="d-flex align-items-center gap-1 text-danger fw-medium mt-1">
                                    <i class="bi bi-arrow-down-right"></i>
                                    {{ \Carbon\Carbon::parse($allocation->end_date)->format('d M Y') }}
                                </div>
                            @else
                                <div class="text-muted mt-1">Ongoing</div>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($allocation->status === 'Active')
                                <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill"
                                      style="font-size:12px;font-weight:600;color:#15803d;background:#dcfce7;border:1px solid #bbf7d0">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#16a34a;display:inline-block"></span>
                                    Active
                                </span>
                            @else
                                <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill"
                                      style="font-size:12px;font-weight:600;color:#6b7280;background:#f3f4f6;border:1px solid #e5e7eb">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#9ca3af;display:inline-block"></span>
                                    Vacated
                                </span>
                            @endif
                        </td>
                        <td class="py-3 text-end px-4">
                            @if($allocation->status === 'Active')
                            <button class="btn btn-sm btn-outline-danger rounded-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#vacateModal{{ $allocation->id }}">
                                <i class="bi bi-box-arrow-right me-1"></i>Vacate
                            </button>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-person-badge fs-1 text-muted d-block mb-2" style="opacity:.25"></i>
                            <div class="fw-semibold text-muted">No Allocations Found</div>
                            <div class="text-muted small">Start by allocating a bed to a student.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile Cards (visible only on mobile) --}}
    <div class="d-md-none">
        @forelse($allocations as $allocation)
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body p-3">
                {{-- Student Row --}}
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($allocation->student->photo)
                        <img src="{{ asset('storage/'.$allocation->student->photo) }}"
                             class="rounded-circle flex-shrink-0"
                             width="44" height="44" style="object-fit:cover;">
                    @else
                        <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center fw-semibold text-white"
                             style="width:44px;height:44px;background:#6366f1;font-size:16px;">
                            {{ strtoupper(substr($allocation->student->first_name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-dark">{{ $allocation->student->first_name }} {{ $allocation->student->last_name }}</div>
                        <div class="text-muted small">{{ $allocation->student->registration_number ?? $allocation->student->student_id }}</div>
                    </div>
                    @if($allocation->status === 'Active')
                        <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill flex-shrink-0"
                              style="font-size:11px;font-weight:600;color:#15803d;background:#dcfce7;border:1px solid #bbf7d0">
                            <span style="width:5px;height:5px;border-radius:50%;background:#16a34a;display:inline-block"></span>
                            Active
                        </span>
                    @else
                        <span class="d-inline-flex align-items-center gap-1 px-2 py-1 rounded-pill flex-shrink-0"
                              style="font-size:11px;font-weight:600;color:#6b7280;background:#f3f4f6;border:1px solid #e5e7eb">
                            <span style="width:5px;height:5px;border-radius:50%;background:#9ca3af;display:inline-block"></span>
                            Vacated
                        </span>
                    @endif
                </div>

                {{-- Details Grid --}}
                <div class="row g-2 mb-3" style="font-size:13px">
                    <div class="col-6">
                        <div class="text-muted small">Hostel</div>
                        <div class="fw-medium text-dark">{{ $allocation->bed->room->hostel->name ?? '—' }}</div>
                    </div>
                    <div class="col-3">
                        <div class="text-muted small">Room</div>
                        <div class="fw-medium text-dark">{{ $allocation->bed->room->room_number ?? '—' }}</div>
                    </div>
                    <div class="col-3">
                        <div class="text-muted small">Bed</div>
                        <div class="fw-medium text-dark">{{ $allocation->bed->bed_number ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">From</div>
                        <div class="fw-medium text-success">{{ \Carbon\Carbon::parse($allocation->start_date)->format('d M Y') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">To</div>
                        <div class="fw-medium {{ $allocation->end_date ? 'text-danger' : 'text-muted' }}">
                            {{ $allocation->end_date ? \Carbon\Carbon::parse($allocation->end_date)->format('d M Y') : 'Ongoing' }}
                        </div>
                    </div>
                </div>

                @if($allocation->status === 'Active')
                <button class="btn btn-outline-danger btn-sm w-100 rounded-2"
                        data-bs-toggle="modal"
                        data-bs-target="#vacateModal{{ $allocation->id }}">
                    <i class="bi bi-box-arrow-right me-1"></i> Vacate
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-person-badge fs-1 d-block mb-2" style="opacity:.25"></i>
            <div class="fw-semibold">No Allocations Found</div>
            <div class="small">Start by allocating a bed to a student.</div>
        </div>
        @endforelse
    </div>

</div>

{{-- ===================== MODALS ===================== --}}

{{-- Allocate Bed Modal --}}
<div class="modal fade" id="allocateBedModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">Allocate Bed</h5>
                    <p class="text-muted small mb-0">Assign a student to an available hostel bed.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sms.hostel.allocations.store') }}" method="POST">
                @csrf
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Student <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-select" required>
                            <option value="">— Search Student —</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Hostel <span class="text-danger">*</span></label>
                            <select id="hostelSelect" class="form-select" required>
                                <option value="">— Select Hostel —</option>
                                @foreach($hostels as $hostel)
                                    <option value="{{ $hostel->id }}">{{ $hostel->name }} ({{ $hostel->type }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Room <span class="text-danger">*</span></label>
                            <select id="roomSelect" class="form-select" disabled required>
                                <option value="">— Select Room —</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Bed <span class="text-danger">*</span></label>
                            <select name="hostel_bed_id" id="bedSelect" class="form-select" disabled required>
                                <option value="">— Select Bed —</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Allocate</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Vacate Modals --}}
@foreach($allocations->where('status', 'Active') as $allocation)
<div class="modal fade" id="vacateModal{{ $allocation->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Vacate Bed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sms.hostel.allocations.vacate', $allocation->id) }}" method="POST">
                @csrf
                <div class="modal-body pt-0">
                    <p class="text-muted small mb-3">
                        Vacating <strong class="text-dark">{{ $allocation->student->first_name }} {{ $allocation->student->last_name }}</strong>
                        from <strong class="text-dark">{{ $allocation->bed->room->hostel->name }} — Room {{ $allocation->bed->room->room_number }}, Bed {{ $allocation->bed->bed_number }}</strong>.
                        The bed will be marked as available.
                    </p>
                    <div>
                        <label class="form-label fw-semibold small">Vacating Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">Confirm Vacate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    const hostelsData = @json($hostels);
    document.addEventListener('DOMContentLoaded', function () {
        const hostelSelect = document.getElementById('hostelSelect');
        const roomSelect   = document.getElementById('roomSelect');
        const bedSelect    = document.getElementById('bedSelect');

        hostelSelect.addEventListener('change', function () {
            roomSelect.innerHTML = '<option value="">— Select Room —</option>';
            bedSelect.innerHTML  = '<option value="">— Select Bed —</option>';
            roomSelect.disabled  = true;
            bedSelect.disabled   = true;

            const hostel = hostelsData.find(h => h.id == this.value);
            if (hostel && hostel.rooms.length > 0) {
                hostel.rooms.forEach(room => {
                    const opt = document.createElement('option');
                    opt.value = room.id;
                    opt.textContent = `Room ${room.room_number} (${room.room_type || 'Standard'}) — Rs. ${room.cost_per_bed}`;
                    roomSelect.appendChild(opt);
                });
                roomSelect.disabled = false;
            }
        });

        roomSelect.addEventListener('change', function () {
            bedSelect.innerHTML = '<option value="">— Select Bed —</option>';
            bedSelect.disabled  = true;

            const hostel = hostelsData.find(h => h.id == hostelSelect.value);
            const room   = hostel?.rooms.find(r => r.id == this.value);
            if (room && room.beds.length > 0) {
                const available = room.beds.filter(b => b.status === 'Available');
                if (available.length > 0) {
                    available.forEach(bed => {
                        const opt = document.createElement('option');
                        opt.value = bed.id;
                        opt.textContent = bed.bed_number;
                        bedSelect.appendChild(opt);
                    });
                    bedSelect.disabled = false;
                } else {
                    bedSelect.innerHTML = '<option value="">— No Available Beds —</option>';
                }
            }
        });
    });
</script>
@endpush
