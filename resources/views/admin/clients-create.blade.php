<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Client - AFIA ORBIT Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .back-link {
            margin-bottom: 1.5rem;
        }
        
        .back-link a {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .page-description {
            color: #666;
            margin-bottom: 2rem;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section h3 {
            font-size: 1.2rem;
            color: var(--primary-teal);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-teal);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-group label .required {
            color: #ef4444;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-teal);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .btn-submit {
            background: var(--primary-teal);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 1rem;
        }
        
        .btn-submit:hover {
            background: #044a4a;
        }
        
        .btn-cancel {
            background: #6b7280;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-cancel:hover {
            background: #4b5563;
            color: white;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .help-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link">
            <a href="{{ route('admin.clients') }}">
                <i class="bi bi-arrow-left me-2"></i>Back to Clients List
            </a>
        </div>
        
        <h1 class="page-title">Register New Client</h1>
        <p class="page-description">Add a new client to the system</p>

        <div class="form-card">
            <form action="{{ route('admin.clients.store') }}" method="POST">
                @csrf

                <!-- Basic Information -->
                <div class="form-section">
                    <h3><i class="bi bi-person me-2"></i>Basic Information</h3>
                    
                    <div class="form-group">
                        <label>Client Name <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            <div class="help-text">Optional - for notifications</div>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Phone Number <span class="required">*</span></label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Category <span class="required">*</span></label>
                            <select name="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="residential" {{ old('category') == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('category') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            </select>
                            @error('category')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Assign to Contractor</label>
                            <select name="contractor_id" class="form-control">
                                <option value="">Unassigned</option>
                                @foreach($contractors as $contractor)
                                    <option value="{{ $contractor->id }}" {{ old('contractor_id') == $contractor->id ? 'selected' : '' }}>
                                        {{ $contractor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contractor_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="form-section">
                    <h3><i class="bi bi-geo-alt me-2"></i>Address Information</h3>
                    
                    <div class="form-group">
                        <label>Street Address <span class="required">*</span></label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                            @error('city')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                            @error('state')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>ZIP Code <span class="required">*</span></label>
                            <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}" required>
                            @error('zip_code')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Service Frequency</label>
                            <select name="service_frequency" class="form-control">
                                <option value="">Select Frequency</option>
                                <option value="daily" {{ old('service_frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('service_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="bi-weekly" {{ old('service_frequency') == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                <option value="monthly" {{ old('service_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                            @error('service_frequency')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- GPS Coordinates (REQUIRED) -->
                <div class="form-section">
                    <h3><i class="bi bi-pin-map me-2"></i>GPS Coordinates <span class="required">*</span> (REQUIRED)</h3>
                    
                    @error('location')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @enderror
                    
                    <div class="mb-3">
                        <button type="button" id="getLocationBtn" class="btn btn-success">
                            <i class="bi bi-crosshair me-2"></i>Get My Location (Required)
                        </button>
                        <div class="help-text mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>IMPORTANT:</strong> GPS location is mandatory. Click the button above to capture precise coordinates.
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Latitude <span class="required">*</span></label>
                            <input type="number" step="any" name="latitude" id="latitude" class="form-control" value="{{ old('latitude') }}" required readonly>
                            <div class="help-text">Will be auto-filled from GPS</div>
                            @error('latitude')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Longitude <span class="required">*</span></label>
                            <input type="number" step="any" name="longitude" id="longitude" class="form-control" value="{{ old('longitude') }}" required readonly>
                            <div class="help-text">Will be auto-filled from GPS</div>
                            @error('longitude')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div id="locationStatus" class="mt-2"></div>
                </div>

                <!-- Additional Notes -->
                <div class="form-section">
                    <h3><i class="bi bi-journal-text me-2"></i>Additional Notes</h3>
                    
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                        <div class="help-text">Any special instructions or information about this client</div>
                        @error('notes')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-section">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle me-2"></i>Register Client
                    </button>
                    <a href="{{ route('admin.clients') }}" class="btn-cancel">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // CRITICAL: Location capture functionality - MANDATORY for client registration
        document.getElementById('getLocationBtn').addEventListener('click', function() {
            const btn = this;
            const statusDiv = document.getElementById('locationStatus');
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Getting location...';
            statusDiv.innerHTML = '<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Requesting location access...</div>';
            
            if (!navigator.geolocation) {
                statusDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>Geolocation is not supported by your browser.</div>';
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-crosshair me-2"></i>Get My Location (Required)';
                return;
            }
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    
                    latInput.value = lat;
                    lngInput.value = lng;
                    
                    // Validate Tanzania bounds
                    if (lat < -11.7 || lat > -0.95 || lng < 29.3 || lng > 40.5) {
                        statusDiv.innerHTML = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i><strong>Warning:</strong> Location appears to be outside Tanzania. Please verify coordinates are correct.</div>';
                    } else {
                        statusDiv.innerHTML = '<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><strong>Success!</strong> Location captured: ' + lat + ', ' + lng + '</div>';
                    }
                    
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check2 me-2"></i>Location Captured';
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                },
                function(error) {
                    let errorMessage = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Location access denied. Please enable location services and try again.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Location information unavailable. Please check your device settings.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Location request timed out. Please try again.';
                            break;
                        default:
                            errorMessage = 'An unknown error occurred while getting location.';
                    }
                    
                    statusDiv.innerHTML = '<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i><strong>Error:</strong> ' + errorMessage + '</div>';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-crosshair me-2"></i>Try Again';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
        
        // Prevent form submission if location not captured
        document.querySelector('form').addEventListener('submit', function(e) {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            
            if (!lat || !lng || lat === '' || lng === '') {
                e.preventDefault();
                alert('CRITICAL: GPS location is required! Please click "Get My Location" button before submitting.');
                document.getElementById('getLocationBtn').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        });
    </script>
</body>
</html>
