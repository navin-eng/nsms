@extends('parent.layout.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Attendance Record</h4>
                <p class="text-muted mb-0">Monthly attendance view for {{ $child->first_name }}.</p>
            </div>

            <form action="{{ route('parent.attendance') }}" method="GET" class="d-flex gap-2">
                <select name="month" class="form-select form-select-sm" onchange="this.form.submit()">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center p-3">
                    <h2 class="mb-0 fw-bold">{{ $summary['present'] }}</h2>
                    <div class="small text-white-50 text-uppercase fw-semibold">Days Present</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body text-center p-3">
                    <h2 class="mb-0 fw-bold">{{ $summary['absent'] }}</h2>
                    <div class="small text-white-50 text-uppercase fw-semibold">Days Absent</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body text-center p-3">
                    <h2 class="mb-0 fw-bold">{{ $summary['late'] }}</h2>
                    <div class="small text-white-50 text-uppercase fw-semibold">Days Late</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body text-center p-3">
                    <h2 class="mb-0 fw-bold">
                        {{ $summary['total'] > 0 ? round(($summary['present'] / $summary['total']) * 100) : 0 }}%
                    </h2>
                    <div class="small text-white-50 text-uppercase fw-semibold">Attendance Rate</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">Daily Records for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h5>
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
                                    {{ \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}</td>
                                <td>
                                    @if($record->status == 'Present')
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i
                                                class="bi bi-check-circle me-1"></i> Present</span>
                                    @elseif($record->status == 'Absent')
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i
                                                class="bi bi-x-circle me-1"></i> Absent</span>
                                    @elseif($record->status == 'Late')
                                        <span
                                            class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1"><i
                                                class="bi bi-clock me-1"></i> Late</span>
                                    @else
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">{{ $record->status }}</span>
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