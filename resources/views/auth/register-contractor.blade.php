<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .primary-dark { color: #055c5c; }
        .primary-light { 
            background-color: rgba(5, 92, 92, 0.08);
            border-left: 4px solid #055c5c;
        }
        .accent-color { color: #c0392b; }
        .btn-primary-custom {
            background-color: #055c5c;
            border-color: #055c5c;
            color: white;
        }
        .btn-primary-custom:hover {
            background-color: #044a4a;
            border-color: #044a4a;
        }
        .form-control:focus, .form-select:focus {
            border-color: #055c5c;
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.25);
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: rgba(5, 92, 92, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: #055c5c;
        }
        .alert-success-custom {
            background-color: rgba(5, 92, 92, 0.1);
            border-color: #055c5c;
            color: #055c5c;
        }
        .alert-danger-custom {
            background-color: rgba(100, 4, 4, 0.1);
            border-color: #c0392b;
            color: #c0392b;
        }
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            border: 1px solid rgba(5, 92, 92, 0.15);
            box-shadow: 0 4px 6px rgba(5, 92, 92, 0.05);
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        /* Location Autocomplete Styles */
        .location-autocomplete-container {
            position: relative;
        }
        
        .location-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            min-height: 50px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            margin-bottom: 10px;
        }
        
        .location-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(5, 92, 92, 0.1);
            border: 1px solid #055c5c;
            border-radius: 20px;
            font-size: 14px;
            color: #055c5c;
        }
        
        .location-tag .remove-tag {
            cursor: pointer;
            font-weight: bold;
            color: #c0392b;
            padding: 0 4px;
            border-radius: 50%;
            transition: all 0.2s;
        }
        
        .location-tag .remove-tag:hover {
            background: #c0392b;
            color: white;
        }
        
        .autocomplete-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 4px 4px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: none;
        }
        
        .autocomplete-suggestions.show {
            display: block;
        }
        
        .autocomplete-suggestion {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }
        
        .autocomplete-suggestion:hover {
            background: rgba(5, 92, 92, 0.05);
        }
        
        .autocomplete-suggestion:last-child {
            border-bottom: none;
        }
        
        .autocomplete-loading {
            padding: 12px 16px;
            text-align: center;
            color: #666;
        }
        
        .autocomplete-no-results {
            padding: 12px 16px;
            text-align: center;
            color: #999;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header Section -->
                <div class="text-center mb-5">
                    <div class="icon-circle">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h1 class="fw-bold primary-dark mb-3">Contractor Registration</h1>
                    <p class="lead text-muted">Join our platform to grow your waste management business</p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger-custom mb-4 rounded">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-3 mt-1 fs-5"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-2">Please correct the following errors:</h5>
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li class="mb-1">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Registration Form -->
                <div class="form-section">
                    <form method="POST" action="{{ route('register.contractor.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_type" value="contractor">
                        
                        <!-- Company Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="primary-dark mb-3 border-bottom pb-2">Company Information</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label primary-dark">Company Name *</label>
                                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required autofocus
                                       class="form-control form-control-lg" placeholder="Enter your company name">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="representative_name" class="form-label primary-dark">Representative Name *</label>
                                <input id="representative_name" type="text" name="name" value="{{ old('name') }}" required
                                       class="form-control form-control-lg" placeholder="Enter representative's full name">
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="primary-dark mb-3 border-bottom pb-2">Contact Information</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label primary-dark">Business Email *</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                       class="form-control form-control-lg" placeholder="Enter business email address">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label primary-dark">Business Phone *</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                                       class="form-control form-control-lg" placeholder="Enter business phone number">
                            </div>
                        </div>
                        
                        <!-- Location Information -->
                        <div class="mb-4">
                            <label for="address" class="form-label primary-dark">Business Address *</label>
                            <input id="address" type="text" name="address" value="{{ old('address') }}" required
                                   class="form-control form-control-lg" placeholder="Enter complete business address">
                        </div>
                        
                        <div class="mb-4">
                            <label for="site_locations_input" class="form-label primary-dark">Site Locations *</label>
                            
                            <!-- Selected Locations Tags -->
                            <div id="location-tags-container" class="location-tags">
                                <span class="text-muted small">Start typing to search locations...</span>
                            </div>
                            
                            <!-- Autocomplete Input Container -->
                            <div class="location-autocomplete-container">
                                <input type="text" id="site_locations_input" 
                                       class="form-control form-control-lg" 
                                       placeholder="Type location name (e.g., ARUSHA, SEKEI, etc.)"
                                       autocomplete="off">
                                <div id="autocomplete-suggestions" class="autocomplete-suggestions"></div>
                            </div>
                            
                            <!-- Hidden field to store selected locations -->
                            <input type="hidden" name="site_locations" id="site_locations_hidden" required value="{{ old('site_locations') }}">
                            
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Search and select locations in format: Region → District → Ward → Street
                            </div>
                        </div>
                        
                        <!-- Business Documentation -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="primary-dark mb-3 border-bottom pb-2">Business Documentation</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="license_number" class="form-label primary-dark">Business License Number *</label>
                                <input id="license_number" type="text" name="license_number" value="{{ old('license_number') }}" required
                                       class="form-control form-control-lg" placeholder="Enter license number">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="certificate" class="form-label primary-dark">Certificate of Incorporation *</label>
                                <input id="certificate" type="file" name="certificate" required accept=".pdf,.jpg,.jpeg,.png"
                                       class="form-control form-control-lg">
                                <div class="form-text">Upload PDF, JPG, or PNG files (max 5MB)</div>
                            </div>
                        </div>
                        
                        <!-- Account Security -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="primary-dark mb-3 border-bottom pb-2">Account Security</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label primary-dark">Password *</label>
                                <input id="password" type="password" name="password" required
                                       class="form-control form-control-lg" placeholder="Create a strong password">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label primary-dark">Confirm Password *</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                       class="form-control form-control-lg" placeholder="Confirm your password">
                            </div>
                        </div>
                        
                        <!-- Benefits Section -->
                        <div class="alert alert-success-custom mb-4 rounded">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill me-3 mt-1 fs-5"></i>
                                <div class="flex-grow-1">
                                    <h5 class="alert-heading mb-3">What you'll get as a contractor:</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <i class="bi bi-people-fill me-2"></i>Manage client database
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <i class="bi bi-calendar-check me-2"></i>Schedule and track pickups
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <i class="bi bi-receipt me-2"></i>Generate invoices and reports
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <i class="bi bi-graph-up me-2"></i>Grow your business efficiently
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-custom btn-lg py-3">
                                <i class="bi bi-building me-2"></i>Create Business Account
                            </button>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted mb-0">Already have an account? 
                            <a href="{{ route('login.contractor') }}" class="primary-dark text-decoration-none fw-semibold">Sign in here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Location Autocomplete System
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('site_locations_input');
            const suggestionsContainer = document.getElementById('autocomplete-suggestions');
            const tagsContainer = document.getElementById('location-tags-container');
            const hiddenField = document.getElementById('site_locations_hidden');
            
            let selectedLocations = [];
            let debounceTimer;
            
            // Load old locations if they exist (for validation errors)
            const oldLocations = hiddenField.value;
            if (oldLocations) {
                selectedLocations = oldLocations.split(',').filter(loc => loc.trim());
                renderTags();
            }
            
            // Input event listener with debouncing
            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    hideSuggestions();
                    return;
                }
                
                debounceTimer = setTimeout(() => {
                    fetchSuggestions(query);
                }, 300);
            });
            
            // Close suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                    hideSuggestions();
                }
            });
            
            // Fetch suggestions from API
            async function fetchSuggestions(query) {
                suggestionsContainer.innerHTML = '<div class="autocomplete-loading"><i class="bi bi-arrow-repeat spinner-border spinner-border-sm me-2"></i>Loading...</div>';
                suggestionsContainer.classList.add('show');
                
                try {
                    const response = await fetch(`/api/locations/autocomplete-simple?q=${encodeURIComponent(query)}&limit=15`);
                    const data = await response.json();
                    
                    if (data.success && data.data.length > 0) {
                        displaySuggestions(data.data);
                    } else {
                        suggestionsContainer.innerHTML = '<div class="autocomplete-no-results">No locations found</div>';
                    }
                } catch (error) {
                    console.error('Error fetching locations:', error);
                    suggestionsContainer.innerHTML = '<div class="autocomplete-no-results text-danger">Error loading locations</div>';
                }
            }
            
            // Display suggestions
            function displaySuggestions(suggestions) {
                suggestionsContainer.innerHTML = '';
                
                suggestions.forEach(location => {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-suggestion';
                    
                    // Format: Region → District → Ward → Street
                    const locationText = location.value;
                    
                    div.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                            <div>
                                <div class="fw-semibold">${locationText}</div>
                                <small class="text-muted">
                                    ${location.region} • ${location.district} • ${location.ward}
                                    ${location.street ? ' • ' + location.street : ''}
                                </small>
                            </div>
                        </div>
                    `;
                    
                    div.addEventListener('click', () => {
                        selectLocation(location);
                    });
                    
                    suggestionsContainer.appendChild(div);
                });
                
                suggestionsContainer.classList.add('show');
            }
            
            // Select a location
            function selectLocation(location) {
                const locationText = location.value;
                
                // Check if already selected
                if (!selectedLocations.includes(locationText)) {
                    selectedLocations.push(locationText);
                    renderTags();
                    updateHiddenField();
                }
                
                // Clear input and hide suggestions
                input.value = '';
                hideSuggestions();
                input.focus();
            }
            
            // Remove a location
            function removeLocation(locationText) {
                selectedLocations = selectedLocations.filter(loc => loc !== locationText);
                renderTags();
                updateHiddenField();
            }
            
            // Render location tags
            function renderTags() {
                if (selectedLocations.length === 0) {
                    tagsContainer.innerHTML = '<span class="text-muted small">Start typing to search locations...</span>';
                    return;
                }
                
                tagsContainer.innerHTML = '';
                
                selectedLocations.forEach(location => {
                    const tag = document.createElement('div');
                    tag.className = 'location-tag';
                    tag.innerHTML = `
                        <span>${location}</span>
                        <span class="remove-tag" title="Remove location">×</span>
                    `;
                    
                    tag.querySelector('.remove-tag').addEventListener('click', () => {
                        removeLocation(location);
                    });
                    
                    tagsContainer.appendChild(tag);
                });
            }
            
            // Update hidden field
            function updateHiddenField() {
                hiddenField.value = selectedLocations.join(', ');
            }
            
            // Hide suggestions
            function hideSuggestions() {
                suggestionsContainer.classList.remove('show');
            }
            
            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                if (selectedLocations.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one site location');
                    input.focus();
                }
            });
        });
    </script>
</body>
</html>