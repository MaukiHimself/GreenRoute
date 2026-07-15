<!DOCTYPE html>
<html>
<head>
    <title>GreenRoute - Summary Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 25px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .stat-box { border: 1px solid #ddd; padding: 15px; text-align: center; }
        .stat-value { font-size: 24px; font-weight: bold; color: #2e7d32; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header no-print">
        <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px;">
            <a href="{{ route('reports.index') }}" style="display: inline-block; padding: 10px 18px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600;">← Back</a>
        </div>
        <h1>GreenRoute</h1>
        <h2>Comprehensive Business Report</h2>
        <p>Generated on: {{ now()->format('F d, Y H:i') }}</p>
        <p>Contractor: {{ auth()->user()->name }}</p>
    </div>

    <div class="section">
        <h3>Business Overview</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">{{ $data['overview']['total_clients'] }}</div>
                <div>Clients</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['overview']['total_routes'] }}</div>
                <div>Routes</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['overview']['total_trucks'] }}</div>
                <div>Trucks</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['overview']['runs_completed'] }}</div>
                <div>Completed Runs</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Field Operations & Waste Collected</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">{{ number_format($data['operationsStats']['total_waste_kg'], 1) }} kg</div>
                <div>Waste Weighed (all trips)</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ number_format($data['operationsStats']['month_waste_kg'], 1) }} kg</div>
                <div>Weighed This Month</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['operationsStats']['trips_weighed'] }}</div>
                <div>Trips Weighed</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['operationsStats']['success_rate'] !== null ? $data['operationsStats']['success_rate'] . '%' : '—' }}</div>
                <div>Collection Success Rate</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['operationsStats']['stops_collected'] }}</div>
                <div>Stops Collected</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['operationsStats']['stops_skipped'] }} / {{ $data['operationsStats']['stops_blocked'] }}</div>
                <div>Skipped / Blocked</div>
            </div>
        </div>

        <h4>Waste by Route (weighbridge)</h4>
        <table>
            <thead>
                <tr><th>Route</th><th>Trips</th><th>Waste (kg)</th></tr>
            </thead>
            <tbody>
                @forelse($data['operationsStats']['waste_by_route'] as $row)
                <tr>
                    <td>{{ $row->route_name ?? 'Unnamed route' }}</td>
                    <td>{{ $row->trips }}</td>
                    <td>{{ number_format($row->total_kg, 1) }}</td>
                </tr>
                @empty
                <tr><td colspan="3">No weighed trips yet.</td></tr>
                @endforelse
            </tbody>
        </table>

        <h4>Top Clients by Waste (estimated share)</h4>
        <table>
            <thead>
                <tr><th>Client</th><th>Pickups</th><th>Waste (kg)</th></tr>
            </thead>
            <tbody>
                @forelse($data['operationsStats']['top_clients_by_waste'] as $row)
                <tr>
                    <td>{{ $row->client_name ?? 'Client' }}</td>
                    <td>{{ $row->pickups }}</td>
                    <td>~{{ number_format($row->total_kg, 1) }}</td>
                </tr>
                @empty
                <tr><td colspan="3">No per-client estimates yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Disposal Records</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">{{ number_format($data['disposalStats']['recorded_weight_kg'], 1) }} kg</div>
                <div>Recorded on Schedules</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ number_format($data['disposalStats']['recycled_kg'], 1) }} kg</div>
                <div>To Sorting Facility</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ number_format($data['disposalStats']['landfill_kg'], 1) }} kg</div>
                <div>To Landfill</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $data['disposalStats']['pending_records'] }}</div>
                <div>Awaiting Record</div>
            </div>
        </div>

        <h4>Waste by Category</h4>
        <table>
            <thead>
                <tr><th>Category</th><th>Waste (kg)</th></tr>
            </thead>
            <tbody>
                @forelse($data['disposalStats']['by_category'] as $row)
                <tr>
                    <td>{{ ucfirst($row->waste_category ?? 'Uncategorised') }}</td>
                    <td>{{ number_format($row->total_kg, 1) }}</td>
                </tr>
                @empty
                <tr><td colspan="2">No disposal records yet.</td></tr>
                @endforelse
            </tbody>
        </table>

        <h4>Waste by Disposal Site</h4>
        <table>
            <thead>
                <tr><th>Site</th><th>Waste (kg)</th></tr>
            </thead>
            <tbody>
                @forelse($data['disposalStats']['by_site'] as $row)
                <tr>
                    <td>{{ $row->disposal_site }}</td>
                    <td>{{ number_format($row->total_kg, 1) }}</td>
                </tr>
                @empty
                <tr><td colspan="2">No disposal records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Monthly Waste Trend (last 6 months)</h3>
        <table>
            <thead>
                <tr><th>Month</th><th>Weighbridge trips (kg)</th><th>Schedule records (kg)</th></tr>
            </thead>
            <tbody>
                @foreach($data['monthlyWaste']['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ number_format($data['monthlyWaste']['trip_kg'][$i], 1) }}</td>
                    <td>{{ number_format($data['monthlyWaste']['schedule_kg'][$i], 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Billing & Revenue Summary</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-value">TZS {{ number_format($data['billingStats']['paid_revenue'], 2) }}</div>
                <div>Total Revenue Paid</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">TZS {{ number_format($data['billingStats']['pending_payments'], 2) }}</div>
                <div>Pending Payments</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">TZS {{ number_format($data['billingStats']['monthly_revenue'], 2) }}</div>
                <div>This Month's Revenue</div>
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
        <h3>Client Database Summary</h3>
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

        <h4>Clients by Route</h4>
        <table>
            <thead>
                <tr><th>Route</th><th>Clients</th></tr>
            </thead>
            <tbody>
                @forelse($data['clientStats']['by_route'] as $route)
                <tr>
                    <td>{{ $route->route }}</td>
                    <td>{{ $route->count }}</td>
                </tr>
                @empty
                <tr><td colspan="2">No clients assigned to routes yet.</td></tr>
                @endforelse
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
