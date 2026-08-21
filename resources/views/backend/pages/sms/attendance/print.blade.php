<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report - Print</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            margin: 0; 
            padding: 20px; 
            color: #333;
        }
        .header { 
            text-align: center; 
            border-bottom: 2px solid #333; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 24px; 
            text-transform: uppercase; 
        }
        .header h3 { 
            margin: 8px 0 0; 
            font-size: 16px; 
            font-weight: normal; 
        }
        .meta-data { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px; 
            font-weight: bold; 
            font-size: 13px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 30px; 
        }
        table, th, td { 
            border: 1px solid #555; 
        }
        th {
            background-color: #f2f2f2;
            padding: 8px 4px;
        }
        td { 
            padding: 6px 4px; 
            text-align: center; 
        }
        .text-start { text-align: left !important; padding-left: 8px !important; }
        .text-success { color: #198754; font-weight: bold; }
        .text-danger { color: #dc3545; font-weight: bold; }
        .text-warning { color: #ffc107; font-weight: bold; }
        .text-info { color: #0dcaf0; font-weight: bold; }
        
        .footer-signatures { 
            display: flex; 
            justify-content: space-between; 
            margin-top: 80px; 
        }
        .signature-box { 
            text-align: center; 
            width: 200px; 
        }
        .signature-line { 
            border-top: 1px solid #333; 
            margin-top: 50px; 
            padding-top: 5px; 
            font-weight: bold;
        }
        .print-btn {
            padding: 10px 20px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        @media print {
            body { padding: 0; }
            @page { 
                size: {{ $reportType === 'monthly_grid' ? 'A4 landscape' : 'A4 portrait' }}; 
                margin: 1cm; 
            }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right;">
        <button class="print-btn" onclick="window.print()">Print Report</button>
    </div>

    @php
        $siteSettings = \App\Models\SiteSetting::current();
        $schoolName = $siteSettings->site_title ?? 'School Name';
        
        $className = $classes->where('id', $selectedClassId)->first()->name ?? '';
        $sectionName = $sections->where('id', $selectedSectionId)->first()->name ?? '';
    @endphp

    <div class="header">
        <h1>{{ $schoolName }}</h1>
        <h3>
            @if($reportType === 'monthly_grid')
                Monthly Attendance Grid
            @elseif($reportType === 'total_present')
                Monthly Total Present Report
            @elseif($reportType === 'highest_present_month' || $reportType === 'highest_present_year')
                Highest Present Report
            @elseif($reportType === 'highest_absent_month' || $reportType === 'highest_absent_year')
                Highest Absent Report
            @elseif($reportType === 'range_absent')
                Absentee Report
            @else
                Attendance Report
            @endif
        </h3>
    </div>

    <div class="meta-data">
        <div>Class: {{ $className }}</div>
        <div>Section: {{ $sectionName }}</div>
        <div>
            Period: 
            @if($reportType === 'monthly_grid' || $reportType === 'total_present' || str_contains($reportType, 'month'))
                {{ $monthName }} {{ $year }}
            @elseif($reportType === 'range_absent')
                {{ $startDateDisplay }} to {{ $endDateDisplay }}
            @else
                Yearly
            @endif
        </div>
    </div>

    @if(count($reportData) > 0)
        <table>
            @if($reportType === 'monthly_grid')
                <!-- Monthly Grid Template -->
                <thead>
                    <tr>
                        <th rowspan="2" class="text-start" style="width: 150px;">Student Details</th>
                        <th colspan="{{ $daysInMonth }}">Days of {{ $monthName }}</th>
                        <th colspan="4">Totals</th>
                    </tr>
                    <tr>
                        @for($i=1; $i<=$daysInMonth; $i++)
                            <th>{{ $i }}</th>
                        @endfor
                        <th>P</th>
                        <th>A</th>
                        <th>L</th>
                        <th>H</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $row)
                        <tr>
                            <td class="text-start">
                                {{ $row['roll_no'] ?? '-' }}. {{ $row['student']->full_name }}
                            </td>
                            
                            @for($i=1; $i<=$daysInMonth; $i++)
                                @php
                                    $dayStr = (string)$i;
                                    $attendance = $row['attendances']->get($dayStr);
                                    $isWeekend = date('N', strtotime("$year-$month-$i")) >= 6;
                                @endphp
                                
                                @if($attendance)
                                    @if($attendance->status == 'Present')
                                        <td class="text-success">P</td>
                                    @elseif($attendance->status == 'Absent')
                                        <td class="text-danger">A</td>
                                    @elseif($attendance->status == 'Late')
                                        <td class="text-warning">L</td>
                                    @elseif($attendance->status == 'Half-Day')
                                        <td class="text-info">H</td>
                                    @endif
                                @else
                                    <td>-</td>
                                @endif
                            @endfor

                            <!-- Summary -->
                            <td class="text-success">{{ $row['summary']['P'] }}</td>
                            <td class="text-danger">{{ $row['summary']['A'] }}</td>
                            <td class="text-warning">{{ $row['summary']['L'] }}</td>
                            <td class="text-info">{{ $row['summary']['HD'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @elseif($reportType === 'range_absent')
                <!-- Range Absent Template -->
                <thead>
                    <tr>
                        <th class="text-start" style="width: 250px;">Student Details</th>
                        <th style="width: 100px;">Total Days Absent</th>
                        <th class="text-start">Absence Dates & Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $row)
                        <tr>
                            <td class="text-start">
                                {{ $row['roll_no'] ?? '-' }}. {{ $row['student']->full_name }}
                            </td>
                            <td class="text-danger">{{ $row['total_absent'] }}</td>
                            <td class="text-start">
                                @php
                                    $absencesList = [];
                                    foreach($row['absences'] as $absence) {
                                        $dateStr = $calendarSystem === 'BS' ? \Pratiksh\Nepalidate\Services\NepaliDate::create(\Carbon\Carbon::parse($absence->date))->toBS() : \Carbon\Carbon::parse($absence->date)->format('M d, Y');
                                        if($absence->remarks) {
                                            $dateStr .= " (" . $absence->remarks . ")";
                                        }
                                        $absencesList[] = $dateStr;
                                    }
                                @endphp
                                {{ implode(', ', $absencesList) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @else
                <!-- Summary Template (Total Present, Highest Present, Highest Absent) -->
                <thead>
                    <tr>
                        <th class="text-start">Student Details</th>
                        <th>Present (Days)</th>
                        <th>Absent (Days)</th>
                        <th>Late (Days)</th>
                        <th>Half-Day (Days)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $row)
                        <tr>
                            <td class="text-start">
                                {{ $row['roll_no'] ?? '-' }}. {{ $row['student']->full_name }}
                            </td>
                            <td class="text-success">{{ $row['summary']['P'] }}</td>
                            <td class="text-danger">{{ $row['summary']['A'] }}</td>
                            <td class="text-warning">{{ $row['summary']['L'] }}</td>
                            <td class="text-info">{{ $row['summary']['HD'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
    @else
        <div style="text-align: center; padding: 50px; font-style: italic; color: #777;">
            No attendance data found for the selected criteria.
        </div>
    @endif

    <div class="footer-signatures">
        <div class="signature-box">
            <div class="signature-line">Prepared By</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Class Teacher</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Principal</div>
        </div>
    </div>
</body>
</html>
