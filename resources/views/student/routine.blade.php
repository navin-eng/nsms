@extends('student.layout.master')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">Class Routine (Timetable)</h4>
        <p class="text-muted">Your weekly class schedule.</p>
    </div>
</div>

@if(!$activeEnrollment)
<div class="alert alert-warning border-warning shadow-sm">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> You are not currently enrolled in any class.
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Routine for {{ $activeEnrollment->academicClass->name ?? '' }} ({{ $activeEnrollment->section->name ?? '' }})</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th style="width: 120px;">Day / Time</th>
                        @foreach($periods as $period)
                            <th>
                                {{ $period->name }}<br>
                                <small class="text-muted fw-normal">{{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}</small>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($days as $day)
                        <tr>
                            <td class="fw-bold bg-light">{{ $day }}</td>
                            @foreach($periods as $period)
                                <td>
                                    @if(isset($timetable[$day][$period->id]))
                                        @php $entry = $timetable[$day][$period->id]; @endphp
                                        <div class="p-2 bg-white border rounded shadow-sm">
                                            <div class="fw-bold text-primary">{{ $entry->subject->name ?? 'N/A' }}</div>
                                            <div class="small text-muted mt-1">
                                                <i class="bi bi-person me-1"></i>{{ $entry->teacher->name ?? 'N/A' }}
                                            </div>
                                            @if($entry->room_no)
                                                <div class="small text-muted">
                                                    <i class="bi bi-door-open me-1"></i>Room: {{ $entry->room_no }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
