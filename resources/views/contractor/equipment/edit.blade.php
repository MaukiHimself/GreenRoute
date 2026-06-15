<x-dashboard-layout title="Edit Equipment">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contractor.equipment.index') }}">Equipment</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('contractor.equipment.update', $equipment) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0 fw-semibold">Edit Equipment</h5>
                                        <p class="text-muted small mb-0">Update the details of <strong>{{ $equipment->name }}</strong>.</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_available" id="is_available" {{ $equipment->is_available ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_available">Available</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Equipment Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $equipment->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" name="category">
                                    <option value="">Select Category</option>
                                    @foreach(['waste_bins' => 'Waste Bins', 'recycling_containers' => 'Recycling Containers', 'dumpsters' => 'Dumpsters', 'compactors' => 'Compactors', 'specialized' => 'Specialized Containers'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('category', $equipment->category) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price (TZS)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $equipment->price) }}" placeholder="25000">
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Unit</label>
                                <input type="text" class="form-control @error('unit') is-invalid @enderror" name="unit" value="{{ old('unit', $equipment->unit) }}" placeholder="e.g. per unit / per month">
                                @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Describe this equipment...">{{ old('description', $equipment->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Specifications</label>
                                <textarea class="form-control @error('specifications') is-invalid @enderror" name="specifications" rows="3" placeholder="Size, capacity, material...">{{ old('specifications', $equipment->specifications) }}</textarea>
                                @error('specifications')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Replace Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">
                                @if($equipment->image)
                                    <img src="{{ asset('storage/' . $equipment->image) }}" class="rounded mt-2" width="80" height="80" style="object-fit: cover;" alt="">
                                @endif
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between pt-2 border-top">
                            <form method="POST" action="{{ route('contractor.equipment.destroy', $equipment) }}"
                                  onsubmit="return confirm('Delete this equipment permanently?');" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                            <div class="d-flex gap-3">
                                <a href="{{ route('contractor.equipment.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check2 me-2"></i>Update Equipment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
