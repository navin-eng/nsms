@extends('backend.pages.layout.master')

@section('title', 'Exam Schedules')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-0">Exam Schedules</h3>
            <p class="text-muted">Build examination routines and set passing marks.</p>
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

    <!-- Step 1: Selection -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form action="{{ route('sms.exam-schedules.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold">Select Exam <span class="text-danger">*</span></label>
                    <select name="exam_id" class="form-select" required>
                        <option value="">Choose Exam...</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                {{ $exam->title }} ({{ $exam->academicYear->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
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
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">Load Subjects</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Step 2: Schedule Builder -->
    @if($selectedExam && $selectedClass)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0">Routine Builder: {{ $selectedExam->title }} - {{ $selectedClass->name }}</h5>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('sms.exam-schedules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                    <input type="hidden" name="academic_class_id" value="{{ $selectedClass->id }}">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">Include</th>
                                    <th>Subject</th>
                                    <th>Exam Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th class="text-center bg-light" colspan="2">Theory Marks</th>
                                    <th class="text-center bg-light" colspan="2">Practical Marks</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center" style="width: 100px;">Full</th>
                                    <th class="text-center" style="width: 100px;">Pass</th>
                                    <th class="text-center" style="width: 100px;">Full</th>
                                    <th class="text-center" style="width: 100px;">Pass</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $subject)
                                    @php
                                        $sched = $schedules->get($subject->id);
                                        $included = $sched ? true : false;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input form-check-input-lg" type="checkbox" name="schedules[{{ $subject->id }}][include]" value="1" {{ $included ? 'checked' : '' }}>
                                        </td>
                                        <td class="fw-bold">{{ $subject->name }}</td>
                                        <td>
                                            <input type="date" name="schedules[{{ $subject->id }}][exam_date]" class="form-control form-control-sm" value="{{ $sched->exam_date ?? '' }}">
                                        </td>
                                        <td>
                                            <input type="time" name="schedules[{{ $subject->id }}][start_time]" class="form-control form-control-sm" value="{{ $sched->start_time ?? '' }}">
                                        </td>
                                        <td>
                                            <input type="time" name="schedules[{{ $subject->id }}][end_time]" class="form-control form-control-sm" value="{{ $sched->end_time ?? '' }}">
                                        </td>
                                        
                                        <!-- Theory Marks -->
                                        <td>
                                            <input type="number" step="0.01" min="0" name="schedules[{{ $subject->id }}][theory_full_marks]" class="form-control form-control-sm text-center" value="{{ $sched->theory_full_marks ?? 100 }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="schedules[{{ $subject->id }}][theory_pass_marks]" class="form-control form-control-sm text-center" value="{{ $sched->theory_pass_marks ?? 40 }}">
                                        </td>
                                        
                                        <!-- Practical Marks -->
                                        <td>
                                            <input type="number" step="0.01" min="0" name="schedules[{{ $subject->id }}][practical_full_marks]" class="form-control form-control-sm text-center" value="{{ $sched->practical_full_marks ?? 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="schedules[{{ $subject->id }}][practical_pass_marks]" class="form-control form-control-sm text-center" value="{{ $sched->practical_pass_marks ?? 0 }}">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No subjects found for this class.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($subjects->isNotEmpty())
                        <div class="card-footer bg-white text-end py-3">
                            <button type="submit" class="btn btn-primary px-5">Save Schedule & Marks</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
