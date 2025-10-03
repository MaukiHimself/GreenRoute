<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .primary-dark { color: #055c5c; }
        .primary-light { 
            background-color: rgba(5, 92, 92, 0.08);
            border-left: 4px solid #055c5c;
        }
        .accent-color { color: #640404; }
        .btn-primary-custom {
            background-color: #055c5c;
            border-color: #055c5c;
            color: white;
        }
        .btn-primary-custom:hover {
            background-color: #044a4a;
            border-color: #044a4a;
        }
        .btn-outline-custom {
            border-color: #055c5c;
            color: #055c5c;
        }
        .btn-outline-custom:hover {
            background-color: #055c5c;
            color: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #055c5c;
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.25);
        }
        .info-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            border: 1px solid rgba(5, 92, 92, 0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 primary-light rounded">
            <h4 class="primary-dark mb-0">Create Invoice</h4>
        </div>

        <!-- Invoice Form -->
        <div class="info-card">
            <form method="POST" action="{{ route('billing.store') }}">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="client_id" class="form-label">Client *</label>
                        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} - {{ $client->category }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="service_type" class="form-label">Service Type *</label>
                        <select class="form-select @error('service_type') is-invalid @enderror" id="service_type" name="service_type" required>
                            <option value="">Select Service</option>
                            <option value="waste_collection" {{ old('service_type') == 'waste_collection' ? 'selected' : '' }}>Waste Collection</option>
                            <option value="disposal" {{ old('service_type') == 'disposal' ? 'selected' : '' }}>Waste Disposal</option>
                            <option value="recycling" {{ old('service_type') == 'recycling' ? 'selected' : '' }}>Recycling</option>
                            <option value="consultation" {{ old('service_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                        </select>
                        @error('service_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="subtotal" class="form-label">Subtotal ($) *</label>
                        <input type="number" class="form-control @error('subtotal') is-invalid @enderror" id="subtotal" name="subtotal" step="0.01" value="{{ old('subtotal') }}" required>
                        @error('subtotal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                        <input type="number" class="form-control @error('tax_rate') is-invalid @enderror" id="tax_rate" name="tax_rate" step="0.01" value="{{ old('tax_rate', 0) }}">
                        @error('tax_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="due_date" class="form-label">Due Date *</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                        @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-check-circle"></i> Create Invoice
                    </button>
                    <a href="{{ route('billing.index') }}" class="btn btn-outline-custom">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>