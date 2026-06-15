<x-dashboard-layout title="Add Service Price">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contractor.pricing.index') }}">Service Pricing</a></li>
        <li class="breadcrumb-item active">Add Price</li>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('contractor.pricing.store') }}">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0 fw-semibold">Service Price Details</h5>
                                        <p class="text-muted small mb-0">Define the price for a specific service offering.</p>
                                    </div>
                                    <span class="badge bg-light text-dark"><i class="bi bi-tag me-1"></i>New Price</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Service Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('service_type') is-invalid @enderror" name="service_type" required>
                                    <option value="">Select Service Type</option>
                                    <option value="regular_pickup" {{ old('service_type') === 'regular_pickup' ? 'selected' : '' }}>Regular Pickup</option>
                                    <option value="bulk_collection" {{ old('service_type') === 'bulk_collection' ? 'selected' : '' }}>Bulk Collection</option>
                                    <option value="hazardous_waste" {{ old('service_type') === 'hazardous_waste' ? 'selected' : '' }}>Hazardous Waste</option>
                                    <option value="recycling" {{ old('service_type') === 'recycling' ? 'selected' : '' }}>Recycling</option>
                                    <option value="organic_waste" {{ old('service_type') === 'organic_waste' ? 'selected' : '' }}>Organic Waste</option>
                                    <option value="construction_debris" {{ old('service_type') === 'construction_debris' ? 'selected' : '' }}>Construction Debris</option>
                                </select>
                                @error('service_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Waste Type</label>
                                <select class="form-select @error('waste_type') is-invalid @enderror" name="waste_type">
                                    <option value="">All Waste Types</option>
                                    <option value="general" {{ old('waste_type') === 'general' ? 'selected' : '' }}>General Waste</option>
                                    <option value="recyclable" {{ old('waste_type') === 'recyclable' ? 'selected' : '' }}>Recyclable</option>
                                    <option value="organic" {{ old('waste_type') === 'organic' ? 'selected' : '' }}>Organic</option>
                                    <option value="electronic" {{ old('waste_type') === 'electronic' ? 'selected' : '' }}>Electronic</option>
                                    <option value="medical" {{ old('waste_type') === 'medical' ? 'selected' : '' }}>Medical</option>
                                    <option value="industrial" {{ old('waste_type') === 'industrial' ? 'selected' : '' }}>Industrial</option>
                                </select>
                                @error('waste_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Volume Tier</label>
                                <select class="form-select @error('volume_tier') is-invalid @enderror" name="volume_tier">
                                    <option value="">Standard</option>
                                    <option value="small" {{ old('volume_tier') === 'small' ? 'selected' : '' }}>Small (1-5 bags)</option>
                                    <option value="medium" {{ old('volume_tier') === 'medium' ? 'selected' : '' }}>Medium (6-15 bags)</option>
                                    <option value="large" {{ old('volume_tier') === 'large' ? 'selected' : '' }}>Large (16-30 bags)</option>
                                    <option value="extra_large" {{ old('volume_tier') === 'extra_large' ? 'selected' : '' }}>Extra Large (30+ bags)</option>
                                    <option value="container" {{ old('volume_tier') === 'container' ? 'selected' : '' }}>Full Container</option>
                                </select>
                                @error('volume_tier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" name="category">
                                    <option value="">All Categories</option>
                                    <option value="residential" {{ old('category') === 'residential' ? 'selected' : '' }}>Residential</option>
                                    <option value="commercial" {{ old('category') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="industrial" {{ old('category') === 'industrial' ? 'selected' : '' }}>Industrial</option>
                                </select>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price (TZS) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" placeholder="25000" required>
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Short Description</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" placeholder="e.g. One-time pickup, up to 5 bags">
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">What's Included</label>
                                <textarea class="form-control @error('includes') is-invalid @enderror" name="includes" rows="3" placeholder="List what's included: bin provided, labor, disposal fees, etc.">{{ old('includes') }}</textarea>
                                @error('includes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                    <label class="form-check-label fw-semibold" for="is_active">Active — visible to clients</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top mt-4">
                            <a href="{{ route('contractor.pricing.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-2"></i>Save Price
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
