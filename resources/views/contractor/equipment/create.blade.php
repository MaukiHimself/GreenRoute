<x-dashboard-layout title="Add Equipment">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contractor.equipment.index') }}">Equipment</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('contractor.equipment.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0 fw-semibold">Equipment Details</h5>
                                        <p class="text-muted small mb-0">Fill in the details of the equipment or container.</p>
                                    </div>
                                    <span class="badge bg-light text-dark"><i class="bi bi-tools me-1"></i>New Listing</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Equipment Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="e.g. 240L Waste Bin" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" name="category">
                                    <option value="">Select Category</option>
                                    <option value="waste_bins" {{ old('category') === 'waste_bins' ? 'selected' : '' }}>Waste Bins</option>
                                    <option value="recycling_containers" {{ old('category') === 'recycling_containers' ? 'selected' : '' }}>Recycling Containers</option>
                                    <option value="dumpsters" {{ old('category') === 'dumpsters' ? 'selected' : '' }}>Dumpsters</option>
                                    <option value="compactors" {{ old('category') === 'compactors' ? 'selected' : '' }}>Compactors</option>
                                    <option value="specialized" {{ old('category') === 'specialized' ? 'selected' : '' }}>Specialized Containers</option>
                                </select>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price (TZS)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" placeholder="25000">
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Unit</label>
                                <input type="text" class="form-control @error('unit') is-invalid @enderror" name="unit" value="{{ old('unit') }}" placeholder="e.g. per unit / per month">
                                @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Describe this equipment, suitable use cases, features...">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Specifications</label>
                                <textarea class="form-control @error('specifications') is-invalid @enderror" name="specifications" rows="3" placeholder="Size, dimensions, capacity, material, durability notes...">{{ old('specifications') }}</textarea>
                                @error('specifications')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Max 2MB, JPG/PNG/WebP</div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center mt-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_available" id="is_available" checked>
                                    <label class="form-check-label fw-semibold" for="is_available">Available for Clients</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-2 border-top">
                            <a href="{{ route('contractor.equipment.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-2"></i>Save Equipment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
