<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #055c5c;
            --secondary-color: #640404;
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
        
        .container-fluid {
            padding: 2rem;
            max-width: 1200px;
        }
        
        /* Header Section */
        .page-header {
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
        
        /* Form Section */
        .form-section {
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
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-bg);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }
        
        .required::after {
            content: " *";
            color: var(--secondary-color);
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
        }
        
        .form-control:read-only {
            background-color: var(--light-bg);
            border-color: #cbd5e1;
        }
        
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: var(--secondary-color);
        }
        
        .invalid-feedback {
            color: var(--secondary-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background: #044a4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
            color: white;
        }
        
        .btn-secondary {
            background: var(--text-muted);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-secondary:hover {
            background: #475569;
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-info {
            background: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-info:hover {
            background: #044a4a;
            transform: translateY(-2px);
            color: white;
        }
        
        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        /* Location Section */
        .location-section {
            background: rgba(5, 92, 92, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid var(--primary-color);
        }
        
        .location-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .location-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--light-bg);
        }
        
        /* Section Headers */
        .section-divider {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light-bg);
        }
        
        /* Autocomplete Styles */
        .autocomplete-wrapper {
            position: relative;
        }

        .autocomplete-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .autocomplete-suggestion {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid #f1f5f9;
        }

        .autocomplete-suggestion:last-child {
            border-bottom: none;
        }

        .autocomplete-suggestion:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .container-fluid {
                padding: 1.5rem;
            }
            
            .form-section {
                padding: 2rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }
            
            .form-section {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
                justify-content: center;
            }
            
            .location-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .form-section {
                padding: 1rem;
            }
            
            .form-control, .form-select {
                padding: 0.625rem 0.875rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Register New Client</h1>
        </div>

        <!-- Client Registration Form -->
        <div class="form-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-person-plus"></i>Client Registration
                </h2>
            </div>
            
            <form method="POST" action="{{ route('contractor.clients.store') }}">
                @csrf
                
                <!-- Business and Contact Information -->
                <div class="form-group">
                    <h3 class="section-divider">Business & Contact Information</h3>
                    <div class="form-row">
                        <div>
                            <label for="name" class="form-label required">Business/Client Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="Enter business or client name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="contact_name" class="form-label required">Contact Person Name</label>
                            <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                   id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required 
                                   placeholder="Enter contact person's name">
                            @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Category and Status -->
                <div class="form-group">
                    <div class="form-row">
                        <div>
                            <label for="category" class="form-label required">Category/Type</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="residential" {{ old('category') == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('category') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="industrial" {{ old('category') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="status" class="form-label required">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Phone Numbers -->
                <div class="form-group">
                    <h3 class="section-divider">Contact Numbers</h3>
                    <div class="form-row">
                        <div>
                            <label for="phone" class="form-label required">Phone Number 1</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required 
                                   placeholder="Primary phone number">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="phone_2" class="form-label required">Phone Number 2</label>
                            <input type="text" class="form-control @error('phone_2') is-invalid @enderror" 
                                   id="phone_2" name="phone_2" value="{{ old('phone_2') }}" required 
                                   placeholder="Secondary phone number">
                            @error('phone_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="phone_3" class="form-label required">Phone Number 3</label>
                            <input type="text" class="form-control @error('phone_3') is-invalid @enderror" 
                                   id="phone_3" name="phone_3" value="{{ old('phone_3') }}" required 
                                   placeholder="Additional phone number">
                            @error('phone_3')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Email Addresses -->
                <div class="form-group">
                    <h3 class="section-divider">Email Addresses</h3>
                    <div class="form-row">
                        <div>
                            <label for="email" class="form-label required">Email Address 1</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required 
                                   placeholder="Primary email address">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="email_2" class="form-label">Email Address 2</label>
                            <input type="email" class="form-control @error('email_2') is-invalid @enderror" 
                                   id="email_2" name="email_2" value="{{ old('email_2') }}" 
                                   placeholder="Secondary email address (optional)">
                            @error('email_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="email_3" class="form-label">Email Address 3</label>
                            <input type="email" class="form-control @error('email_3') is-invalid @enderror" 
                                   id="email_3" name="email_3" value="{{ old('email_3') }}" 
                                   placeholder="Additional email address (optional)">
                            @error('email_3')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Address Information -->
                <div class="form-group">
                    <h3 class="section-divider">Location Information</h3>
                    <!-- Address Section -->
                    <div class="mb-3">
                        <label for="address" class="form-label required">Site Location/Address</label>
                        <div class="autocomplete-wrapper">
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" name="address" value="{{ old('address') }}" required 
                                   placeholder="Search for address (Region, District, Ward, or Street)" autocomplete="off">
                            <div id="address-suggestions" class="autocomplete-suggestions"></div>
                            <div id="address-loading" style="position: absolute; right: 10px; top: 10px; display: none;">
                                <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="form-text text-muted">Start typing to search from known locations (Region, District, Ward, Street)</div>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <!-- Location Section -->
                    <div class="location-section">
                        <div class="location-header">
                            <h4 class="location-title">GPS Coordinates <span style="color: var(--secondary-color);">* (REQUIRED)</span></h4>
                            <button type="button" class="btn-info" id="getLocationBtn" onclick="getLocation()">
                                <i class="bi bi-geo-alt"></i> Get Current Location (Required)
                            </button>
                        </div>
                        
                        @error('location')
                            <div class="alert alert-danger mt-3" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; border: 1px solid #f5c6cb;">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Location Error:</strong> {{ $message }}
                            </div>
                        @enderror
                        
                        <div id="locationStatus" class="mt-3"></div>
                        
                        <p class="text-muted mt-2 mb-3" style="font-size: 0.9rem;">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>IMPORTANT:</strong> GPS location is mandatory. Click the button above to capture precise coordinates.
                        </p>
                        
                        <div class="form-row">
                            <div>
                                <label for="latitude" class="form-label required">Latitude</label>
                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                       id="latitude" name="latitude" value="{{ old('latitude') }}" required readonly 
                                       placeholder="Will be auto-filled from GPS">
                                @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="longitude" class="form-label required">Longitude</label>
                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                       id="longitude" name="longitude" value="{{ old('longitude') }}" required readonly 
                                       placeholder="Will be auto-filled from GPS">
                                @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div>
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" 
                                   placeholder="City name">
                        </div>
                        <div>
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" 
                                   placeholder="State or province">
                        </div>
                        <div>
                            <label for="zip_code" class="form-label">ZIP Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code') }}" 
                                   placeholder="Postal code">
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="form-group">
                    <h3 class="section-divider">Additional Information</h3>
                    <div>
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Any additional notes about this client...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-check-circle"></i> Register Client
                    </button>
                    <a href="{{ route('contractor.clients.index') }}" class="btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Address Autocomplete Logic
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('address');
            const suggestionsBox = document.getElementById('address-suggestions');
            const loadingSpinner = document.getElementById('address-loading');
            let debounceTimer;

            if (input && suggestionsBox) {
                input.addEventListener('input', function() {
                    const query = this.value.trim();
                    
                    clearTimeout(debounceTimer);
                    suggestionsBox.style.display = 'none';
                    
                    if (query.length < 2) {
                        return;
                    }

                    debounceTimer = setTimeout(() => {
                        // Show loading spinner
                        if(loadingSpinner) loadingSpinner.style.display = 'block';

                        // Use the simple autocomplete endpoint
                        fetch(`/api/locations/autocomplete-simple?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                if(loadingSpinner) loadingSpinner.style.display = 'none';
                                
                                if (data.success && data.data && data.data.length > 0) {
                                    renderSuggestions(data.data);
                                } else {
                                    suggestionsBox.style.display = 'none';
                                }
                            })
                            .catch(err => {
                                if(loadingSpinner) loadingSpinner.style.display = 'none';
                                console.error('Autocomplete error:', err);
                                suggestionsBox.style.display = 'none';
                            });
                    }, 300);
                });

                function renderSuggestions(suggestions) {
                    suggestionsBox.innerHTML = '';
                    
                    suggestions.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'autocomplete-suggestion';
                        
                        div.innerHTML = `
                            <div>${item.value}</div>
                        `;
                        
                        div.addEventListener('click', function() {
                            input.value = item.value;
                            suggestionsBox.style.display = 'none';
                            
                            // Auto-fill City and State if available
                            const cityInput = document.getElementById('city');
                            const stateInput = document.getElementById('state');
                            
                            if (cityInput && !cityInput.value) {
                                cityInput.value = item.district || item.region || '';
                            }
                            
                            if (stateInput && !stateInput.value) {
                                stateInput.value = item.region || '';
                            }
                        });
                        
                        suggestionsBox.appendChild(div);
                    });
                    
                    suggestionsBox.style.display = 'block';
                }

                // Close suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (e.target !== input && e.target !== suggestionsBox && !suggestionsBox.contains(e.target)) {
                        suggestionsBox.style.display = 'none';
                    }
                });
            }
        });

        function getLocation() {
            const locationBtn = document.getElementById('getLocationBtn');
            const statusDiv = document.getElementById('locationStatus');
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            if (!navigator.geolocation) {
                statusDiv.innerHTML = '<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px;"><i class="bi bi-x-circle me-2"></i><strong>Error:</strong> Geolocation is not supported by your browser.</div>';
                showNotification('Geolocation is not supported by this browser.', 'error');
                return;
            }
            
            // Show loading state
            const originalText = locationBtn.innerHTML;
            locationBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Getting Location...';
            locationBtn.disabled = true;
            statusDiv.innerHTML = '<div class="alert alert-info" style="background: #d1ecf1; color: #0c5460; padding: 1rem; border-radius: 8px;"><i class="bi bi-info-circle me-2"></i>Requesting location access...</div>';
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    
                    latInput.value = lat;
                    lngInput.value = lng;
                    
                    // CRITICAL: Validate Tanzania bounds
                    if (lat < -11.7 || lat > -0.95 || lng < 29.3 || lng > 40.5) {
                        statusDiv.innerHTML = '<div class="alert alert-warning" style="background: #fff3cd; color: #856404; padding: 1rem; border-radius: 8px;"><i class="bi bi-exclamation-triangle me-2"></i><strong>Warning:</strong> Location appears to be outside Tanzania (Lat: ' + lat + ', Lng: ' + lng + '). Please verify coordinates are correct.</div>';
                        showNotification('Warning: Location appears to be outside Tanzania', 'error');
                    } else {
                        statusDiv.innerHTML = '<div class="alert alert-success" style="background: #d1e7dd; color: #0f5132; padding: 1rem; border-radius: 8px;"><i class="bi bi-check-circle me-2"></i><strong>Success!</strong> Location captured: ' + lat + ', ' + lng + '</div>';
                        showNotification('Location captured successfully!', 'success');
                    }
                    
                    // Update button
                    locationBtn.innerHTML = '<i class="bi bi-check2"></i> Location Captured';
                    locationBtn.disabled = false;
                    locationBtn.classList.remove('btn-info');
                    locationBtn.classList.add('btn-success');
                },
                function(error) {
                    let errorMessage = 'Error getting location: ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Location access denied. Please enable location services and try again.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Location information is unavailable. Please check your device settings.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Location request timed out. Please try again.';
                            break;
                        default:
                            errorMessage += 'An unknown error occurred.';
                            break;
                    }
                    
                    statusDiv.innerHTML = '<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px;"><i class="bi bi-x-circle me-2"></i><strong>Error:</strong> ' + errorMessage + '</div>';
                    
                    // Restore button
                    locationBtn.innerHTML = originalText;
                    locationBtn.disabled = false;
                    
                    showNotification(errorMessage, 'error');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
        
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 1000;
                animation: slideIn 0.3s ease;
                max-width: 400px;
            `;
            
            if (type === 'success') {
                notification.style.background = 'var(--primary-color)';
            } else {
                notification.style.background = 'var(--secondary-color)';
            }
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Remove after 5 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 5000);
        }
        
        // Add form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const requiredFields = form.querySelectorAll('[required]');
            
            // Add spinner animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .spinner {
                    animation: spin 1s linear infinite;
                }
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
            
            form.addEventListener('submit', function(e) {
                // CRITICAL: Check location first
                const lat = document.getElementById('latitude').value;
                const lng = document.getElementById('longitude').value;
                
                if (!lat || !lng || lat === '' || lng === '') {
                    e.preventDefault();
                    alert('CRITICAL: GPS location is required! Please click "Get Current Location (Required)" button before submitting.');
                    document.getElementById('getLocationBtn').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    showNotification('GPS location is mandatory! Click the location button.', 'error');
                    return false;
                }
                
                // Validate Tanzania bounds before submission
                const latNum = parseFloat(lat);
                const lngNum = parseFloat(lng);
                
                if (latNum < -11.7 || latNum > -0.95 || lngNum < 29.3 || lngNum > 40.5) {
                    if (!confirm('Warning: The location appears to be outside Tanzania. Do you want to proceed anyway?')) {
                        e.preventDefault();
                        return false;
                    }
                }
                
                // Check other required fields
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                        
                        // Scroll to first invalid field
                        if (isValid) {
                            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    showNotification('Please fill in all required fields marked with *.', 'error');
                }
            });
            
            // Remove invalid state when user starts typing
            requiredFields.forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });
            
            // Add character counter for notes
            const notes = document.getElementById('notes');
            if (notes) {
                const counter = document.createElement('div');
                counter.className = 'text-muted small mt-1';
                counter.style.textAlign = 'right';
                counter.textContent = '0/500 characters';
                notes.parentNode.appendChild(counter);
                
                notes.addEventListener('input', function() {
                    const length = this.value.length;
                    counter.textContent = `${length}/500 characters`;
                    if (length > 500) {
                        counter.style.color = 'var(--secondary-color)';
                    } else {
                        counter.style.color = 'var(--text-muted)';
                    }
                });
            }
        });
    </script>
</body>
</html>