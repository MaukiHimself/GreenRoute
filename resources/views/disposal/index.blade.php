@extends('layouts.contractor-sidebar')

@section('title', 'Disposal Schedule')

@section('styles')
<style>
    :root {
        --primary-color: #055c5c;
        --secondary-color: #c0392b;
        --white-color: #ffffff;
        --light-bg: #f8f9fa;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        padding: 0;
        margin: 0;
    }

    .container {
        max-width: 1400px;
        padding: 2rem;
    }

    /* Header Section */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .page-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    /* Table Section - No Cards */
    .table-section {
        background: var(--white-color);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-bg);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    .table {
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background: var(--primary-color);
        color: var(--white-color);
        border: none;
        padding: 1.25rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table thead th:first-child {
        border-radius: 12px 0 0 0;
    }

    .table thead th:last-child {
        border-radius: 0 12px 0 0;
    }

    .table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge.bg-primary { background: var(--primary-color) !important; }
    .badge.bg-success { background: #28a745 !important; }
    .badge.bg-warning { background: #ffc107 !important; color: #000 !important; }

    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
        font-weight: 500;
    }

    .btn-outline-primary {
        color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    .btn-outline-primary:hover {
        background: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
    }

    .btn-outline-warning {
        color: var(--secondary-color) !important;
        border-color: var(--secondary-color) !important;
    }

    .btn-outline-warning:hover {
        background: var(--secondary-color) !important;
        border-color: var(--secondary-color) !important;
        color: white !important;
    }

    /* Status Indicators */
    .status-recorded {
        color: var(--primary-color);
        font-weight: 600;
    }

    .status-pending {
        color: #ffc107;
        font-weight: 600;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }

    .pagination .page-link {
        color: var(--primary-color) !important;
        border: 1px solid var(--border-color);
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
    }

    .pagination .page-item.active .page-link {
        background: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .container {
            padding: 1.5rem;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .table-section {
            padding: 1.5rem;
            overflow-x: auto;
        }

        .table {
            min-width: 900px;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn-group .btn {
            margin: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Disposal Schedule</h1>
        </div>
        <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Return
        </a>
    </div>

    <!-- Disposal Table - No Cards -->
    <div class="table-section">
        <div class="section-header">
            <h2 class="section-title">Completed Collections - Record Disposal Data</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Route</th>
                        <th>Collection Date</th>
                        <th>Site Location</th>
                        <th>Volume (m³)</th>
                        <th>Disposal Site</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                    <tr>
                        <td>
                            <span class="badge bg-primary">{{ $schedule->route ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $schedule->pickup_date->format('M d, Y') }}</td>
                        <td>
                            <strong>{{ $schedule->pickup_location }}</strong><br>
                            <small class="text-muted">{{ $schedule->pickup_address }}</small>
                        </td>
                        <td>
                            @if($schedule->total_volume)
                                {{ number_format($schedule->total_volume, 2) }}
                            @else
                                <span class="text-muted">Not recorded</span>
                            @endif
                        </td>
                        <td>
                            @if($schedule->disposal_site)
                                {{ $schedule->disposal_site }}
                                <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $schedule->disposal_type)) }}</small>
                            @else
                                <span class="text-muted">Not recorded</span>
                            @endif
                        </td>
                        <td>
                            @if($schedule->total_volume && $schedule->disposal_site)
                                <span class="badge bg-success">Recorded</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('disposal.show', $schedule) }}" class="btn btn-outline-primary">View</a>
                                <a href="{{ route('disposal.edit', $schedule) }}" class="btn btn-outline-warning">Record Data</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No completed collections found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            {{ $schedules->links() }}
        </div>
    </div>
</div>
@endsection
