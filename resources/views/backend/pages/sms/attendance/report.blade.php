@extends('backend.pages.layout.master')

@section('title', 'Attendance Reports')

@section('backend-content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Attendance Reports</h4>
            <p class="text-muted mb-0">View monthly and summary attendance reports</p>
        </div>
        <div>
            <a href="{{ route('sms.attendance.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-check-circle"></i> Mark Attendance
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('sms.attendance.report') }}" method="GET" class="row g-3 align-items-end" id="reportFilterForm">
                <div class="col-md-2">
                    <label class="form-label">Report Type <span class="text-danger">*</span></label>
                    <select name="report_type" id="report_type" class="form-select" required>
                        <option value="monthly_grid" {{ $reportType == 'monthly_grid' ? 'selected' : '' }}>Monthly Grid</option>
                        <option value="range_absent" {{ $reportType == 'range_absent' ? 'selected' : '' }}>Range Absent</option>
                        <option value="total_present" {{ $reportType == 'total_present' ? 'selected' : '' }}>Total Present (Month)</option>
                        <option value="highest_present_month" {{ $reportType == 'highest_present_month' ? 'selected' : '' }}>Highest Present (Month)</option>
                        <option value="highest_absent_month" {{ $reportType == 'highest_absent_month' ? 'selected' : '' }}>Highest Absent (Month)</option>
                        <option value="highest_present_year" {{ $reportType == 'highest_present_year' ? 'selected' : '' }}>Highest Present (Year)</option>
                        <option value="highest_absent_year" {{ $reportType == 'highest_absent_year' ? 'selected' : '' }}>Highest Absent (Year)</option>
                    </select>
                </div>
                <div class="col-md-2 month-year-fields">
                    <label class="form-label">Month <span class="text-danger">*</span></label>
                    <select name="month" class="form-select" required>
                        @php
                            $bsMonths = [1=>'Baishakh', 2=>'Jestha', 3=>'Ashadh', 4=>'Shrawan', 5=>'Bhadra', 6=>'Ashwin', 7=>'Kartik', 8=>'Mangsir', 9=>'Poush', 10=>'Magh', 11=>'Falgun', 12=>'Chaitra'];
                        @endphp
                        @for($m=1; $m<=12; ++$m)
                            <option value="{{ sprintf("%02d", $m) }}" {{ $month == sprintf("%02d", $m) ? 'selected' : '' }}>
                                {{ $calendarSystem === 'BS' ? $bsMonths[$m] : date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 month-year-fields">
                    <label class="form-label">Year <span class="text-danger">*</span></label>
                    <select name="year" class="form-select" required>
                        @php
                            $currentSysYear = $calendarSystem === 'BS' ? explode('-', \Pratiksh\Nepalidate\Services\NepaliDate::create(\Carbon\Carbon::now())->toBS())[0] : date('Y');
                        @endphp
                        @for($y=$currentSysYear-2; $y<=$currentSysYear; ++$y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="col-md-2 date-range-fields" style="display: none;">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    @if($calendarSystem === 'BS')
                        <input type="text" name="start_date" class="form-control nepali-datepicker" value="{{ $startDateDisplay ?? '' }}" pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                    @else
                        <input type="date" name="start_date" class="form-control" value="{{ $startDateDisplay ?? '' }}">
                    @endif
                </div>
                <div class="col-md-2 date-range-fields" style="display: none;">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    @if($calendarSystem === 'BS')
                        <input type="text" name="end_date" class="form-control nepali-datepicker" value="{{ $endDateDisplay ?? '' }}" pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD">
                    @else
                        <input type="date" name="end_date" class="form-control" value="{{ $endDateDisplay ?? '' }}">
                    @endif
                </div>

                <div class="col-md-2">
                    <label class="form-label">Class <span class="text-danger">*</span></label>
                    <select name="academic_class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Section <span class="text-danger">*</span></label>
                    <select name="section_id" class="form-select" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" data-class-ids="{{ $section->academicClasses->pluck('id')->join(',') }}" {{ $selectedSectionId == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-file-text"></i> Get Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Grid -->
    @if($selectedClassId && $selectedSectionId)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h6 class="mb-0 fw-bold">
                    @if($reportType === 'monthly_grid' || $reportType === 'total_present' || str_contains($reportType, 'month'))
                        Monthly Report: {{ $monthName }} {{ $year }}
                    @elseif($reportType === 'range_absent')
                        Absent Report: {{ $startDateDisplay }} to {{ $endDateDisplay }}
                    @else
                        Yearly Attendance Report
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
                                        <th rowspan="2" class="align-middle bg-light text-start ps-3" style="min-width: 200px; z-index: 2;">Student Details</th>
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
                                                    <span class="text-muted me-2" style="font-size: 0.75rem;">{{ $row['roll_no'] ?? '-' }}.</span>
                                                    {{ $row['student']->full_name }}
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
                                                        <td class="text-success fw-bold" title="{{ $attendance->remarks }}">P</td>
                                                    @elseif($attendance->status == 'Absent')
                                                        <td class="text-danger fw-bold bg-danger bg-opacity-10" title="{{ $attendance->remarks }}">A</td>
                                                    @elseif($attendance->status == 'Late')
                                                        <td class="text-warning fw-bold" title="{{ $attendance->remarks }}">L</td>
                                                    @elseif($attendance->status == 'Half-Day')
                                                        <td class="text-info fw-bold" title="{{ $attendance->remarks }}">H</td>
                                                    @endif
                                                @else
                                                    <td class="{{ $isWeekend ? 'bg-secondary bg-opacity-10' : 'text-muted' }}">-</td>
                                                @endif
                                            @endfor
    
                                            <!-- Summary -->
                                            <td class="fw-bold text-success border-start border-2 border-dark bg-success bg-opacity-10">{{ $row['summary']['P'] }}</td>
                                            <td class="fw-bold text-danger bg-danger bg-opacity-10">{{ $row['summary']['A'] }}</td>
                                            <td class="fw-bold text-warning bg-warning bg-opacity-10">{{ $row['summary']['L'] }}</td>
                                            <td class="fw-bold text-info bg-info bg-opacity-10">{{ $row['summary']['HD'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @elseif($reportType === 'range_absent')
                                <!-- Range Absent Template -->
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="bg-light text-start ps-3" style="min-width: 200px;">Student Details</th>
                                        <th class="bg-light">Total Days Absent</th>
                                        <th class="bg-light text-start">Absence Dates & Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportData as $row)
                                        <tr>
                                            <td class="text-start ps-3 fw-semibold bg-white">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-muted me-2" style="font-size: 0.75rem;">{{ $row['roll_no'] ?? '-' }}.</span>
                                                    {{ $row['student']->full_name }}
                                                </div>
                                            </td>
                                            <td class="fw-bold text-danger fs-6">{{ $row['total_absent'] }}</td>
                                            <td class="text-start text-muted">
                                                @foreach($row['absences'] as $absence)
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger mb-1 me-1">
                                                        {{ $calendarSystem === 'BS' ? \Pratiksh\Nepalidate\Services\NepaliDate::create(\Carbon\Carbon::parse($absence->date))->toBS() : \Carbon\Carbon::parse($absence->date)->format('M d, Y') }}
                                                        @if($absence->remarks) ({{ $absence->remarks }}) @endif
                                                    </span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <!-- Summary Template (Total Present, Highest Present, Highest Absent) -->
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th class="bg-light text-start ps-3" style="min-width: 200px;">Student Details</th>
                                        <th class="bg-success text-white">Present (Days)</th>
                                        <th class="bg-danger text-white">Absent (Days)</th>
                                        <th class="bg-warning text-dark">Late (Days)</th>
                                        <th class="bg-info text-dark">Half-Day (Days)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportData as $row)
                                        <tr>
                                            <td class="text-start ps-3 fw-semibold bg-white">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-muted me-2" style="font-size: 0.75rem;">{{ $row['roll_no'] ?? '-' }}.</span>
                                                    {{ $row['student']->full_name }}
                                                </div>
                                            </td>
                                            <td class="fw-bold text-success fs-6 bg-success bg-opacity-10">{{ $row['summary']['P'] }}</td>
                                            <td class="fw-bold text-danger fs-6 bg-danger bg-opacity-10">{{ $row['summary']['A'] }}</td>
                                            <td class="fw-bold text-warning fs-6 bg-warning bg-opacity-10">{{ $row['summary']['L'] }}</td>
                                            <td class="fw-bold text-info fs-6 bg-info bg-opacity-10">{{ $row['summary']['HD'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif

                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-file-earmark-x fs-1 d-block mb-3 opacity-50"></i>
                        <h5>No Data Found</h5>
                        <p>No students or attendance records found for the selected criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-funnel fs-1 d-block mb-3 opacity-50"></i>
                <h5>Select Filter Criteria</h5>
                <p>Please select a month, year, class, and section to view the detailed attendance report.</p>
            </div>
        </div>
    @endif
</div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportTypeSelect = document.getElementById('report_type');
        const monthYearFields = document.querySelectorAll('.month-year-fields');
        const dateRangeFields = document.querySelectorAll('.date-range-fields');

        function toggleFields() {
            const reportType = reportTypeSelect.value;
            
            if (reportType === 'range_absent') {
                monthYearFields.forEach(el => el.style.display = 'none');
                monthYearFields.forEach(el => {
                    const select = el.querySelector('select');
                    if(select) select.removeAttribute('required');
                });
                
                dateRangeFields.forEach(el => el.style.display = 'block');
                dateRangeFields.forEach(el => {
                    const input = el.querySelector('input');
                    if(input) input.setAttribute('required', 'required');
                });
            } else if (reportType === 'highest_present_year' || reportType === 'highest_absent_year') {
                monthYearFields.forEach(el => el.style.display = 'none');
                monthYearFields.forEach(el => {
                    const select = el.querySelector('select');
                    if(select) select.removeAttribute('required');
                });
                
                dateRangeFields.forEach(el => el.style.display = 'none');
                dateRangeFields.forEach(el => {
                    const input = el.querySelector('input');
                    if(input) input.removeAttribute('required');
                });
            } else {
                monthYearFields.forEach(el => el.style.display = 'block');
                monthYearFields.forEach(el => {
                    const select = el.querySelector('select');
                    if(select) select.setAttribute('required', 'required');
                });
                
                dateRangeFields.forEach(el => el.style.display = 'none');
                dateRangeFields.forEach(el => {
                    const input = el.querySelector('input');
                    if(input) input.removeAttribute('required');
                });
            }
        }

        reportTypeSelect.addEventListener('change', toggleFields);
        toggleFields(); // Init on load

        // Initialize Nepali Datepicker if present
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
</script>

<!-- Include SheetJS (xlsx) for reliable Excel export -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script>
    // Excel Export Logic using SheetJS
    function exportTableToExcel(tableID, filename = 'attendance_report.xlsx') {
        var table = document.getElementById(tableID);
        // Convert HTML table to Excel workbook
        var wb = XLSX.utils.table_to_book(table, {sheet: "Attendance Report"});
        // Trigger file download
        XLSX.writeFile(wb, filename);
    }
</script>
@endpush
