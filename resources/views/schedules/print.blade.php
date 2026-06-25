<!DOCTYPE html>
<html>
<head>
    <title>Collection Schedule - {{ $schedule->pickup_location }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 24px; color: #1e293b; }
        .header {
            text-align: center;
            margin-bottom: 28px;
            padding: 20px;
            border-radius: 12px;
            background: linear-gradient(135deg, #055c5c 0%, #2e7d32 100%);
            color: #ffffff;
        }
        .header .brand { font-size: 13px; letter-spacing: 2px; text-transform: uppercase; opacity: 0.9; margin-bottom: 4px; }
        .header h2 { margin: 0 0 6px; font-size: 24px; font-weight: 700; }
        .header h3 { margin: 0; font-size: 16px; font-weight: 500; opacity: 0.95; }
        .header p { margin: 6px 0 0; font-size: 13px; opacity: 0.9; }
        .info {
            margin-bottom: 20px;
            background: #f1f8f2;
            border-left: 4px solid #2e7d32;
            border-radius: 0 8px 8px 0;
            padding: 14px 18px;
            font-size: 14px;
        }
        .info p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #e2e8f0; padding: 9px 10px; text-align: left; font-size: 13px; }
        thead th { background-color: #055c5c; color: #ffffff; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; font-size: 11px; }
        tbody tr:nth-child(even) td { background-color: #f8fafc; }
        .checkbox { width: 20px; height: 20px; border: 2px solid #055c5c; border-radius: 4px; display: inline-block; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">GreenRoute &bull; Waste Management</div>
        <h2>Collection Schedule</h2>
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