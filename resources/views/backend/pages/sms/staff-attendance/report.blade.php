@extends('backend.pages.layout.master')

@section('title', 'Staff Attendance Reports')

@section('backend-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Staff Attendance Reports</h4>
            <p class="text-muted mb-0">Generate, view, and export staff attendance data</p>
        </div>
        <div>
            <a href="{{ route('sms.staff-attendance.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Marking
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('sms.staff-attendance.report') }}" method="GET" class="row g-3 align-items-end" id="reportFilterForm">
                
                <div class="col-md-3">
                    <label class="form-label">Report Type <span class="text-danger">*</span></label>
                    <select name="report_type" id="report_type" class="form-select" required>
                        <option value="monthly_grid" {{ $reportType == 'monthly_grid' ? 'selected' : '' }}>Monthly Grid (Detailed)</option>
                        <option value="range_absent" {{ $reportType == 'range_absent' ? 'selected' : '' }}>Absence by Date Range</option>
                        <option value="total_present" {{ $reportType == 'total_present' ? 'selected' : '' }}>Total Present (Monthly)</option>
                        <option value="highest_present_month" {{ $reportType == 'highest_present_month' ? 'selected' : '' }}>Highest Present (Month)</option>
                        <option value="highest_absent_month" {{ $reportType == 'highest_absent_month' ? 'selected' : '' }}>Highest Absent (Month)</option>
                        <option value="highest_present_year" {{ $reportType == 'highest_present_year' ? 'selected' : '' }}>Highest Present (Yearly)</option>
                        <option value="highest_absent_year" {{ $reportType == 'highest_absent_year' ? 'selected' : '' }}>Highest Absent (Yearly)</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">All Departments (WIP)</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ $selectedDepartmentId == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Month/Year Selection -->
                <div class="col-md-4" id="monthYearSelection">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Year <span class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control" value="{{ $year }}" required>
                        </div>
                        <div class="col-6" id="monthCol">
                            <label class="form-label">Month <span class="text-danger">*</span></label>
                            <select name="month" class="form-select">
                                @if($calendarSystem === 'BS')
                                    @foreach([1=>'Baishakh', 2=>'Jestha', 3=>'Ashadh', 4=>'Shrawan', 5=>'Bhadra', 6=>'Ashwin', 7=>'Kartik', 8=>'Mangsir', 9=>'Poush', 10=>'Magh', 11=>'Falgun', 12=>'Chaitra'] as $num => $name)
                                        <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                @else
                                    @for($m=1; $m<=12; $m++)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endfor
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Date Range Selection -->
                <div class="col-md-4 d-none" id="dateRangeSelection">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            @if($calendarSystem === 'BS')
                                <input type="text" name="start_date" id="start_date" class="form-control nepali-datepicker" value="{{ $startDateDisplay }}" placeholder="YYYY-MM-DD">
                            @else
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDateDisplay }}">
                            @endif
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            @if($calendarSystem === 'BS')
                                <input type="text" name="end_date" id="end_date" class="form-control nepali-datepicker" value="{{ $endDateDisplay }}" placeholder="YYYY-MM-DD">
                            @else
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDateDisplay }}">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Grid -->
    @if($selectedDepartmentId)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h6 class="mb-0 fw-bold">
                    @if($reportType === 'monthly_grid' || $reportType === 'total_present' || str_contains($reportType, 'month'))
                        Monthly Report: {{ $monthName }} {{ $year }}
                    @elseif($reportType === 'range_absent')
                        Absent Report: {{ $startDateDisplay }} to {{ $endDateDisplay }}
                    @else
                        Yearly Attendance Report: {{ $year }}
                    @endif
                </h6>
                <div class="d-flex align-items-center gap-2">
                    @if($reportType === 'monthly_grid')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success me-3"><i class="bi bi-check"></i> P = Present</span>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger me-3"><i class="bi bi-x"></i> A = Absent</span>
                    @endif
                    <a href="{{ request()->fullUrlWithQuery(['print' => 'true']) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                        <i class="bi bi-printer"></i> Print
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="exportTableToExcel('attendanceReportTable')">
                        <i class="bi bi-file-earmark-excel"></i> Export
                    </button>
                </div>
            </div>
            
            <div class="card-body p-0">
                @if(count($reportData) > 0)
                    <div class="table-responsive" style="max-height: 600px;">
                        <table class="table table-bordered table-hover table-sm align-middle mb-0 text-center" id="attendanceReportTable" style="font-size: 0.85rem;">
                            
                            @if($reportType === 'monthly_grid')
                                <!-- Monthly Grid Template -->
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th rowspan="2" class="align-middle bg-light text-start ps-3" style="min-width: 200px; z-index: 2;">Staff Member</th>
                                        <th colspan="{{ $daysInMonth }}" class="bg-light">Days of {{ $monthName }}</th>
                                        <th colspan="4" class="bg-light border-start border-2 border-dark">Summary Totals</th>
                                    </tr>
                                    <tr>
                                        @for($i=1; $i<=$daysInMonth; $i++)
                                            <th class="bg-light {{ date('N', strtotime("$year-$month-$i")) >= 6 ? 'bg-secondary bg-opacity-10 text-danger' : '' }}" style="width: 30px;">
                                                {{ $i }}
                                            </th>
                                        @endfor
                                        <th class="bg-success text-white border-start border-2 border-dark" title="Total Present" style="width: 40px;">P</th>
                                        <th class="bg-danger text-white" title="Total Absent" style="width: 40px;">A</th>
                                        <th class="bg-warning text-dark" title="Total Late" style="width: 40px;">L</th>
                                        <th class="bg-info text-dark" title="Total Half-Day" style="width: 40px;">H</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportData as $row)
                                        <tr>
                                            <td class="text-start ps-3 fw-semibold bg-white position-sticky start-0" style="z-index: 1;">
                                                <div class="d-flex align-items-center">
                                                    {{ $row['staff']->full_name }}
                                                </div>
                                            </td>
                                            
                                            @for($i=1; $i<=$daysInMonth; $i++)
                                                @php
                                                    $dayStr = (string)$i;
                                                    $attendance = $row['attendances']->get($dayStr);
                                                    $isWeekend = date('N', strtotime("$year-$month-$i")) >= 6;
                                                @endphp
                                                
                                                @if($attendance)
                                                    @if($attendance->status == 'Present')
                                                        <td class="text-success fw-bold bg-success bg-opacity-10">P</td>
                                                    @elseif($attendance->status == 'Absent')
                                                        <td class="text-danger fw-bold bg-danger bg-opacity-10">A</td>
                                                    @elseif($attendance->status == 'Late')
                                                        <td class="text-warning fw-bold bg-warning bg-opacity-10">L</td>
                                                    @elseif($attendance->status == 'Half-Day')
                                                        <td class="text-info fw-bold bg-info bg-opacity-10">H</td>
                                                    @endif
                                                @else
                                                    <td class="{{ $isWeekend ? 'bg-secondary bg-opacity-10' : '' }} text-muted">-</td>
                                                @endif
                                            @endfor
                                            
                                            <td class="fw-bold text-success border-start border-2 border-dark">{{ $row['summary']['P'] }}</td>
                                            <td class="fw-bold text-danger">{{ $row['summary']['A'] }}</td>
                                            <td class="fw-bold text-warning">{{ $row['summary']['L'] }}</td>
                                            <td class="fw-bold text-info">{{ $row['summary']['HD'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @elseif($reportType === 'range_absent')
                                <!-- Range Absent Template -->
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start ps-3" style="width: 25%;">Staff Member</th>
                                        <th style="width: 20%;">Total Absences</th>
                                        <th class="text-start" style="width: 55%;">Absent Dates & Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportData as $row)
                                        <tr>
                                            <td class="text-start ps-3 fw-semibold">
                                                {{ $row['staff']->full_name }}
                                                <div class="small text-muted">{{ $row['staff']->designation->name ?? '' }}</div>
                                            </td>
                                            <td class="fw-bold text-danger fs-5">{{ $row['total_absent'] }}</td>
                                            <td class="text-start">
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($row['absences'] as $abs)
                                                        @php
                                                            $bsDate = $calendarSystem === 'BS' ? NepaliDate::create(\Carbon\Carbon::parse($abs->date))->toBS() : \Carbon\Carbon::parse($abs->date)->format('M d, Y');
                                                        @endphp
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                                            {{ $bsDate }}
                                                            @if($abs->remarks)
                                                                <small class="ms-1 fw-normal text-muted">({{ $abs->remarks }})</small>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <!-- Summary Template -->
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start ps-3" style="width: 50%;">Staff Member</th>
                                        <th style="width: 15%;">Total Present</th>
                                        <th style="width: 15%;">Total Absent</th>
                                        <th style="width: 10%;">Late</th>
                                        <th style="width: 10%;">Half-Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportData as $row)
                                        <tr>
                                            <td class="text-start ps-3 fw-semibold">
                                                {{ $row['staff']->full_name }}
                                                <div class="small text-muted">{{ $row['staff']->designation->name ?? '' }}</div>
                                            </td>
                                            <td class="fw-bold text-success">{{ $row['summary']['P'] }}</td>
                                            <td class="fw-bold text-danger">{{ $row['summary']['A'] }}</td>
                                            <td class="fw-bold text-warning">{{ $row['summary']['L'] }}</td>
                                            <td class="fw-bold text-info">{{ $row['summary']['HD'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-50"></i>
                        <h5>No Attendance Records</h5>
                        <p>No records found for the selected criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-funnel fs-1 d-block mb-3 opacity-50"></i>
                <h5>Generate Report</h5>
                <p>Select department and date filters above to view the staff attendance report.</p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle filters based on report type
        const reportTypeSelect = document.getElementById('report_type');
        const monthYearSelection = document.getElementById('monthYearSelection');
        const dateRangeSelection = document.getElementById('dateRangeSelection');
        const monthCol = document.getElementById('monthCol');

        function toggleFilters() {
            const val = reportTypeSelect.value;
            
            if (val === 'range_absent') {
                monthYearSelection.classList.add('d-none');
                dateRangeSelection.classList.remove('d-none');
                
                document.getElementById('start_date').required = true;
                document.getElementById('end_date').required = true;
                monthYearSelection.querySelectorAll('input, select').forEach(el => el.required = false);
            } else if (val.includes('year')) {
                monthYearSelection.classList.remove('d-none');
                dateRangeSelection.classList.add('d-none');
                monthCol.classList.add('d-none');
                
                document.getElementById('start_date').required = false;
                document.getElementById('end_date').required = false;
                monthYearSelection.querySelector('input[name="year"]').required = true;
                monthCol.querySelector('select').required = false;
            } else {
                monthYearSelection.classList.remove('d-none');
                dateRangeSelection.classList.add('d-none');
                monthCol.classList.remove('d-none');
                
                document.getElementById('start_date').required = false;
                document.getElementById('end_date').required = false;
                monthYearSelection.querySelectorAll('input, select').forEach(el => el.required = true);
            }
        }

        reportTypeSelect.addEventListener('change', toggleFilters);
        toggleFilters();

        if (typeof jQuery !== 'undefined') {
            var nepaliDateInputs = document.querySelectorAll('.nepali-datepicker');
            if (nepaliDateInputs.length > 0) {
                nepaliDateInputs.forEach(function(input) {
                    input.nepaliDatePicker({
                        dateFormat: "YYYY-MM-DD",
                        closeOnDateSelect: true
                    });
                });
            }
        }
    });

    // Excel Export Logic using SheetJS
    function exportTableToExcel(tableID, filename = 'staff_attendance_report.xlsx') {
        var table = document.getElementById(tableID);
        var wb = XLSX.utils.table_to_book(table, {sheet: "Attendance Report"});
        XLSX.writeFile(wb, filename);
    }
</script>
@endpush
