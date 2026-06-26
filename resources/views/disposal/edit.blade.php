<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Disposal Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .primary-dark { 
            color: #047857; 
        }
        .primary-light { 
            background-color: rgba(5, 92, 92, 0.08);
            border-left: 4px solid #047857;
        }
        .accent-color { 
            color: #c0392b; 
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
        .btn-outline-custom {
            border-color: #047857;
            color: #047857;
        }
        .btn-outline-custom:hover {
            background-color: #047857;
            color: white;
        }
        .section-header {
            border-bottom: 2px solid #047857;
            padding-bottom: 8px;
            margin-bottom: 20px;
            color: #047857;
        }
        .form-label {
            font-weight: 600;
            color: #047857;
            margin-bottom: 8px;
        }
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(5, 92, 92, 0.1);
        }
        .form-control:focus, .form-select:focus {
            border-color: #047857;
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.25);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 primary-light rounded">
            <h4 class="primary-dark mb-0">
                <i class="bi bi-clipboard-data me-2"></i>
                Record Disposal Data - {{ $schedule->pickup_location }}
            </h4>
        </div>
        
        <!-- Collection Information Card -->
        <div class="info-card">
            <h5 class="section-header">Collection Information</h5>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <strong class="primary-dark">Route:</strong><br>
                    <span>{{ $schedule->pickup_location }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <strong class="primary-dark">Collection Date:</strong><br>
                    <span>{{ $schedule->pickup_date->format('M d, Y') }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <strong class="primary-dark">Site Location:</strong><br>
                    <span>{{ $schedule->pickup_address }}</span>
                </div>
                <div class="col-md-3 mb-2">
                    <strong class="primary-dark">Client:</strong><br>
                    <span>{{ $schedule->client->name }}</span>
                </div>
            </div>
        </div>

        <!-- Disposal Form Card -->
        <div class="info-card">
            <form method="POST" action="{{ route('disposal.update', $schedule) }}">
                @csrf
                @method('PUT')
                
                <h5 class="section-header">Disposal Information</h5>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="total_volume" class="form-label">Total Volume Collected (m³) *</label>
                        <input type="number" class="form-control @error('total_volume') is-invalid @enderror" 
                               id="total_volume" name="total_volume" step="0.01" 
                               value="{{ old('total_volume', $schedule->total_volume) }}" required>
                        @error('total_volume')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="disposal_type" class="form-label">Disposal Type *</label>
                        <select class="form-select @error('disposal_type') is-invalid @enderror" id="disposal_type" name="disposal_type" required>
                            <option value="">Select Disposal Type</option>
                            <option value="sorting_facility" {{ old('disposal_type', $schedule->disposal_type) == 'sorting_facility' ? 'selected' : '' }}>Sorting Facility</option>
                            <option value="landfill" {{ old('disposal_type', $schedule->disposal_type) == 'landfill' ? 'selected' : '' }}>Landfill</option>
                        </select>
                        @error('disposal_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="disposal_site" class="form-label">Disposal Site Location *</label>
                    <input type="text" class="form-control @error('disposal_site') is-invalid @enderror" 
                           id="disposal_site" name="disposal_site" 
                           value="{{ old('disposal_site', $schedule->disposal_site) }}" 
                           placeholder="Enter the name/location of disposal site" required>
                    @error('disposal_site')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="disposal_notes" class="form-label">Disposal Notes</label>
                    <textarea class="form-control" id="disposal_notes" name="disposal_notes" rows="3" 
                              placeholder="Additional notes about the disposal process">{{ old('disposal_notes', $schedule->disposal_notes) }}</textarea>
                </div>
                
                <div class="d-flex gap-2 pt-3 border-top">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-check-circle me-2"></i>Record Disposal Data
                    </button>
                    <a href="{{ route('disposal.index') }}" class="btn btn-outline-custom">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>