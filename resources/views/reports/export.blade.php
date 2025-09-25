<!DOCTYPE html>
<html>
<head>
    <title>AFIA ORBIT - Summary Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 25px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .stat-box { border: 1px solid #ddd; padding: 15px; text-align: center; }
        .stat-value { font-size: 24px; font-weight: bold; color: #198754; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>AFIA ORBIT</h1>
        <h2>Comprehensive Business Report</h2>
        <p>Generated on: {{ now()->format('F d, Y H:i') }}</p>
        <p>Contractor: {{ auth()->user()->name }}</p>
    </div>

    <div class="section">
        <h3>Client Database Summary</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">{{ $data['clientStats']['total_clients'] }}</div>
                <div>Total Clients</div>
            </div>
        </div>
        
        <h4>Clients by Category</h4>
        <table>
            <thead>
                <tr><th>Category</th><th>Count</th></tr>
            </thead>
            <tbody>
                @foreach($data['clientStats']['by_category'] as $category)
                <tr>
                    <td>{{ ucfirst($category->category ?? 'Unknown') }}</td>
                    <td>{{ $category->count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Billing & Revenue Summary</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">${{ number_format($data['billingStats']['paid_revenue'], 2) }}</div>
                <div>Total Revenue Paid</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">${{ number_format($data['billingStats']['pending_payments'], 2) }}</div>
                <div>Pending Payments</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">${{ number_format($data['billingStats']['weekly_revenue'], 2) }}</div>
                <div>Weekly Revenue</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">${{ number_format($data['billingStats']['monthly_revenue'], 2) }}</div>
                <div>Monthly Revenue</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">${{ number_format($data['billingStats']['yearly_revenue'], 2) }}</div>
                <div>Yearly Revenue</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['billingStats']['paid_customers'] }}</div>
                <div>Customers Paid</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['billingStats']['overdue_customers'] }}</div>
                <div>Overdue Customers</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Collection Summary</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">{{ $data['collectionStats']['total_routes'] }}</div>
                <div>Total Routes</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['collectionStats']['completed_collections'] }}</div>
                <div>Completed Collections</div>
            </div>
        </div>
        
        <h4>Volumes by Route</h4>
        <table>
            <thead>
                <tr><th>Route</th><th>Volume (m³)</th></tr>
            </thead>
            <tbody>
                @foreach($data['collectionStats']['volumes_by_route'] as $route)
                <tr>
                    <td>{{ $route->pickup_location }}</td>
                    <td>{{ number_format($route->total_volume, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Disposal Summary</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">{{ number_format($data['disposalStats']['total_volume_collected'], 2) }} m³</div>
                <div>Total Volume Collected</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ number_format($data['disposalStats']['recycled_volume'], 2) }} m³</div>
                <div>Volume Recycled</div>
            </div>
        </div>
        
        <h4>Volumes by Disposal Type</h4>
        <table>
            <thead>
                <tr><th>Disposal Type</th><th>Volume (m³)</th></tr>
            </thead>
            <tbody>
                @foreach($data['disposalStats']['volumes_by_disposal_type'] as $disposal)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $disposal->disposal_type)) }}</td>
                    <td>{{ number_format($disposal->total_volume, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>