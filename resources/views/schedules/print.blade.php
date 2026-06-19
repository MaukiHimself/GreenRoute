<!DOCTYPE html>
<html>
<head>
    <title>Collection Schedule - {{ $schedule->pickup_location }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .checkbox { width: 20px; height: 20px; border: 2px solid #000; display: inline-block; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h2>AFIA ORBIT - Collection Schedule</h2>
        <h3>Route: {{ $schedule->pickup_location }}</h3>
        <p>Date: {{ $schedule->pickup_date->format('l, F d, Y') }}</p>
    </div>

    <div class="info">
        <p><strong>Contractor:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Total Clients:</strong> {{ $locationSchedules->count() }}</p>
        @if($schedule->notes)
            <p><strong>Comments:</strong> {{ $schedule->notes }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Address</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Completed</th>
                <th>Price</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($locationSchedules as $locationSchedule)
            <tr>
                <td>{{ $locationSchedule->client->name }}</td>
                <td>{{ $locationSchedule->pickup_address }}</td>
                <td>{{ ucfirst($locationSchedule->client->category) }}</td>
                <td>{{ $locationSchedule->client->phone }}</td>
                <td><span class="checkbox"></span></td>
                <td>{{ $locationSchedule->displayed_price !== null ? 'TZS ' . number_format($locationSchedule->displayed_price, 2) : 'Not set' }}</td>
                <td style="width: 150px; border-bottom: 1px solid #ccc;">&nbsp;</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px;">
        <p><strong>Team Leader Signature:</strong> ___________________________ <strong>Date:</strong> ___________</p>
        <p><strong>Notes:</strong></p>
        <div style="border: 1px solid #ccc; height: 100px; margin-top: 10px;"></div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>