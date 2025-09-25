<x-guest-layout>
    <div class="text-center mb-4">
        <div class="icon-circle">
            <i class="bi bi-truck text-success"></i>
        </div>
        <h2 class="fw-bold text-dark mb-2">Contractor Registration</h2>
        <p class="text-muted">Join our platform to grow your waste management business</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                <div>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register.contractor.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_type" value="contractor">
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="company_name" class="form-label fw-medium">Company Name</label>
                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required autofocus
                       class="form-control form-control-lg">
            </div>
            
            <div class="col-md-6">
                <label for="representative_name" class="form-label fw-medium">Representative Name</label>
                <input id="representative_name" type="text" name="name" value="{{ old('name') }}" required
                       class="form-control form-control-lg">
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="email" class="form-label fw-medium">Business Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="form-control form-control-lg">
            </div>
            
            <div class="col-md-6">
                <label for="phone" class="form-label fw-medium">Business Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                       class="form-control form-control-lg">
            </div>
        </div>
        
        <div class="mb-3">
            <label for="address" class="form-label fw-medium">Business Address</label>
            <input id="address" type="text" name="address" value="{{ old('address') }}" required
                   class="form-control form-control-lg">
        </div>
        
        <div class="mb-3">
            <label for="site_locations" class="form-label fw-medium">Site Locations</label>
            <textarea id="site_locations" name="site_locations" rows="3" required
                      class="form-control form-control-lg" placeholder="List your service areas or site locations">{{ old('site_locations') }}</textarea>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="license_number" class="form-label fw-medium">Business License Number</label>
                <input id="license_number" type="text" name="license_number" value="{{ old('license_number') }}" required
                       class="form-control form-control-lg">
            </div>
            
            <div class="col-md-6">
                <label for="certificate" class="form-label fw-medium">Certificate of Incorporation</label>
                <input id="certificate" type="file" name="certificate" required accept=".pdf,.jpg,.jpeg,.png"
                       class="form-control form-control-lg">
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label fw-medium">Password</label>
                <input id="password" type="password" name="password" required
                       class="form-control form-control-lg">
            </div>
            
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="form-control form-control-lg">
            </div>
        </div>
        
        <div class="alert alert-success mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-check-circle-fill me-2 mt-1"></i>
                <div>
                    <strong>What you'll get:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Manage client database</li>
                        <li>Schedule and track pickups</li>
                        <li>Generate invoices and reports</li>
                        <li>Grow your business efficiently</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success btn-lg w-100 mb-3">
            <i class="bi bi-building me-2"></i>Create Business Account
        </button>
    </form>

    <div class="text-center">
        <p class="text-muted mb-0">Already have an account? 
            <a href="{{ route('login.contractor') }}" class="text-success text-decoration-none fw-medium">Sign in here</a>
        </p>
    </div>
</x-guest-layout>