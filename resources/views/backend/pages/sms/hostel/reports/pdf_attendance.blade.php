<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hostel Attendance Report</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; font-size: 18px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 10px; }
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: right; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 5px 15px; cursor: pointer;">Print</button>
    </div>

    <div class="header">
        <h2>Hostel Attendance Report</h2>
        @if(isset($request) && ($request->filled('start_date') || $request->filled('end_date')))
            <p>Date Range: {{ $request->start_date ?? 'N/A' }} to {{ $request->end_date ?? 'N/A' }}</p>
        @else
            <p>Generated on: {{ date('M d, Y') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Hostel</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                <td>{{ $attendance->student->registration_number ?? '-' }}</td>
                <td>{{ $attendance->student->first_name }} {{ $attendance->student->last_name }}</td>
                <td>{{ $attendance->hostel->name ?? '-' }}</td>
                <td>
                    <span class="badge {{ $attendance->status === 'Present' ? 'text-success' : ($attendance->status === 'Absent' ? 'text-danger' : 'text-warning') }}">
                        {{ $attendance->status }}
                    </span>
                </td>
                <td>{{ $attendance->remarks ?? '-' }}</td>
            </tr>
            @endforeach
            
            @if($attendances->isEmpty())
            <tr>
                <td colspan="6" style="text-align: center; color: #999;">No attendance records found.</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
