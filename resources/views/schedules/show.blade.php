<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Schedule Details - {{ $schedule->pickup_location }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #047857;
            --primary-red: #c0392b;
            --white: #ffffff;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            padding: 2rem;
        }
        
        /* Header Section */
        .page-header {
            background: linear-gradient(135deg, var(--primary-teal) 0%, #059669 100%);
            color: var(--white);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .badge.bg-primary {
            background: var(--white) !important;
            color: var(--primary-teal) !important;
        }
        
        .badge.bg-info {
            background: rgba(255, 255, 255, 0.3) !important;
            color: var(--white) !important;
        }
        
        /* Action Buttons */
        .btn-primary-custom {
            background: var(--primary-red);
            color: var(--white);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            background: #4a0303;
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(100, 4, 4, 0.3);
        }
        
        .btn-secondary-custom {
            background: var(--white);
            color: var(--primary-teal);
            border: 2px solid var(--white);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-secondary-custom:hover {
            background: rgba(255, 255, 255, 0.2);
            color: var(--white);
            border-color: var(--white);
        }
        
        /* Content Section */
        .content-section {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .section-title {
            color: var(--primary-teal);
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-teal);
        }
        
        /* Table Styling */
        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table thead th {
            background: var(--primary-teal);
            color: var(--white);
            border: none;
            padding: 1rem;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table thead th:first-child {
            border-radius: 8px 0 0 0;
        }
        
        .table thead th:last-child {
            border-radius: 0 8px 0 0;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .badge.category-badge {
            background: var(--primary-teal) !important;
            color: var(--white) !important;
        }
        
        /* Form Controls */
        .form-select-sm {
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .form-select-sm:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
            outline: none;
        }
        
        .form-check-input {
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid var(--primary-teal);
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-teal);
            border-color: var(--primary-teal);
        }
        
        /* Notes Section */
        .notes-section {
            background: linear-gradient(135deg, #f0f9f9 0%, #e6f4f4 100%);
            border-left: 4px solid var(--primary-teal);
            padding: 1.5rem;
            border-radius: 8px;
        }
        
        .notes-title {
            color: var(--primary-teal);
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .content-section {
                padding: 1.5rem;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1 class="page-title">
                        <i class="bi bi-calendar-check me-2"></i>
                        {{ $schedule->pickup_location }}
                    </h1>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge bg-primary">Route: {{ $schedule->route ?? 'N/A' }}</span>
                        <span class="badge bg-info">{{ $schedule->pickup_date->format('M d, Y') }}</span>
                        <span class="badge bg-info">{{ $schedule->pickup_time }}</span>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn-primary-custom">
                        <i class="bi bi-pencil"></i> Edit Billing
                    </a>
                    <a href="{{ route('schedules.print', $schedule) }}" class="btn-primary-custom" target="_blank">
                        <i class="bi bi-printer"></i> Print
                    </a>
                    <a href="{{ route('schedules.index') }}" class="btn-secondary-custom">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Schedule Details Table -->
        <div class="content-section">
            <h2 class="section-title">
                <i class="bi bi-list-check me-2"></i>Collection Details
            </h2>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Address</th>
                            <th>Category</th>
                            <th>Phone</th>
                            <th>Collection Status</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locationSchedules as $locationSchedule)
                        <tr>
                            <td><strong>{{ $locationSchedule->client->name }}</strong></td>
                            <td>{{ $locationSchedule->pickup_address }}</td>
                            <td>
                                <span class="badge category-badge">
                                    {{ ucfirst($locationSchedule->client->category) }}
                                </span>
                            </td>
                            <td>{{ $locationSchedule->client->phone }}</td>
                            <td>
                                <select class="form-select form-select-sm" onchange="updateStatus({{ $locationSchedule->id }}, this.value)">
                                    <option value="scheduled" {{ $locationSchedule->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="in_progress" {{ $locationSchedule->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $locationSchedule->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $locationSchedule->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" class="form-check-input" 
                                       {{ $locationSchedule->status === 'completed' ? 'checked' : '' }}
                                       onchange="toggleComplete({{ $locationSchedule->id }}, this.checked)">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($schedule->displayed_price !== null || $schedule->billingRate)
        <div class="content-section">
            <h2 class="section-title">
                <i class="bi bi-currency-dollar me-2"></i>Billing Price
            </h2>

            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Official Billing Rate</th>
                            <td>{{ $schedule->billingRateLabel ?? 'Manual contractor price' }}</td>
                        </tr>
                        <tr>
                            <th>Official Fee</th>
                            <td>{{ $schedule->base_collection_fee !== null ? 'TZS ' . number_format($schedule->base_collection_fee, 2) : 'Not set' }}</td>
                        </tr>
                        <tr>
                            <th>Contractor Adjusted Price</th>
                            <td>{{ $schedule->contractor_adjusted_fee !== null ? 'TZS ' . number_format($schedule->contractor_adjusted_fee, 2) : 'Not changed' }}</td>
                        </tr>
                        <tr>
                            <th>Final Schedule Price</th>
                            <td class="fw-bold">{{ $schedule->displayed_price !== null ? 'TZS ' . number_format($schedule->displayed_price, 2) : 'Not set' }}</td>
                        </tr>
                        @if($schedule->billing_rate_change_reason)
                        <tr>
                            <th>Reason</th>
                            <td>{{ $schedule->billing_rate_change_reason }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Notes Section -->
        @if($schedule->notes)
        <div class="content-section">
            <div class="notes-section">
                <h3 class="notes-title">
                    <i class="bi bi-chat-left-text"></i> Notes & Comments
                </h3>
                <p class="mb-0">{{ $schedule->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateStatus(scheduleId, status) {
            fetch(`/schedules/${scheduleId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirectToDisposal) {
                        if (confirm(data.message + '\n\nGo to Disposal page now?')) {
                            window.location.href = '/disposal';
                        } else {
                            location.reload();
                        }
                    } else {
                        location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update status');
            });
        }

        function toggleComplete(scheduleId, isCompleted) {
            const status = isCompleted ? 'completed' : 'scheduled';
            updateStatus(scheduleId, status);
        }
    </script>
</body>
</html>
