@extends('backend.pages.layout.master')
@push('b-title', 'Teacher-Subject Assignment')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Teacher-Subject Assignment</h3>
        <p class="text-muted mb-0">Assign subjects to classes and allocate a teaching staff member for each.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">

    {{-- Left: Add New Assignment Form --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="card-title mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>New Assignment</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('sms.assignments.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Academic Year <span class="text-danger">*</span></label>
                        <select name="academic_year_id" class="form-select" required>
                            <option value="">Select Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $activeYear && $activeYear->id == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Class <span class="text-danger">*</span></label>
                        <select name="academic_class_id" id="classSelect" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->name }}{{ $class->stream ? ' — ' . $class->stream->name : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Section <small class="text-muted">(optional)</small></label>
                        <select name="section_id" id="sectionSelect" class="form-select">
                            <option value="">All Sections</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ strtoupper($subject->code) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Assign Teacher <small class="text-muted">(optional)</small></label>
                        <select name="staff_id" class="form-select">
                            <option value="">— Not assigned —</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Weekly Periods <small class="text-muted">(optional)</small></label>
                        <input type="number" name="weekly_periods" class="form-control" min="1" max="50" placeholder="e.g. 5">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check2-circle me-1"></i> Assign Subject
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right: Assignments Table --}}
    <div class="col-lg-8">

        {{-- Filter Bar --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('sms.assignments.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small text-muted mb-1">Academic Year</label>
                        <select name="year_id" class="form-select form-select-sm">
                            <option value="">All Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ request('year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small text-muted mb-1">Class</label>
                        <select name="class_id" class="form-select form-select-sm">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-funnel"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Assignments Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if($assignments->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-x" style="font-size: 3rem;"></i>
                        <p class="mt-2">No assignments yet. Use the form on the left to get started.</p>
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th>Class / Section</th>
                                <th>Year</th>
                                <th>Teacher</th>
                                <th class="text-center">Periods/wk</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($assignments as $a)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $a->subject->name }}</div>
                                    <div class="small text-muted">{{ strtoupper($a->subject->code) }} &middot; {{ ucfirst($a->subject->type) }}</div>
                                </td>
                                <td>
                                    <div>{{ $a->academicClass->name }}</div>
                                    <div class="small text-muted">{{ $a->section ? $a->section->name : 'All Sections' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">{{ $a->academicYear->name }}</span>
                                </td>
                                <td>
                                    @if($a->staff)
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $a->staff->photo ? asset('storage/' . $a->staff->photo) : asset('backend/admin/images/avatar.png') }}"
                                                 class="rounded-circle" style="width:30px;height:30px;object-fit:cover;">
                                            <span>{{ $a->staff->first_name }} {{ $a->staff->last_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">Not assigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ $a->weekly_periods ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    {{-- Quick edit teacher --}}
                                    <button class="btn btn-sm btn-outline-secondary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $a->id }}"
                                            title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    {{-- Delete --}}
                                    <form action="{{ route('sms.assignments.destroy', $a->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Remove this assignment?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $a->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('sms.assignments.update', $a->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Assignment — {{ $a->subject->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Assign Teacher</label>
                                                    <select name="staff_id" class="form-select">
                                                        <option value="">— Not assigned —</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" {{ $a->staff_id == $teacher->id ? 'selected' : '' }}>
                                                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Weekly Periods</label>
                                                    <input type="number" name="weekly_periods" class="form-control"
                                                           value="{{ $a->weekly_periods }}" min="1" max="50">
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
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($assignments->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $assignments->links() }}
                    </div>
                @endif
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Dynamic sections and subjects load when class changes
document.getElementById('classSelect').addEventListener('change', function () {
    const classId = this.value;
    const sectionSelect = document.getElementById('sectionSelect');
    const subjectSelect = document.querySelector('select[name="subject_id"]');
    
    sectionSelect.innerHTML = '<option value="">All Sections</option>';
    subjectSelect.innerHTML = '<option value="">Select Subject</option>';

    if (!classId) return;

    // Load Sections
    fetch(`{{ url('admin/sms/assignments/sections') }}/${classId}`)
        .then(r => r.json())
        .then(sections => {
            sections.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name;
                sectionSelect.appendChild(opt);
            });
        });

    // Load Subjects assigned to the class
    fetch(`{{ url('admin/sms/assignments/subjects') }}/${classId}`)
        .then(r => r.json())
        .then(subjects => {
            subjects.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = `${s.name} (${s.code.toUpperCase()})`;
                subjectSelect.appendChild(opt);
            });
        });
});
</script>
@endpush
