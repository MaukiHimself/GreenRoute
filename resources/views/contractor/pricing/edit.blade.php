<x-dashboard-layout title="Edit Service Price">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contractor.pricing.index') }}">Service Pricing</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('contractor.pricing.update', $price) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0 fw-semibold">Edit Service Price</h5>
                                        <p class="text-muted small mb-0">Updating: <strong>{{ \App\Models\ServicePrice::getLabel($price->service_type) }}</strong></p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ $price->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Service Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('service_type') is-invalid @enderror" name="service_type" required>
                                    @foreach(['regular_pickup' => 'Regular Pickup', 'bulk_collection' => 'Bulk Collection', 'hazardous_waste' => 'Hazardous Waste', 'recycling' => 'Recycling', 'organic_waste' => 'Organic Waste', 'construction_debris' => 'Construction Debris'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('service_type', $price->service_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('service_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Waste Type</label>
                                <select class="form-select @error('waste_type') is-invalid @enderror" name="waste_type">
                                    <option value="">All Waste Types</option>
                                    @foreach(['general' => 'General', 'recyclable' => 'Recyclable', 'organic' => 'Organic', 'electronic' => 'Electronic', 'medical' => 'Medical', 'industrial' => 'Industrial'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('waste_type', $price->waste_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('waste_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Volume Tier</label>
                                <select class="form-select @error('volume_tier') is-invalid @enderror" name="volume_tier">
                                    <option value="">Standard</option>
                                    @foreach(['small' => 'Small (1-5 bags)', 'medium' => 'Medium (6-15 bags)', 'large' => 'Large (16-30 bags)', 'extra_large' => 'Extra Large (30+ bags)', 'container' => 'Full Container'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('volume_tier', $price->volume_tier) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('volume_tier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" name="category">
                                    <option value="">All Categories</option>
                                    @foreach(['residential' => 'Residential', 'commercial' => 'Commercial', 'industrial' => 'Industrial'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('category', $price->category) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price (TZS) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $price->price) }}" required>
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Short Description</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description', $price->description) }}">
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">What's Included</label>
                                <textarea class="form-control @error('includes') is-invalid @enderror" name="includes" rows="3">{{ old('includes', $price->includes) }}</textarea>
                                @error('includes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top mt-4">
                            <form method="POST" action="{{ route('contractor.pricing.destroy', $price) }}"
                                  onsubmit="return confirm('Remove this price entry permanently?');" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                            <div class="d-flex gap-3">
                                <a href="{{ route('contractor.pricing.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check2 me-2"></i>Update Price
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
