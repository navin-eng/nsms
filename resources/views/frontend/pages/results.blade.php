@extends('frontend.layout.master')

@section('frontend-content')
<div class="container py-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="fw-bold" style="color: var(--primary-color);">Student Results</h1>
                <p class="text-muted">Enter your Symbol Number and select your exam to view your result.</p>
            </div>

            <!-- Search Form -->
            <div class="card shadow-sm border-0 mb-5" style="border-radius: 12px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                <div class="card-body p-4 p-md-5">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('results.search') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Select Examination</label>
                                <select name="exam_id" class="form-select" required>
                                    <option value="">-- Choose Exam --</option>
                                    @foreach($exams as $ex)
                                        <option value="{{ $ex->id }}" {{ (isset($exam) && $exam->id == $ex->id) ? 'selected' : '' }}>
                                            {{ $ex->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Symbol Number</label>
                                <input type="text" name="symbol_number" class="form-control" placeholder="e.g. 1001" value="{{ request('symbol_number') }}" required>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn text-white px-5 py-2 fw-bold" style="background-color: var(--primary-color); border-radius: 8px;">
                                    View Result
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Result Card -->
            @if(isset($student) && isset($exam))
                <div class="card shadow border-0" id="resultCard" style="border-radius: 12px; border-top: 5px solid var(--primary-color) !important;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4 border-bottom pb-3">
                            <h2 class="fw-bold h3 mb-1" style="color: var(--primary-color);">{{ \App\Models\SiteSetting::current()->site_name }}</h2>
                            <h4 class="text-muted h5 mb-3">{{ $exam->title }}</h4>
                            <p class="mb-0 text-dark"><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
                            <p class="mb-0 text-dark"><strong>Symbol Number:</strong> {{ $student->registration_number }}</p>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead style="background-color: var(--primary-light); color: white;">
                                    <tr>
                                        <th style="background-color: var(--primary-light); color: white;" class="text-start">Subject</th>
                                        <th style="background-color: var(--primary-light); color: white;" class="text-center">Full Marks</th>
                                        <th style="background-color: var(--primary-light); color: white;" class="text-center">Pass Marks</th>
                                        <th style="background-color: var(--primary-light); color: white;" class="text-center">Obtained</th>
                                        <th style="background-color: var(--primary-light); color: white;" class="text-center">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grandTotalMax = 0;
                                        $grandTotalObt = 0;
                                        $isFail = false;
                                    @endphp
                                    
                                    @foreach($schedules as $schedule)
                                        @php
                                            $mark = $marks->get($schedule->subject_id);
                                            
                                            $subMax = $schedule->theory_full_marks + $schedule->practical_full_marks;
                                            $subPassTh = $schedule->theory_pass_marks;
                                            $subPassPr = $schedule->practical_pass_marks;
                                            
                                            $obtTh = $mark ? ($mark->is_absent ? 0 : $mark->theory_marks) : 0;
                                            $obtPr = $mark ? ($mark->is_absent ? 0 : $mark->practical_marks) : 0;
                                            $subObt = $obtTh + $obtPr;
                                            
                                            if (($schedule->theory_full_marks > 0 && $obtTh < $subPassTh) || ($schedule->practical_full_marks > 0 && $obtPr < $subPassPr)) {
                                                $isFail = true;
                                            }
                                            
                                            $grandTotalMax += $subMax;
                                            $grandTotalObt += $subObt;
                                            
                                            $subPercent = $subMax > 0 ? ($subObt / $subMax) * 100 : 0;
                                            $subGrade = '-';
                                            foreach ($gradingRules as $rule) {
                                                if ($subPercent >= $rule->min_percent && $subPercent <= $rule->max_percent) {
                                                    $subGrade = $rule->grade_name;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold text-secondary">{{ $schedule->subject->name }}</td>
                                            <td class="text-center">{{ $subMax }}</td>
                                            <td class="text-center">{{ $subPassTh + $subPassPr }}</td>
                                            <td class="text-center fw-bold">
                                                @if($mark && $mark->is_absent)
                                                    <span class="text-danger">Absent</span>
                                                @else
                                                    {{ number_format($subObt, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-center fw-bold">{{ $subGrade }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    @php
                                        $finalPercent = $grandTotalMax > 0 ? ($grandTotalObt / $grandTotalMax) * 100 : 0;
                                        $finalGrade = '-';
                                        $finalGpa = 0;
                                        foreach ($gradingRules as $rule) {
                                            if ($finalPercent >= $rule->min_percent && $finalPercent <= $rule->max_percent) {
                                                $finalGrade = $rule->grade_name;
                                                $finalGpa = $rule->grade_point;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total Obtained:</td>
                                        <td class="text-center fw-bold fs-5">{{ number_format($grandTotalObt, 2) }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Percentage:</td>
                                        <td class="text-center fw-bold">{{ number_format($finalPercent, 2) }}%</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">GPA / Grade:</td>
                                        <td class="text-center fw-bold text-primary">{{ number_format($finalGpa, 2) }} / {{ $finalGrade }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <h4 class="mb-0">Result: <span class="{{ $isFail ? 'text-danger' : 'text-success' }} text-uppercase fw-bold">{{ $isFail ? 'Fail' : 'Pass' }}</span></h4>
                        </div>
                        
                        <div class="text-center mt-4 d-print-none">
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="fas fa-print"></i> Print Result
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #resultCard, #resultCard * {
            visibility: visible;
        }
        #resultCard {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@endsection
