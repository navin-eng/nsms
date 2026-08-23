@extends('backend.pages.layout.master')
@push('b-title', 'Student Directory')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Student Directory</h3>
            <p class="text-muted mb-0">Manage all students enrolled in the institution.</p>
        </div>
        <div>
            <a href="{{ route('sms.students.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i> Admit New Student
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('sms.students.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name or Admission No..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Class</label>
                    <select name="class_id" class="form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Graduated" {{ request('status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="Transferred" {{ request('status') == 'Transferred' ? 'selected' : '' }}>Transferred</option>
                        <option value="Dropped" {{ request('status') == 'Dropped' ? 'selected' : '' }}>Dropped</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
                @if(request()->has('class_id') || request()->has('status') || request()->has('search'))
                <div class="col-md-3">
                    <a href="{{ route('sms.students.index') }}" class="btn btn-light w-100 border">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="studentsTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Admission No</th>
                            <th>Student Name</th>
                            <th>Class (Section)</th>
                            <th>Guardian</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $student->admission_no }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 40px; height: 40px;">
                                                    {{ substr($student->first_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $student->full_name }}</div>
                                            <div class="small text-muted">{{ $student->gender ?? 'Unknown' }} &bull; {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->age . ' yrs' : 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($student->currentEnrollment)
                                        <div class="fw-semibold">{{ $student->currentEnrollment->academicClass->name ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $student->currentEnrollment->section->name ?? 'N/A' }}</div>
                                    @else
                                        <span class="text-muted">No Enrollment</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->guardian)
                                        <div>{{ $student->guardian->father_name ?? $student->guardian->guardian_name ?? 'N/A' }}</div>
                                        <div class="small text-muted"><i class="bi bi-telephone"></i> {{ $student->guardian->father_phone ?? $student->guardian->guardian_phone ?? 'N/A' }}</div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->status == 'Active')
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Active</span>
                                    @elseif($student->status == 'Graduated')
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">Graduated</span>
                                    @elseif($student->status == 'Transferred')
                                        <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">Transferred</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">{{ $student->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('sms.students.show', $student->id) }}" class="btn btn-sm btn-light border" title="View Profile">
                                            <i class="bi bi-eye text-primary"></i>
                                        </a>
                                        <a href="{{ route('sms.students.edit', $student->id) }}" class="btn btn-sm btn-light border" title="Edit Student">
                                            <i class="bi bi-pencil text-warning"></i>
                                        </a>
                                        <form action="{{ route('sms.students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student and all associated records?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-light border rounded-end" title="Delete Student">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-people fs-2 d-block mb-2"></i>
                                    No students found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('b-script')
<script>
    $(document).ready(function() {
        $('#studentsTable').DataTable({
            "pageLength": 25,
            "order": [],
            "columnDefs": [
                { "orderable": false, "targets": 5 }
            ]
        });
    });
</script>
@endpush
