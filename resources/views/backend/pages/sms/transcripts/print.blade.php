<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annual Transcript - {{ $student->first_name }} {{ $student->last_name }}</title>
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
            @page { size: A4 landscape; margin: 15mm; }
        }
    </style>
</head>
<body>
    <div class="d-print-none text-center py-3 bg-light border-bottom mb-4">
        <button onclick="window.print()" class="btn btn-primary px-4"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer me-2" viewBox="0 0 16 16"><path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/><path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/></svg> Print Transcript</button>
        <button onclick="window.close()" class="btn btn-outline-secondary ms-2">Close</button>
    </div>

    <div class="container-fluid" style="max-width: 1000px; margin: 0 auto;">
        <div class="marksheet-container">
            <div class="content-wrapper">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1">BLESS ACADEMY</h2>
                    <p class="mb-0 text-muted">Kathmandu, Nepal</p>
                    
                    <h4 class="fw-bold mt-4 mb-0 text-uppercase border border-dark rounded-pill d-inline-block px-4 py-1">
                        ANNUAL ACADEMIC TRANSCRIPT
                    </h4>
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
                                <td class="border-bottom border-dark border-1 rounded-0">{{ $year->name }}</td>
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
                        </table>
                    </div>
                </div>

                <!-- Aggregate Marks Table -->
                <table class="table table-bordered table-sm mb-4 border-dark">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle" style="width: 40px;">S.N.</th>
                            <th rowspan="2" class="text-start align-middle" style="width: 200px;">Subjects</th>
                            @foreach($exams as $exam)
                                <th colspan="3">{{ $exam->title }}</th>
                            @endforeach
                            <th colspan="3" class="bg-light">Yearly Aggregate</th>
                        </tr>
                        <tr>
                            @foreach($exams as $exam)
                                <th>TH</th>
                                <th>PR</th>
                                <th>Total</th>
                            @endforeach
                            <th class="bg-light">Total</th>
                            <th class="bg-light">%</th>
                            <th class="bg-light">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $examTotals = [];
                            foreach($exams as $exam) {
                                $examTotals[$exam->id] = 0;
                            }
                            $grandYearlyTotal = 0;
                            $grandYearlyMax = 0;
                        @endphp
                        
                        @foreach($subjects as $index => $subject)
                            @php
                                $subjectYearlyTotal = 0;
                                $subjectYearlyMax = 0;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $subject->name }}</td>
                                
                                @foreach($exams as $exam)
                                    @php
                                        $examMarks = $marks->get($exam->id, collect())->keyBy('subject_id');
                                        $mark = $examMarks->get($subject->id);
                                        
                                        $schedule = $schedules->where('exam_id', $exam->id)->where('subject_id', $subject->id)->first();
                                        
                                        $thObt = $mark ? ($mark->is_absent ? 0 : $mark->theory_marks) : '-';
                                        $prObt = $mark ? ($mark->is_absent ? 0 : $mark->practical_marks) : '-';
                                        $total = '-';
                                        
                                        if ($mark && !$mark->is_absent) {
                                            $total = $mark->theory_marks + $mark->practical_marks;
                                            $examTotals[$exam->id] += $total;
                                            $subjectYearlyTotal += $total;
                                        }
                                        
                                        if ($schedule) {
                                            $subjectYearlyMax += ($schedule->theory_full_marks + $schedule->practical_full_marks);
                                        }
                                    @endphp
                                    <td class="text-center">{{ $thObt }}</td>
                                    <td class="text-center">{{ $prObt }}</td>
                                    <td class="text-center fw-bold">{{ $total }}</td>
                                @endforeach
                                
                                @php
                                    $grandYearlyTotal += $subjectYearlyTotal;
                                    $grandYearlyMax += $subjectYearlyMax;
                                    
                                    $subPercent = $subjectYearlyMax > 0 ? ($subjectYearlyTotal / $subjectYearlyMax) * 100 : 0;
                                    $subGrade = '-';
                                    foreach ($gradingRules as $rule) {
                                        if ($subPercent >= $rule->min_percent && $subPercent <= $rule->max_percent) {
                                            $subGrade = $rule->grade_name;
                                            break;
                                        }
                                    }
                                @endphp
                                
                                <td class="text-center fw-bold bg-light">{{ $subjectYearlyTotal > 0 ? $subjectYearlyTotal : '-' }}</td>
                                <td class="text-center bg-light">{{ $subjectYearlyMax > 0 ? number_format($subPercent, 1) . '%' : '-' }}</td>
                                <td class="text-center fw-bold bg-light text-primary">{{ $subjectYearlyMax > 0 ? $subGrade : '-' }}</td>
                            </tr>
                        @endforeach
                        
                        <!-- Totals Row -->
                        <tr class="fw-bold border-dark">
                            <td colspan="2" class="text-end pe-3">GRAND TOTAL:</td>
                            @foreach($exams as $exam)
                                <td colspan="3" class="text-center">{{ $examTotals[$exam->id] }}</td>
                            @endforeach
                            <td class="text-center fs-6 bg-light">{{ $grandYearlyTotal }}</td>
                            <td class="bg-light"></td>
                            <td class="bg-light"></td>
                        </tr>
                    </tbody>
                </table>
                
                @php
                    $finalPercent = $grandYearlyMax > 0 ? ($grandYearlyTotal / $grandYearlyMax) * 100 : 0;
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
                                    <th>Aggregate Percentage</th>
                                    <th>Final Grade</th>
                                    <th>Final GPA</th>
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
                            <small class="fw-normal">Remarks: {{ $finalRemarks }}</small>
                        </div>
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
