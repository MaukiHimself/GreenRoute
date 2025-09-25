<x-guest-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-success">Reports & Analytics</h4>
            <a href="{{ route('reports.export') }}" class="btn btn-success" target="_blank">
                <i class="bi bi-download"></i> Export Report
            </a>
        </div>

        <!-- Client Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-success mb-0">Client Database Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h3 class="text-primary">{{ $data['clientStats']['total_clients'] }}</h3>
                                    <p class="text-muted mb-0">Total Clients</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Clients by Category</h6>
                                @foreach($data['clientStats']['by_category'] as $category)
                                <div class="d-flex justify-content-between">
                                    <span>{{ ucfirst($category->category ?? 'Unknown') }}</span>
                                    <span class="badge bg-info">{{ $category->count }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-5">
                                <h6>Clients by Location</h6>
                                <div style="max-height: 120px; overflow-y: auto;">
                                    @foreach($data['clientStats']['by_location'] as $location)
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>{{ $location->address }}</small>
                                        <span class="badge bg-secondary">{{ $location->count }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-success mb-0">Billing & Revenue Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center mb-3">
                                    <h4 class="text-success">${{ number_format($data['billingStats']['paid_revenue'], 2) }}</h4>
                                    <p class="text-muted mb-0">Total Revenue Paid</p>
                                </div>
                                <div class="text-center">
                                    <h5 class="text-warning">${{ number_format($data['billingStats']['pending_payments'], 2) }}</h5>
                                    <p class="text-muted mb-0">Pending Payments</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h6>Weekly Revenue</h6>
                                <h4 class="text-info">${{ number_format($data['billingStats']['weekly_revenue'], 2) }}</h4>
                                <h6>Monthly Revenue</h6>
                                <h4 class="text-info">${{ number_format($data['billingStats']['monthly_revenue'], 2) }}</h4>
                            </div>
                            <div class="col-md-3">
                                <h6>Yearly Revenue</h6>
                                <h4 class="text-info">${{ number_format($data['billingStats']['yearly_revenue'], 2) }}</h4>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center mb-3">
                                    <h4 class="text-success">{{ $data['billingStats']['paid_customers'] }}</h4>
                                    <p class="text-muted mb-0">Customers Paid</p>
                                </div>
                                <div class="text-center">
                                    <h4 class="text-danger">{{ $data['billingStats']['overdue_customers'] }}</h4>
                                    <p class="text-muted mb-0">Overdue Customers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Collection Statistics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-success mb-0">Collection Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center">
                                <h3 class="text-primary">{{ $data['collectionStats']['total_routes'] }}</h3>
                                <p class="text-muted mb-0">Total Routes</p>
                            </div>
                            <div class="col-6 text-center">
                                <h3 class="text-success">{{ $data['collectionStats']['completed_collections'] }}</h3>
                                <p class="text-muted mb-0">Completed Collections</p>
                            </div>
                        </div>
                        <hr>
                        <h6>Volumes by Route</h6>
                        @foreach($data['collectionStats']['volumes_by_route'] as $route)
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $route->pickup_location }}</span>
                            <span class="badge bg-info">{{ number_format($route->total_volume, 2) }} m³</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-success mb-0">Disposal Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h3 class="text-primary">{{ number_format($data['disposalStats']['total_volume_collected'], 2) }} m³</h3>
                            <p class="text-muted mb-0">Total Volume Collected</p>
                        </div>
                        <div class="text-center mb-3">
                            <h4 class="text-success">{{ number_format($data['disposalStats']['recycled_volume'], 2) }} m³</h4>
                            <p class="text-muted mb-0">Volume Recycled</p>
                        </div>
                        <hr>
                        <h6>Volumes by Disposal Type</h6>
                        @foreach($data['disposalStats']['volumes_by_disposal_type'] as $disposal)
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ ucfirst(str_replace('_', ' ', $disposal->disposal_type)) }}</span>
                            <span class="badge bg-info">{{ number_format($disposal->total_volume, 2) }} m³</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-success mb-0">Revenue Trend</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-success mb-0">Client Distribution</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="clientChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Weekly', 'Monthly', 'Yearly'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [
                        {{ $data['billingStats']['weekly_revenue'] }},
                        {{ $data['billingStats']['monthly_revenue'] }},
                        {{ $data['billingStats']['yearly_revenue'] }}
                    ],
                    backgroundColor: ['#198754', '#20c997', '#0dcaf0']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Client Distribution Chart
        const clientCtx = document.getElementById('clientChart').getContext('2d');
        new Chart(clientCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($data['clientStats']['by_category'] as $category)
                        '{{ ucfirst($category->category ?? "Unknown") }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($data['clientStats']['by_category'] as $category)
                            {{ $category->count }},
                        @endforeach
                    ],
                    backgroundColor: ['#198754', '#20c997', '#0dcaf0', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</x-guest-layout>