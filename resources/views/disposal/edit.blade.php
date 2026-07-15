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
                        <label for="weight_kg" class="form-label">Waste Weight (kg) *</label>
                        <input type="number" class="form-control @error('weight_kg') is-invalid @enderror"
                               id="weight_kg" name="weight_kg" step="0.1" min="0.1"
                               value="{{ old('weight_kg', $schedule->weight_kg) }}"
                               placeholder="e.g. 120.5" required>
                        <small class="text-muted">From the weighbridge/scale, or estimated (standard bag ≈ 5 kg, bin ≈ 20 kg).</small>
                        @error('weight_kg')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="waste_category" class="form-label">Waste Category *</label>
                        <select class="form-select @error('waste_category') is-invalid @enderror" id="waste_category" name="waste_category" required>
                            <option value="">Select Category</option>
                            <option value="general" {{ old('waste_category', $schedule->waste_category) == 'general' ? 'selected' : '' }}>General Waste</option>
                            <option value="organic" {{ old('waste_category', $schedule->waste_category) == 'organic' ? 'selected' : '' }}>Organic Waste</option>
                            <option value="recyclable" {{ old('waste_category', $schedule->waste_category) == 'recyclable' ? 'selected' : '' }}>Recyclable</option>
                            <option value="mixed" {{ old('waste_category', $schedule->waste_category) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                        </select>
                        @error('waste_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="disposal_site" class="form-label">Disposal Site *</label>
                    <select class="form-select @error('disposal_site') is-invalid @enderror" id="disposal_site" name="disposal_site" required>
                        <option value="">Select Disposal Site</option>
                        @foreach($dumpingSites as $site)
                            <option value="{{ $site }}" {{ old('disposal_site', $schedule->disposal_site) == $site ? 'selected' : '' }}>{{ $site }}</option>
                        @endforeach
                        @if($schedule->disposal_site && !$dumpingSites->contains($schedule->disposal_site))
                            <option value="{{ $schedule->disposal_site }}" selected>{{ $schedule->disposal_site }}</option>
                        @endif
                    </select>
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