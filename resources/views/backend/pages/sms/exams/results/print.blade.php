<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Sheet - {{ $student->first_name }} {{ $student->last_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #333;
        }
        
        .marksheet-container {
            position: relative;
            background: #fff;
            padding: 40px;
            overflow: hidden;
            border: 1px solid #eee;
            margin-bottom: 20px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
            width: 300px;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }
        
        table th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            vertical-align: middle;
            text-align: center;
        }

        /* Print Specifics */
        @media print {
            body { background: #fff; margin: 0; padding: 0; }
            .marksheet-container { border: none; padding: 0; box-shadow: none; margin: 0; }
            .d-print-none { display: none !important; }
            @page { size: A4 portrait; margin: 15mm; }
        }
    </style>
</head>
<body>
    <div class="d-print-none text-center py-3 bg-light border-bottom mb-4">
        <button onclick="window.print()" class="btn btn-primary px-4"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer me-2" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg> Print Mark Sheet</button>
        <button onclick="window.close()" class="btn btn-outline-secondary ms-2">Close</button>
    </div>

    <div class="container-fluid" style="max-width: 800px; margin: 0 auto;">
        <div class="marksheet-container">
            <!-- <img src="/logo.png" class="watermark" alt="Watermark"> -->
            
            <div class="content-wrapper">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1">BLESS ACADEMY</h2>
                    <p class="mb-0 text-muted">Kathmandu, Nepal</p>
                    <p class="mb-0 text-muted small">Phone: +977-1-1234567 | Email: info@bless.edu.np</p>
                    
                    <h4 class="fw-bold mt-4 mb-0 text-uppercase border border-dark rounded-pill d-inline-block px-4 py-1">
                        {{ $exam->title }}
                    </h4>
                    <p class="mt-2 fw-bold text-decoration-underline text-uppercase">Academic Transcript</p>
                </div>

                <!-- Details -->
                <div class="row mb-4">
                    <div class="col-8">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-start ps-0" style="width: 130px;">Student's Name:</th>
                                <td class="fw-bold border-bottom border-dark border-1 rounded-0">{{ $student->first_name }} {{ $student->last_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-start ps-0">Grade/Class:</th>
                                <td class="border-bottom border-dark border-1 rounded-0">{{ $class->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-start ps-0">Academic Year:</th>
                                <td class="border-bottom border-dark border-1 rounded-0">{{ $exam->academicYear->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th class="text-start">Roll No:</th>
                                <td class="border-bottom border-dark border-1 rounded-0">{{ $student->registration_number }}</td>
                            </tr>
                            <tr>
                                <th class="text-start">DOB:</th>
                                <td class="border-bottom border-dark border-1 rounded-0">{{ $student->dob ? $student->dob->format('Y-m-d') : '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-start text-primary">Class Rank:</th>
                                <td class="border-bottom border-dark border-1 rounded-0 fw-bold text-primary">{{ $studentResult->rank ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Marks Table -->
                <table class="table table-bordered table-sm mb-4 border-dark">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle" style="width: 50px;">S.N.</th>
                            <th rowspan="2" class="text-start align-middle">Subjects</th>
                            <th colspan="2">Full Marks</th>
                            <th colspan="2">Pass Marks</th>
                            <th colspan="3">Marks Obtained</th>
                            <th rowspan="2" class="align-middle" style="width: 80px;">Final Grade</th>
                        </tr>
                        <tr>
                            <th>TH</th>
                            <th>PR</th>
                            <th>TH</th>
                            <th>PR</th>
                            <th>TH</th>
                            <th>PR</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalThMax = 0;
                            $totalPrMax = 0;
                            $totalThObt = 0;
                            $totalPrObt = 0;
                            $grandTotalObt = 0;
                            $grandTotalMax = 0;
                            $isFail = false;
                        @endphp
                        
                        @foreach($schedules as $index => $schedule)
                            @php
                                $mark = $marks->get($schedule->subject_id);
                                
                                $thMax = $schedule->theory_full_marks;
                                $prMax = $schedule->practical_full_marks;
                                $thPass = $schedule->theory_pass_marks;
                                $prPass = $schedule->practical_pass_marks;
                                
                                $thObt = $mark ? ($mark->is_absent ? 0 : $mark->theory_marks) : 0;
                                $prObt = $mark ? ($mark->is_absent ? 0 : $mark->practical_marks) : 0;
                                
                                $isThFail = ($thMax > 0 && $thObt < $thPass);
                                $isPrFail = ($prMax > 0 && $prObt < $prPass);
                                
                                if ($isThFail || $isPrFail) {
                                    $isFail = true;
                                }
                                
                                $subTotalObt = $thObt + $prObt;
                                $subTotalMax = $thMax + $prMax;
                                
                                $totalThMax += $thMax;
                                $totalPrMax += $prMax;
                                $totalThObt += $thObt;
                                $totalPrObt += $prObt;
                                $grandTotalObt += $subTotalObt;
                                $grandTotalMax += $subTotalMax;
                                
                                $subPercent = $subTotalMax > 0 ? ($subTotalObt / $subTotalMax) * 100 : 0;
                                $subGrade = '-';
                                
                                foreach ($gradingRules as $rule) {
                                    if ($subPercent >= $rule->min_percent && $subPercent <= $rule->max_percent) {
                                        $subGrade = $rule->grade_name;
                                        break;
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $schedule->subject->name }}</td>
                                <td class="text-center">{{ $thMax > 0 ? $thMax : '-' }}</td>
                                <td class="text-center">{{ $prMax > 0 ? $prMax : '-' }}</td>
                                <td class="text-center">{{ $thPass > 0 ? $thPass : '-' }}</td>
                                <td class="text-center">{{ $prPass > 0 ? $prPass : '-' }}</td>
                                <td class="text-center {{ $isThFail ? 'text-danger fw-bold' : '' }}">
                                    @if($mark && $mark->is_absent)
                                        Abs
                                    @else
                                        {{ $thMax > 0 ? number_format($thObt, 2) : '-' }}
                                    @endif
                                </td>
                                <td class="text-center {{ $isPrFail ? 'text-danger fw-bold' : '' }}">
                                    @if($mark && $mark->is_absent)
                                        Abs
                                    @else
                                        {{ $prMax > 0 ? number_format($prObt, 2) : '-' }}
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ number_format($subTotalObt, 2) }}</td>
                                <td class="text-center fw-bold">{{ $subGrade }}</td>
                            </tr>
                        @endforeach
                        
                        <!-- Grand Total Row -->
                        <tr class="table-light fw-bold border-dark">
                            <td colspan="2" class="text-end pe-3">GRAND TOTAL:</td>
                            <td class="text-center">{{ $totalThMax }}</td>
                            <td class="text-center">{{ $totalPrMax }}</td>
                            <td colspan="2" class="text-end">Total Obtained:</td>
                            <td class="text-center">{{ number_format($totalThObt, 2) }}</td>
                            <td class="text-center">{{ number_format($totalPrObt, 2) }}</td>
                            <td class="text-center fs-6">{{ number_format($grandTotalObt, 2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                
                @php
                    $finalPercent = $grandTotalMax > 0 ? ($grandTotalObt / $grandTotalMax) * 100 : 0;
                    $finalGpa = 0;
                    $finalGrade = '-';
                    $finalRemarks = '-';
                    
                    foreach ($gradingRules as $rule) {
                        if ($finalPercent >= $rule->min_percent && $finalPercent <= $rule->max_percent) {
                            $finalGrade = $rule->grade_name;
                            $finalGpa = $rule->grade_point;
                            $finalRemarks = $rule->remarks;
                            break;
                        }
                    }
                @endphp

                <!-- Final Calculation & Legend -->
                <div class="row mb-5">
                    <div class="col-6">
                        <table class="table table-sm table-bordered border-dark mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Percentage</th>
                                    <th>Final Grade</th>
                                    <th>Grade Point Average (GPA)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold fs-5">{{ number_format($finalPercent, 2) }}%</td>
                                    <td class="fw-bold fs-5 text-primary">{{ $finalGrade }}</td>
                                    <td class="fw-bold fs-5">{{ number_format($finalGpa, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-2 fw-bold text-center border border-dark p-2 rounded">
                            Result: <span class="{{ $isFail ? 'text-danger' : 'text-success' }} text-uppercase">{{ $isFail ? 'Fail' : 'Pass' }}</span>
                            <br>
                            <small class="fw-normal">Remarks: {{ $finalRemarks }}</small>
                        </div>
                    </div>
                    
                    <div class="col-6">
                        <table class="table table-sm table-bordered border-dark mb-0" style="font-size: 11px;">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="3" class="text-center py-1">Grading System Reference</th>
                                </tr>
                                <tr>
                                    <th class="py-1">Interval (%)</th>
                                    <th class="py-1">Grade</th>
                                    <th class="py-1">GPA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradingRules->take(5) as $rule)
                                    <tr>
                                        <td class="py-1">{{ number_format($rule->min_percent, 0) }} - {{ number_format($rule->max_percent, 0) }}</td>
                                        <td class="py-1 text-center fw-bold">{{ $rule->grade_name }}</td>
                                        <td class="py-1 text-center">{{ number_format($rule->grade_point, 1) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="row mt-5 pt-5 text-center fw-bold">
                    <div class="col-4">
                        <div class="border-top border-dark mx-4 pt-2">Class Teacher</div>
                    </div>
                    <div class="col-4">
                        <div class="border-top border-dark mx-4 pt-2">School Seal</div>
                    </div>
                    <div class="col-4">
                        <div class="border-top border-dark mx-4 pt-2">Principal</div>
                    </div>
                </div>
                
                <div class="text-center mt-4 small text-muted">
                    <p class="mb-0">Date of Issue: {{ date('M d, Y') }}</p>
                    <p class="mb-0">Note: TH=Theory, PR=Practical, Abs=Absent</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        };
    </script>
</body>
</html>
