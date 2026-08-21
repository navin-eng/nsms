@extends('student.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">My Results</h4>
        <p class="text-muted">View your academic performance and exam marks.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold">Published Exams</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" id="exam-list" role="tablist">
                    @forelse($exams as $index => $exam)
                        <a class="list-group-item list-group-item-action py-3 {{ $index === 0 ? 'active' : '' }}" 
                           id="exam-list-{{ $exam->id }}-list" 
                           data-bs-toggle="list" 
                           href="#exam-list-{{ $exam->id }}" 
                           role="tab" 
                           aria-controls="exam-list-{{ $exam->id }}">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold">{{ $exam->name }}</h6>
                                <span class="badge {{ $index === 0 ? 'bg-white text-primary' : 'bg-primary bg-opacity-10 text-primary' }} rounded-pill">
                                    {{ \Carbon\Carbon::parse($exam->start_date)->format('M Y') }}
                                </span>
                            </div>
                            <small class="{{ $index === 0 ? 'text-white-50' : 'text-muted' }}">
                                Academic Year: {{ $exam->academicYear->name ?? 'N/A' }}
                            </small>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-2 text-black-50"></i>
                            No exam results published yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="tab-content" id="nav-tabContent">
            @forelse($exams as $index => $exam)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                     id="exam-list-{{ $exam->id }}" 
                     role="tabpanel" 
                     aria-labelledby="exam-list-{{ $exam->id }}-list">
                     
                     <div class="card border-0 shadow-sm mb-4">
                         <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                             <h5 class="mb-0 fw-bold text-primary">{{ $exam->name }} Results</h5>
                         </div>
                         <div class="card-body p-0">
                             <div class="table-responsive">
                                 <table class="table table-hover align-middle mb-0 text-center">
                                     <thead class="table-light">
                                         <tr>
                                             <th class="text-start ps-4">Subject</th>
                                             <th>Full Marks</th>
                                             <th>Pass Marks</th>
                                             <th>Obtained Marks</th>
                                             <th>Grade</th>
                                             <th>Status</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @php
                                            $marks = \App\Models\ExamMark::with('subject')
                                                ->where('exam_id', $exam->id)
                                                ->where('student_id', $student->id)
                                                ->get();
                                                
                                            $totalObtained = 0;
                                            $totalFull = 0;
                                            $hasFailed = false;
                                         @endphp
                                         
                                         @foreach($marks as $mark)
                                             @php
                                                // Try to get schedule for full/pass marks
                                                $schedule = \App\Models\ExamSchedule::where('exam_id', $exam->id)
                                                    ->where('subject_id', $mark->subject_id)
                                                    ->first();
                                                    
                                                $fullMarks = $schedule ? $schedule->full_marks : 100;
                                                $passMarks = $schedule ? $schedule->pass_marks : 40;
                                                
                                                $totalObtained += $mark->marks_obtained;
                                                $totalFull += $fullMarks;
                                                
                                                $isPassed = $mark->marks_obtained >= $passMarks;
                                                if (!$isPassed) {
                                                    $hasFailed = true;
                                                }
                                                
                                                // Basic grading
                                                $percentage = ($mark->marks_obtained / $fullMarks) * 100;
                                                $grade = 'F';
                                                if ($percentage >= 90) $grade = 'A+';
                                                elseif ($percentage >= 80) $grade = 'A';
                                                elseif ($percentage >= 70) $grade = 'B+';
                                                elseif ($percentage >= 60) $grade = 'B';
                                                elseif ($percentage >= 50) $grade = 'C+';
                                                elseif ($percentage >= 40) $grade = 'C';
                                             @endphp
                                             <tr>
                                                 <td class="text-start ps-4 fw-medium">{{ $mark->subject->name ?? 'Unknown' }}</td>
                                                 <td class="text-muted">{{ $fullMarks }}</td>
                                                 <td class="text-muted">{{ $passMarks }}</td>
                                                 <td class="fw-bold fs-5">{{ $mark->marks_obtained }}</td>
                                                 <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $grade }}</span></td>
                                                 <td>
                                                     @if($isPassed)
                                                        <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-check me-1"></i>Pass</span>
                                                     @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger"><i class="bi bi-x me-1"></i>Fail</span>
                                                     @endif
                                                 </td>
                                             </tr>
                                         @endforeach
                                     </tbody>
                                     @if($marks->count() > 0)
                                     <tfoot class="table-light fw-bold">
                                         <tr>
                                             <td class="text-start ps-4 text-uppercase">Total</td>
                                             <td>{{ $totalFull }}</td>
                                             <td>-</td>
                                             <td class="fs-4 text-primary">{{ $totalObtained }}</td>
                                             <td colspan="2">
                                                 @if(!$hasFailed)
                                                    <span class="text-success"><i class="bi bi-trophy me-1"></i> PASSED</span>
                                                 @else
                                                    <span class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i> FAILED</span>
                                                 @endif
                                             </td>
                                         </tr>
                                     </tfoot>
                                     @endif
                                 </table>
                             </div>
                             @if($marks->count() == 0)
                                 <div class="p-5 text-center text-muted">
                                     <i class="bi bi-file-earmark-x fs-1 d-block mb-3 opacity-50"></i>
                                     No marks recorded for this exam yet.
                                 </div>
                             @endif
                         </div>
                     </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
                        <div class="bg-light rounded-circle p-4 mb-3">
                            <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                        </div>
                        <h4 class="text-muted fw-bold">No Results Available</h4>
                        <p class="text-muted mb-0">Your exam results will appear here once they are published.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
