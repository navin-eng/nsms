@extends('backend.pages.layout.master')

@section('title', 'Marks Entry')

@section('backend-content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-0">Marks Entry</h3>
            <p class="text-muted">Enter theory and practical marks for students.</p>
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
            <form action="{{ route('sms.exam-marks.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label class="form-label fw-bold">Select Class <span class="text-danger">*</span></label>
                    <select name="academic_class_id" class="form-select" required onchange="this.form.submit()">
                        <option value="">Choose Class...</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('academic_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($selectedClass && $subjects->isNotEmpty())
                <div class="col-md-3">
                    <label class="form-label fw-bold">Select Subject <span class="text-danger">*</span></label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">Choose Subject...</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-secondary w-100">Load Students</button>
                </div>
                @elseif($selectedClass)
                <div class="col-md-6 text-danger pt-3">
                    <em>No scheduled subjects found for this class in this exam.</em>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Marks Entry Grid -->
    @if($selectedSubject && $schedule)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    Enter Marks: {{ $selectedSubject->name }}
                </h5>
                <span class="badge bg-light text-dark">
                    Theory Full: {{ $schedule->theory_full_marks }} | Practical Full: {{ $schedule->practical_full_marks }}
                </span>
            </div>
            <div class="card-body p-0">
                <form action="{{ route('sms.exam-marks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                    <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 50px;">S.N.</th>
                                    <th>Student Name</th>
                                    <th>Reg. No.</th>
                                    <th class="text-center" style="width: 150px;">Theory Marks<br><small class="text-muted">(Max: {{ $schedule->theory_full_marks }})</small></th>
                                    <th class="text-center" style="width: 150px;">Practical Marks<br><small class="text-muted">(Max: {{ $schedule->practical_full_marks }})</small></th>
                                    <th class="text-center" style="width: 100px;">Absent?</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    @php
                                        $mark = $marks->get($student->id);
                                    @endphp
                                    <tr>
                                        <td class="ps-4">{{ $index + 1 }}</td>
                                        <td class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td>{{ $student->registration_number }}</td>
                                        
                                        <!-- Theory Marks -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="{{ $schedule->theory_full_marks }}" name="marks[{{ $student->id }}][theory_marks]" class="form-control text-center marks-input" value="{{ $mark->theory_marks ?? '' }}" {{ ($mark && $mark->is_absent) ? 'readonly' : '' }}>
                                        </td>
                                        
                                        <!-- Practical Marks -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="{{ $schedule->practical_full_marks }}" name="marks[{{ $student->id }}][practical_marks]" class="form-control text-center marks-input" value="{{ $mark->practical_marks ?? '' }}" {{ ($mark && $mark->is_absent) ? 'readonly' : '' }}>
                                        </td>
                                        
                                        <!-- Absent Checkbox -->
                                        <td class="text-center">
                                            <input type="checkbox" name="marks[{{ $student->id }}][is_absent]" value="1" class="form-check-input form-check-input-lg absent-checkbox" {{ ($mark && $mark->is_absent) ? 'checked' : '' }}>
                                        </td>
                                        
                                        <!-- Remarks -->
                                        <td>
                                            <input type="text" name="marks[{{ $student->id }}][remarks]" class="form-control form-control-sm" value="{{ $mark->remarks ?? '' }}">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No students enrolled in this class.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($students->isNotEmpty())
                        <div class="card-footer bg-white text-end py-3">
                            <button type="submit" class="btn btn-primary px-5">Save Marks</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        
        <script>
            // Toggle readonly on inputs when absent is checked
            document.querySelectorAll('.absent-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    let row = this.closest('tr');
                    let inputs = row.querySelectorAll('.marks-input');
                    inputs.forEach(function(input) {
                        if (checkbox.checked) {
                            input.value = '';
                            input.setAttribute('readonly', 'readonly');
                        } else {
                            input.removeAttribute('readonly');
                        }
                    });
                });
            });
        </script>
    @endif
</div>
@endsection
