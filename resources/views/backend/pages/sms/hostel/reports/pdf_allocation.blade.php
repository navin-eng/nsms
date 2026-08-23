<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hostel Bed Allocation Report</title>
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
        .text-secondary { color: #6c757d; }
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
        <h2>Hostel Bed Allocation Report</h2>
        <p>Generated on: {{ date('M d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Hostel</th>
                <th>Room No.</th>
                <th>Room Type</th>
                <th>Bed No.</th>
                <th>Allocated Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allocations as $allocation)
            <tr>
                <td>{{ $allocation->student->registration_number ?? '-' }}</td>
                <td>{{ $allocation->student->first_name }} {{ $allocation->student->last_name }}</td>
                <td>{{ $allocation->bed->room->hostel->name ?? '-' }}</td>
                <td>{{ $allocation->bed->room->room_number ?? '-' }}</td>
                <td>{{ $allocation->bed->room->room_type ?? '-' }}</td>
                <td>{{ $allocation->bed->bed_number ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($allocation->allocation_date)->format('M d, Y') }}</td>
                <td>
                    <span class="badge {{ $allocation->status === 'Active' ? 'text-success' : 'text-secondary' }}">
                        {{ $allocation->status }}
                    </span>
                </td>
            </tr>
            @endforeach
            
            @if($allocations->isEmpty())
            <tr>
                <td colspan="8" style="text-align: center; color: #999;">No bed allocation records found.</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
