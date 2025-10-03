<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
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
            <h1 class="page-title">Edit Client</h1>
        </div>

        <!-- Edit Client Form -->
        <div class="form-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-person-gear"></i>Client Information
                </h2>
            </div>
            
            <form method="POST" action="{{ route('contractor.clients.update', $client) }}">
                @csrf
                @method('PUT')
                
                <!-- Business and Contact Information -->
                <div class="form-group">
                    <h5 class="mb-3" style="color: var(--primary-color);">Business & Contact Information</h5>
                    <div class="form-row">
                        <div>
                            <label for="name" class="form-label required">Business/Client Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $client->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="contact_name" class="form-label required">Contact Person Name</label>
                            <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                   id="contact_name" name="contact_name" value="{{ old('contact_name', $client->contact_name) }}" required>
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
                                <option value="residential" {{ old('category', $client->category) == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('category', $client->category) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="industrial" {{ old('category', $client->category) == 'industrial' ? 'selected' : '' }}>Industrial</option>
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="status" class="form-label required">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Phone Numbers -->
                <div class="form-group">
                    <h5 class="mb-3" style="color: var(--primary-color);">Contact Numbers</h5>
                    <div class="form-row">
                        <div>
                            <label for="phone" class="form-label required">Phone Number 1</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $client->phone) }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="phone_2" class="form-label required">Phone Number 2</label>
                            <input type="text" class="form-control @error('phone_2') is-invalid @enderror" 
                                   id="phone_2" name="phone_2" value="{{ old('phone_2', $client->phone_2) }}" required>
                            @error('phone_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="phone_3" class="form-label required">Phone Number 3</label>
                            <input type="text" class="form-control @error('phone_3') is-invalid @enderror" 
                                   id="phone_3" name="phone_3" value="{{ old('phone_3', $client->phone_3) }}" required>
                            @error('phone_3')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Email Addresses -->
                <div class="form-group">
                    <h5 class="mb-3" style="color: var(--primary-color);">Email Addresses</h5>
                    <div class="form-row">
                        <div>
                            <label for="email" class="form-label required">Email Address 1</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $client->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="email_2" class="form-label">Email Address 2</label>
                            <input type="email" class="form-control @error('email_2') is-invalid @enderror" 
                                   id="email_2" name="email_2" value="{{ old('email_2', $client->email_2) }}">
                            @error('email_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label for="email_3" class="form-label">Email Address 3</label>
                            <input type="email" class="form-control @error('email_3') is-invalid @enderror" 
                                   id="email_3" name="email_3" value="{{ old('email_3', $client->email_3) }}">
                            @error('email_3')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Address Information -->
                <div class="form-group">
                    <h5 class="mb-3" style="color: var(--primary-color);">Location Information</h5>
                    <div class="mb-3">
                        <label for="address" class="form-label required">Site Location/Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" 
                               id="address" name="address" value="{{ old('address', $client->address) }}" required>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <!-- Location Section -->
                    <div class="location-section">
                        <div class="location-header">
                            <h6 class="location-title">GPS Coordinates</h6>
                            <button type="button" class="btn-info" onclick="getLocation()">
                                <i class="bi bi-geo-alt"></i> Update Location
                            </button>
                        </div>
                        <div class="form-row">
                            <div>
                                <label for="latitude" class="form-label required">Latitude</label>
                                <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                       id="latitude" name="latitude" value="{{ old('latitude', $client->latitude) }}" required readonly>
                                @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label for="longitude" class="form-label required">Longitude</label>
                                <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                       id="longitude" name="longitude" value="{{ old('longitude', $client->longitude) }}" required readonly>
                                @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div>
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $client->city) }}">
                        </div>
                        <div>
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $client->state) }}">
                        </div>
                        <div>
                            <label for="zip_code" class="form-label">ZIP Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code', $client->zip_code) }}">
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="form-group">
                    <h5 class="mb-3" style="color: var(--primary-color);">Additional Information</h5>
                    <div>
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional notes about this client...">{{ old('notes', $client->notes) }}</textarea>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-check-circle"></i> Update Client
                    </button>
                    <a href="{{ route('contractor.clients.show', $client) }}" class="btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Details
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                    document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                    
                    // Show success message
                    alert('Location updated successfully!');
                }, function(error) {
                    let errorMessage = 'Error getting location: ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'User denied the request for Geolocation.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Location information is unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'The request to get user location timed out.';
                            break;
                        case error.UNKNOWN_ERROR:
                            errorMessage += 'An unknown error occurred.';
                            break;
                    }
                    alert(errorMessage);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
        
        // Add form validation enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const requiredFields = form.querySelectorAll('[required]');
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields marked with *.');
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
        });
    </script>
</body>
</html>