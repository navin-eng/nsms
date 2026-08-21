@extends('student.layout.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-center text-md-start">
                <h4 class="mb-1 fw-bold"><i class="bi bi-calendar-check text-primary me-2"></i>My Attendance</h4>
                <p class="text-muted mb-0">Monthly attendance view.</p>
            </div>

            <form action="{{ route('student.attendance') }}" method="GET" class="d-flex gap-2 w-100 w-md-auto">
                <select name="month" class="form-select" onchange="this.form.submit()">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                            @if(isset($calendarSystem) && $calendarSystem == 'BS')
                                {{ [1=>'Baishakh', 2=>'Jestha', 3=>'Ashadh', 4=>'Shrawan', 5=>'Bhadra', 6=>'Ashwin', 7=>'Kartik', 8=>'Mangsir', 9=>'Poush', 10=>'Magh', 11=>'Falgun', 12=>'Chaitra'][$m] }}
                            @else
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            @endif
                        </option>
                    @endfor
                </select>
                <select name="year" class="form-select" onchange="this.form.submit()">
                    @php 
                        $currentSysYear = isset($calendarSystem) && $calendarSystem == 'BS' ? explode('-', \Pratiksh\Nepalidate\Services\NepaliDate::create(\Carbon\Carbon::now())->toBS())[0] : date('Y');
                    @endphp
                    @for($y = $currentSysYear; $y >= $currentSysYear - 2; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100 rounded-4">
                <div class="card-body text-center p-3">
                    <h2 class="mb-0 fw-bold">{{ $stats['present'] }}</h2>
                    <div class="small text-white-50 text-uppercase fw-semibold">Present</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white h-100 rounded-4">
                <div class="card-body text-center p-3">
                    <h2 class="mb-0 fw-bold">{{ $stats['absent'] }}</h2>
                    <div class="small text-white-50 text-uppercase fw-semibold">Absent</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark h-100 rounded-4">
                <div class="card-body text-center p-3">
                    <h2 class="mb-0 fw-bold">{{ $stats['late'] }}</h2>
                    <div class="small text-black-50 text-uppercase fw-semibold">Late</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-info text-dark h-100 rounded-4">
                <div class="card-body text-center p-3">
                    @php $total = $stats['present'] + $stats['absent'] + $stats['late'] + $stats['half_day']; @endphp
                    <h2 class="mb-0 fw-bold">
                        {{ $total > 0 ? round(($stats['present'] / $total) * 100) : 0 }}%
                    </h2>
                    <div class="small text-black-50 text-uppercase fw-semibold">Rate</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">Daily Records for {{ isset($calendarSystem) && $calendarSystem == 'BS' ? $monthName . ' ' . $year : date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $record)
                            <tr>
                                <td class="ps-4 fw-medium">
                                    @if(isset($calendarSystem) && $calendarSystem == 'BS')
                                        {{ \Pratiksh\Nepalidate\Services\NepaliDate::create(\Carbon\Carbon::parse($record->date))->toBS() }}
                                    @else
                                        {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($record->date)->format('l') }}</td>
                                <td>
                                    @if($record->status == 'Present')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle me-1"></i> Present</span>
                                    @elseif($record->status == 'Absent')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-x-circle me-1"></i> Absent</span>
                                    @elseif($record->status == 'Late')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1"><i class="bi bi-clock me-1"></i> Late</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">{{ $record->status }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $record->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-3 text-black-50"></i>
                                    No attendance records found for this month.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
