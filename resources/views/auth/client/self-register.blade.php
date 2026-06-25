<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — GreenRoute Client Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --teal: #055c5c; --red: #c0392b; }
        body { background: linear-gradient(135deg, #f0f9f9 0%, #e2e8f0 100%); min-height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .card { border: none; border-radius: 16px; box-shadow: 0 8px 30px rgba(5,92,92,.12); }
        .card-header { background: linear-gradient(135deg, var(--teal), #077777); color: white; border-radius: 16px 16px 0 0 !important; padding: 2rem; }
        .form-control, .form-select { border: 2px solid #e2e8f0; border-radius: 10px; padding: .75rem 1rem; }
        .form-control:focus, .form-select:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(5,92,92,.1); }
        .btn-teal { background: var(--teal); color: white; border: none; border-radius: 10px; padding: .85rem 2rem; font-weight: 600; }
        .btn-teal:hover { background: #044a4a; color: white; }
        .step-badge { background: rgba(255,255,255,.2); border-radius: 50px; padding: .25rem 1rem; font-size: .85rem; }
        .section-label { color: var(--teal); font-weight: 700; border-bottom: 2px solid #e2e8f0; padding-bottom: .5rem; margin-bottom: 1.25rem; }
        #routeLoadingSpinner { display: none; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-recycle fs-3 me-3"></i>
                        <div>
                            <h2 class="mb-0 fw-bold">GreenRoute</h2>
                            <small class="step-badge">Client Self-Registration</small>
                        </div>
                    </div>
                    <p class="mb-0 mt-2" style="opacity:.9;">Register below and you'll be automatically matched to a waste contractor serving your area. Your account will be activated once your contractor approves your registration.</p>
                </div>

                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Please fix the following:</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('client.self-register.store') }}">
                        @csrf

                        {{-- Business / Personal Info --}}
                        <p class="section-label"><i class="bi bi-person-badge me-2"></i>Your Information</p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Business / Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. John's Residence or ABC Shop" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contact Person Name <span class="text-danger">*</span></label>
                                <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name') }}" placeholder="Your full name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="e.g. 0712345678" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
                                    <option value="">Select your category</option>
                                    <option value="Residential (Unplanned)"          {{ old('category')=='Residential (Unplanned)' ? 'selected' : '' }}>Residential (Unplanned)</option>
                                    <option value="Residential (Planned/Modern)"     {{ old('category')=='Residential (Planned/Modern)' ? 'selected' : '' }}>Residential (Planned/Modern)</option>
                                    <option value="Commercial Residential (Apartment)" {{ old('category')=='Commercial Residential (Apartment)' ? 'selected' : '' }}>Commercial Residential (Apartment)</option>
                                    <option value="Restaurant"                       {{ old('category')=='Restaurant' ? 'selected' : '' }}>Restaurant</option>
                                    <option value="Retail shops (food and other items)" {{ old('category')=='Retail shops (food and other items)' ? 'selected' : '' }}>Retail Shop</option>
                                    <option value="Offices"                          {{ old('category')=='Offices' ? 'selected' : '' }}>Offices</option>
                                    <option value="Hotel"                            {{ old('category')=='Hotels' ? 'selected' : '' }}>Hotel</option>
                                    <option value="Hospital (Domestic waste)"        {{ old('category')=='Hospital (Domestic waste)' ? 'selected' : '' }}>Hospital / Health Centre</option>
                                    <option value="Markets"                          {{ old('category')=='Markets' ? 'selected' : '' }}>Market</option>
                                    <option value="Industries (Light waste)"         {{ old('category')=='Industries (Light waste)' ? 'selected' : '' }}>Industry</option>
                                    <option value="Other"                            {{ old('category')=='Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        {{-- Location --}}
                        <p class="section-label mt-4"><i class="bi bi-geo-alt me-2"></i>Your Location</p>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Address / Street <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Street address or landmark" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Region <span class="text-danger">*</span></label>
                                <select name="region" id="regionSelect" class="form-select" required>
                                    <option value="">Select Region</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region }}" {{ old('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">District</label>
                                <input type="text" name="district" class="form-control" value="{{ old('district') }}" placeholder="e.g. Arusha DC">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ward</label>
                                <input type="text" name="ward" class="form-control" value="{{ old('ward') }}" placeholder="e.g. Kimundo">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Street</label>
                                <input type="text" name="street" class="form-control" value="{{ old('street') }}" placeholder="e.g. Sokoni Street">
                            </div>
                        </div>

                        {{-- Notes --}}

                        {{-- Route / Contractor selection --}}
                        <p class="section-label mt-4"><i class="bi bi-signpost-split me-2"></i>Select Your Collection Route</p>
                        <div class="mb-3">
                            <div id="routeLoadingSpinner" class="text-center py-2">
                                <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                <span class="ms-2 text-muted small">Loading routes…</span>
                            </div>
                            <div id="routeWrapper">
                                <p class="text-muted small"><i class="bi bi-info-circle me-1"></i>Select your region above to see available collection routes in your area.</p>
                            </div>
                            <input type="hidden" name="route_id" id="route_id" value="{{ old('route_id') }}">
                            @error('route_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Notes --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Additional Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions or information for your contractor…">{{ old('notes') }}</textarea>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-shield-check me-2"></i>
                            After submitting, your contractor will review your registration. You will receive an email with your login credentials once approved. <strong>You cannot log in until approved.</strong>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-teal">
                                <i class="bi bi-send me-2"></i>Submit Registration
                            </button>
                            <a href="{{ route('client.login') }}" class="btn btn-outline-secondary">Already have an account? Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
