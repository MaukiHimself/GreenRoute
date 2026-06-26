<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disposal Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .primary-dark { color: #047857; }
        .primary-light { color: #047857; background-color: rgba(5, 92, 92, 0.1); }
        .accent-color { color: #c0392b; }
        .section-header { 
            border-bottom: 2px solid #047857; 
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .info-card {
            border-left: 4px solid #047857;
            background-color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 0 4px 4px 0;
        }
        .btn-primary-custom {
            background-color: #047857;
            border-color: #047857;
            color: white;
        }
        .btn-primary-custom:hover {
            background-color: #065f46;
            border-color: #065f46;
        }
        .btn-accent {
            background-color: #c0392b;
            border-color: #c0392b;
            color: white;
        }
        .btn-accent:hover {
            background-color: #530303;
            border-color: #530303;
        }
        .empty-state {
            border: 1px dashed #047857;
            background-color: rgba(5, 92, 92, 0.05);
            padding: 20px;
            text-align: center;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-white p-4">

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 primary-light rounded">
            <h4 class="primary-dark mb-0">Disposal Record - {{ $schedule->pickup_location }}</h4>
            <div>
                <a href="{{ route('disposal.edit', $schedule) }}" class="btn btn-accent btn-sm">Edit Data</a>
                <a href="{{ route('disposal.index') }}" class="btn btn-outline-secondary btn-sm">Back to List</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Collection Information -->
            <div class="col-md-6">
                <div class="info-card">
                    <h5 class="section-header primary-dark">Collection Information</h5>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <strong class="primary-dark">Route Name:</strong> 
                            <span class="ms-2">{{ $schedule->pickup_location }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong class="primary-dark">Collection Date:</strong> 
                            <span class="ms-2">{{ $schedule->pickup_date->format('M d, Y') }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong class="primary-dark">Collection Time:</strong> 
                            <span class="ms-2">{{ $schedule->pickup_time }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong class="primary-dark">Site Location:</strong> 
                            <span class="ms-2">{{ $schedule->pickup_address }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong class="primary-dark">Client:</strong> 
                            <span class="ms-2">{{ $schedule->client->name }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong class="primary-dark">Service Type:</strong> 
                            <span class="ms-2">{{ ucfirst($schedule->service_type) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disposal Information -->
            <div class="col-md-6">
                <div class="info-card">
                    <h5 class="section-header primary-dark">Disposal Information</h5>
                    @if($schedule->total_volume)
                        <div class="row">
                            <div class="col-12 mb-2">
                                <strong class="primary-dark">Total Volume Collected:</strong> 
                                <span class="ms-2">{{ number_format($schedule->total_volume, 2) }} m³</span>
                            </div>
                            <div class="col-12 mb-2">
                                <strong class="primary-dark">Disposal Site:</strong> 
                                <span class="ms-2">{{ $schedule->disposal_site }}</span>
                            </div>
                            <div class="col-12 mb-2">
                                <strong class="primary-dark">Disposal Type:</strong> 
                                <span class="ms-2">{{ ucfirst(str_replace('_', ' ', $schedule->disposal_type)) }}</span>
                            </div>
                            @if($schedule->disposal_notes)
                                <div class="col-12 mb-2">
                                    <strong class="primary-dark">Disposal Notes:</strong> 
                                    <span class="ms-2">{{ $schedule->disposal_notes }}</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="empty-state">
                            <p class="accent-color mb-3">Disposal data not yet recorded</p>
                            <a href="{{ route('disposal.edit', $schedule) }}" class="btn btn-primary-custom btn-sm">Record Disposal Data</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Collection Notes -->
        @if($schedule->notes)
        <div class="row mt-3">
            <div class="col-12">
                <div class="info-card">
                    <h5 class="section-header primary-dark">Collection Notes</h5>
                    <p class="mb-0">{{ $schedule->notes }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

</body>
</html>