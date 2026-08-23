<!DOCTYPE html>
<html>
<head>
    <title>Events List</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2 class="text-center">All Events & Activities</h2>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Type</th>
                <th>Category</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Participants</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allEvents as $event)
            <tr>
                <td>{{ $event->name }}</td>
                <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $event->event_type) }}</td>
                <td style="text-transform: capitalize;">{{ $event->category }}</td>
                <td>{{ $event->visit_date->format('M d, Y') }}</td>
                <td>{{ $event->venue ?: '-' }}</td>
                <td>{{ $event->total_participants }} / {{ $event->max_participants ?: 'Unlimited' }}</td>
                <td>{{ $event->status ? 'Active' : 'Inactive' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
