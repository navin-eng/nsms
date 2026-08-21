@extends('backend.pages.layout.master')
@push('b-title', 'Annual Transcripts')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Annual Transcripts</h3>
        <p class="text-muted mb-0">Generate a consolidated transcript for all published exams in an academic year.</p>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<!-- Filter Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('sms.transcripts.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Academic Year</label>
                <select name="academic_year_id" class="form-select" required>
                    <option value="">Select Year</option>
                    @foreach($years as $year)
                        <option value="{{ $year->id }}" {{ $selectedYear && $selectedYear->id == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Class</label>
                <select name="academic_class_id" class="form-select" required>
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $selectedClass && $selectedClass->id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Get Students
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Results View -->
@if($selectedYear && $selectedClass)
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">Students in {{ $selectedClass->name }} ({{ $selectedYear->name }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">Roll No.</th>
                            <th>Student Name</th>
                            <th>Reg. No.</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="ps-4">{{ $student->roll_number ?? '-' }}</td>
                                <td class="fw-bold">
                                    <div class="d-flex align-items-center">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle me-2 object-fit-cover" width="40" height="40">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 border" style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student->registration_number }}</td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('sms.transcripts.print') }}" method="GET" target="_blank" class="d-inline">
                                        <input type="hidden" name="academic_year_id" value="{{ $selectedYear->id }}">
                                        <input type="hidden" name="academic_class_id" value="{{ $selectedClass->id }}">
                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-journal-text"></i> Generate Transcript
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No students enrolled in this class.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
