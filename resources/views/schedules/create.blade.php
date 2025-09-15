<x-dashboard-layout title="Create Schedule">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.contractor') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('clients.index') }}"><i class="bi bi-people me-2"></i>Clients</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('schedules.index') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Schedules</a></li>
        <li class="breadcrumb-item active">Create</li>
    </x-slot>

    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Create New Schedule</h4>
                <a href="{{ route('schedules.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="client_id" class="form-label">Client *</label>
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                <option value="">Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} - {{ $client->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="pickup_date" class="form-label">Pickup Date *</label>
                            <input type="date" name="pickup_date" id="pickup_date" value="{{ old('pickup_date') }}" class="form-control @error('pickup_date') is-invalid @enderror" min="{{ date('Y-m-d') }}" required>
                            @error('pickup_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="pickup_time" class="form-label">Pickup Time *</label>
                            <input type="time" name="pickup_time" id="pickup_time" value="{{ old('pickup_time') }}" class="form-control @error('pickup_time') is-invalid @enderror" required>
                            @error('pickup_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="service_type" class="form-label">Service Type *</label>
                            <select name="service_type" id="service_type" class="form-select @error('service_type') is-invalid @enderror" required>
                                <option value="">Select service type</option>
                                <option value="collection" {{ old('service_type') == 'collection' ? 'selected' : '' }}>Collection</option>
                                <option value="disposal" {{ old('service_type') == 'disposal' ? 'selected' : '' }}>Disposal</option>
                                <option value="both" {{ old('service_type') == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                            @error('service_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label for="pickup_location" class="form-label">Pickup Location *</label>
                            <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location') }}" placeholder="e.g., Front yard, Garage, Loading dock" class="form-control @error('pickup_location') is-invalid @enderror" required>
                            @error('pickup_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="pickup_address" class="form-label">Street Address *</label>
                            <input type="text" name="pickup_address" id="pickup_address" value="{{ old('pickup_address') }}" placeholder="123 Main Street" class="form-control @error('pickup_address') is-invalid @enderror" required>
                            @error('pickup_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror" required>
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="state" class="form-label">State *</label>
                            <input type="text" name="state" id="state" value="{{ old('state') }}" class="form-control @error('state') is-invalid @enderror" required>
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="zip_code" class="form-label">ZIP Code *</label>
                            <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" class="form-control @error('zip_code') is-invalid @enderror" required>
                            @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Special instructions, access codes, etc.">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> Create Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>