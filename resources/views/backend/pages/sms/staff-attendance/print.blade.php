<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Attendance Report - Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            margin: 0; 
            padding: 20px; 
            color: #333;
        }
        .school-header { 
            text-align: center; 
            border-bottom: 2px solid #333; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
        }
        .school-header h1 { 
            margin: 0; 
            font-size: 24px; 
            text-transform: uppercase; 
        }
        .school-header p { 
            margin: 8px 0 0; 
            font-size: 16px; 
            font-weight: normal; 
        }
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
        }
        .report-meta { 
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
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            padding: 8px 4px;
        }
        td { 
            padding: 6px 4px; 
            text-align: center; 
        }
        .text-left { text-align: left !important; padding-left: 8px !important; }
        .text-success { color: #198754; font-weight: bold; }
        .text-danger { color: #dc3545; font-weight: bold; }
        .text-warning { color: #ffc107; font-weight: bold; }
        .text-info { color: #0dcaf0; font-weight: bold; }
        
        .signature-section { 
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
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button class="print-btn" onclick="window.print()">Print Report</button>
        <button class="print-btn" style="background: #6c757d; margin-left: 10px;" onclick="window.close()">Close</button>
    </div>

    <div>
        <!-- Header -->
        <div class="school-header">
            @php $siteSetting = \App\Models\SiteSetting::current(); @endphp
            <h1 class="school-name">{{ $siteSetting->site_title ?? 'School Management System' }}</h1>
            <p class="school-address">{{ $siteSetting->address ?? 'Address Line' }} | Contact: {{ $siteSetting->phone ?? 'Phone' }}</p>
        </div>

        <!-- Title -->
        <div class="report-title">
            @if($reportType === 'monthly_grid' || $reportType === 'total_present' || str_contains($reportType, 'month'))
                STAFF ATTENDANCE REPORT - {{ strtoupper($monthName) }} {{ $year }}
            @elseif($reportType === 'range_absent')
                STAFF ABSENT REPORT ({{ $startDateDisplay }} TO {{ $endDateDisplay }})
            @else
                YEARLY STAFF ATTENDANCE REPORT - {{ $year }}
            @endif
        </div>

        <!-- Meta -->
        <div class="report-meta">
            <div>
                Department: {{ $selectedDepartmentId ? \App\Models\Department::find($selectedDepartmentId)->name : 'All Departments' }}
            </div>
            <div>
                Generated On: {{ date('Y-m-d') }}
            </div>
        </div>

        <!-- Table Data -->
        <table>
            @if($reportType === 'monthly_grid')
                <thead>
                    <tr>
                        <th rowspan="2" class="text-left" style="width: 150px;">Staff Member</th>
                        <th colspan="{{ $daysInMonth }}">Days of {{ $monthName }}</th>
                        <th colspan="4">Summary</th>
                    </tr>
                    <tr>
                        @for($i=1; $i<=$daysInMonth; $i++)
                            <th style="width: 15px;">{{ $i }}</th>
                        @endfor
                        <th style="width: 25px;">P</th>
                        <th style="width: 25px;">A</th>
                        <th style="width: 25px;">L</th>
                        <th style="width: 25px;">H</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $row)
                        <tr>
                            <td class="text-left">{{ $row['staff']->full_name }}</td>
                            @for($i=1; $i<=$daysInMonth; $i++)
                                @php
                                    $attendance = $row['attendances']->get((string)$i);
                                @endphp
                                @if($attendance)
                                    @if($attendance->status == 'Present') <td>P</td>
                                    @elseif($attendance->status == 'Absent') <td>A</td>
                                    @elseif($attendance->status == 'Late') <td>L</td>
                                    @elseif($attendance->status == 'Half-Day') <td>H</td>
                                    @endif
                                @else
                                    <td>-</td>
                                @endif
                            @endfor
                            <td><strong>{{ $row['summary']['P'] }}</strong></td>
                            <td><strong>{{ $row['summary']['A'] }}</strong></td>
                            <td><strong>{{ $row['summary']['L'] }}</strong></td>
                            <td><strong>{{ $row['summary']['HD'] }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            @elseif($reportType === 'range_absent')
                <thead>
                    <tr>
                        <th class="text-left" style="width: 30%;">Staff Member</th>
                        <th style="width: 20%;">Total Absences</th>
                        <th class="text-left" style="width: 50%;">Absent Dates</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $row)
                        <tr>
                            <td class="text-left">{{ $row['staff']->full_name }}<br><small>{{ $row['staff']->designation->name ?? '' }}</small></td>
                            <td><strong>{{ $row['total_absent'] }}</strong></td>
                            <td class="text-left">
                                @foreach($row['absences'] as $abs)
                                    @php
                                        $bsDate = $calendarSystem === 'BS' ? \Pratiksh\Nepalidate\Services\NepaliDate::create(\Carbon\Carbon::parse($abs->date))->toBS() : \Carbon\Carbon::parse($abs->date)->format('M d, Y');
                                    @endphp
                                    {{ $bsDate }}@if(!$loop->last), @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @else
                <thead>
                    <tr>
                        <th class="text-left" style="width: 40%;">Staff Member</th>
                        <th style="width: 15%;">Total Present</th>
                        <th style="width: 15%;">Total Absent</th>
                        <th style="width: 15%;">Late</th>
                        <th style="width: 15%;">Half-Day</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportData as $row)
                        <tr>
                            <td class="text-left">{{ $row['staff']->full_name }}<br><small>{{ $row['staff']->designation->name ?? '' }}</small></td>
                            <td>{{ $row['summary']['P'] }}</td>
                            <td>{{ $row['summary']['A'] }}</td>
                            <td>{{ $row['summary']['L'] }}</td>
                            <td>{{ $row['summary']['HD'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Prepared By</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">HR Manager</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Principal</div>
            </div>
        </div>
    </div>
    

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
