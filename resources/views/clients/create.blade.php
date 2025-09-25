<x-guest-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-success mb-0">Register New Client</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('contractor.clients.store') }}">
                            @csrf
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Business/Client Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_name" class="form-label">Contact Person Name *</label>
                                    <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                           id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required>
                                    @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Category/Type *</label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="residential" {{ old('category') == 'residential' ? 'selected' : '' }}>Residential</option>
                                        <option value="commercial" {{ old('category') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                        <option value="industrial" {{ old('category') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                    </select>
                                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="phone" class="form-label">Phone Number 1 *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="phone_2" class="form-label">Phone Number 2 *</label>
                                    <input type="text" class="form-control @error('phone_2') is-invalid @enderror" 
                                           id="phone_2" name="phone_2" value="{{ old('phone_2') }}" required>
                                    @error('phone_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="phone_3" class="form-label">Phone Number 3 *</label>
                                    <input type="text" class="form-control @error('phone_3') is-invalid @enderror" 
                                           id="phone_3" name="phone_3" value="{{ old('phone_3') }}" required>
                                    @error('phone_3')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email Address 1 *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="email_2" class="form-label">Email Address 2</label>
                                    <input type="email" class="form-control @error('email_2') is-invalid @enderror" 
                                           id="email_2" name="email_2" value="{{ old('email_2') }}">
                                    @error('email_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="email_3" class="form-label">Email Address 3</label>
                                    <input type="email" class="form-control @error('email_3') is-invalid @enderror" 
                                           id="email_3" name="email_3" value="{{ old('email_3') }}">
                                    @error('email_3')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Site Location/Address *</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                       id="address" name="address" value="{{ old('address') }}" required>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="latitude" class="form-label">Latitude *</label>
                                    <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" 
                                           id="latitude" name="latitude" value="{{ old('latitude') }}" required readonly>
                                    @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="longitude" class="form-label">Longitude *</label>
                                    <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude') }}" required readonly>
                                    @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <button type="button" class="btn btn-info" onclick="getLocation()">
                                    <i class="bi bi-geo-alt"></i> Get Current Location
                                </button>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="zip_code" class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code') }}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Register Client
                                </button>
                                <a href="{{ route('contractor.clients.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                }, function(error) {
                    alert('Error getting location: ' + error.message);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
    </script>
</x-guest-layout>