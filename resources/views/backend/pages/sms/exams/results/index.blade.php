@extends('backend.pages.layout.master')

@section('title', 'Exam Results')

@section('backend-content')
    <div class="container-fluid py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0">Exam Results</h3>
                <p class="text-muted">Calculate grades and print mark sheets.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Selection Form -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('sms.exam-results.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Select Exam <span class="text-danger">*</span></label>
                        <select name="exam_id" class="form-select" required>
                            <option value="">Choose Exam...</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Select Class <span class="text-danger">*</span></label>
                        <select name="academic_class_id" class="form-select" required>
                            <option value="">Choose Class...</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('academic_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-secondary w-100">Load Results</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Ledger -->
        @if($selectedExam && $selectedClass)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Results for {{ $selectedClass->name }}</h5>
                    <div>
                        @if($selectedExam->is_published)
                            <form action="{{ route('sms.exam-results.publish', $selectedExam->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="bi bi-eye-slash me-1"></i> Unpublish Results
                                </button>
                            </form>
                            <span class="badge bg-success ms-2">Published</span>
                        @else
                            <form action="{{ route('sms.exam-results.publish', $selectedExam->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-eye me-1"></i> Publish Results
                                </button>
                            </form>
                            <span class="badge bg-secondary ms-2">Unpublished</span>
                        @endif
                        <a href="{{ route('sms.exam-results.print-bulk', ['exam_id' => $selectedExam->id, 'academic_class_id' => $selectedClass->id]) }}" class="btn btn-primary btn-sm ms-2" target="_blank">
                            <i class="bi bi-printer me-1"></i> Print All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">Rank</th>
                                    <th>Student Name</th>
                                    <th>Reg. No.</th>
                                    <th class="text-center">Total Marks</th>
                                    <th class="text-center">Percentage</th>
                                    <th class="text-center">GPA</th>
                                    <th class="text-center">Grade</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $index => $result)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-dark rounded-circle" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">
                                                {{ $result->rank }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">{{ $result->student->first_name }} {{ $result->student->last_name }}
                                        </td>
                                        <td>{{ $result->student->registration_number }}</td>
                                        <td class="text-center">{{ number_format($result->total_marks, 2) }}</td>
                                        <td class="text-center">{{ number_format($result->percentage, 2) }}%</td>
                                        <td class="text-center fw-bold">{{ number_format($result->gpa, 2) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-dark fs-6">{{ $result->grade }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <form action="{{ route('sms.exam-results.print') }}" method="GET" target="_blank"
                                                class="d-inline">
                                                <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                                                <input type="hidden" name="academic_class_id" value="{{ $selectedClass->id }}">
                                                <input type="hidden" name="student_id" value="{{ $result->student->id }}">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-printer"></i> Mark Sheet
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No students enrolled in this class.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection