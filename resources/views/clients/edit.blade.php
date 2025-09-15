<x-dashboard-layout title="Edit Client">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard.contractor') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('clients.index') }}">
                    <i class="bi bi-people me-2"></i>Clients
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </x-slot>

    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Client</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-1"></i> View
                    </a>
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('clients.update', $client) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $client->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $client->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $client->phone) }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active" {{ old('status', $client->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $client->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Street Address *</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $client->address) }}" required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $client->city) }}" required>
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="state" class="form-label">State *</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', $client->state) }}" required>
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="zip_code" class="form-label">ZIP Code *</label>
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code" value="{{ old('zip_code', $client->zip_code) }}" required>
                            @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Optional notes about this client">{{ old('notes', $client->notes) }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('clients.show', $client) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-layout>