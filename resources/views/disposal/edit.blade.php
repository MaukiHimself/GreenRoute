<x-guest-layout>
    <div class="container py-4">
        <div class="card">
            <div class="card-header">
                <h5 class="text-success mb-0">Record Disposal Data - {{ $schedule->pickup_location }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-success">Collection Information</h6>
                        <p><strong>Route:</strong> {{ $schedule->pickup_location }}</p>
                        <p><strong>Collection Date:</strong> {{ $schedule->pickup_date->format('M d, Y') }}</p>
                        <p><strong>Site Location:</strong> {{ $schedule->pickup_address }}</p>
                        <p><strong>Client:</strong> {{ $schedule->client->name }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('disposal.update', $schedule) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_volume" class="form-label">Total Volume Collected (m³) *</label>
                            <input type="number" class="form-control @error('total_volume') is-invalid @enderror" 
                                   id="total_volume" name="total_volume" step="0.01" 
                                   value="{{ old('total_volume', $schedule->total_volume) }}" required>
                            @error('total_volume')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="disposal_type" class="form-label">Disposal Type *</label>
                            <select class="form-select @error('disposal_type') is-invalid @enderror" id="disposal_type" name="disposal_type" required>
                                <option value="">Select Disposal Type</option>
                                <option value="sorting_facility" {{ old('disposal_type', $schedule->disposal_type) == 'sorting_facility' ? 'selected' : '' }}>Sorting Facility</option>
                                <option value="landfill" {{ old('disposal_type', $schedule->disposal_type) == 'landfill' ? 'selected' : '' }}>Landfill</option>
                            </select>
                            @error('disposal_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="disposal_site" class="form-label">Disposal Site Location *</label>
                        <input type="text" class="form-control @error('disposal_site') is-invalid @enderror" 
                               id="disposal_site" name="disposal_site" 
                               value="{{ old('disposal_site', $schedule->disposal_site) }}" 
                               placeholder="Enter the name/location of disposal site" required>
                        @error('disposal_site')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="disposal_notes" class="form-label">Disposal Notes</label>
                        <textarea class="form-control" id="disposal_notes" name="disposal_notes" rows="3" 
                                  placeholder="Additional notes about the disposal process">{{ old('disposal_notes', $schedule->disposal_notes) }}</textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Record Disposal Data
                        </button>
                        <a href="{{ route('disposal.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>