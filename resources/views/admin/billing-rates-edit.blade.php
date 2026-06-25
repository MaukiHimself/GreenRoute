<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Billing Rate - GreenRoute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #c0392b;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .back-link {
            margin-bottom: 1.5rem;
        }
        
        .back-link a {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .page-description {
            color: #666;
            margin-bottom: 2rem;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section h3 {
            font-size: 1.2rem;
            color: var(--primary-teal);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-teal);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-group label .required {
            color: #ef4444;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-teal);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .btn-submit {
            background: var(--primary-teal);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 1rem;
        }
        
        .btn-submit:hover {
            background: #044a4a;
        }
        
        .btn-cancel {
            background: #6b7280;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-cancel:hover {
            background: #4b5563;
            color: white;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .help-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
        }
        
        .checkbox-group label {
            margin: 0;
            font-weight: normal;
        }
        
        .info-box {
            background: #e6f2f2;
            border-left: 4px solid var(--primary-teal);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .info-box p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link">
            <a href="{{ route('admin.billing.rates') }}">
                <i class="bi bi-arrow-left me-2"></i>Back to Billing Rates
            </a>
        </div>
        
        <h1 class="page-title">Edit Billing Rate</h1>
        <p class="page-description">Update collection fee by category and location</p>

        <div class="info-box">
            <p><i class="bi bi-info-circle me-2"></i>
                Update billing rates for different client categories and locations. These rates will be visible to contractors and used for invoice generation.
            </p>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.billing.rates.update', $rate) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Rate Information -->
                <div class="form-section">
                    <h3><i class="bi bi-cash-coin me-2"></i>Rate Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Client Category <span class="required">*</span></label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $rate->category) }}" required readonly style="background-color: #f8f9fa;">
                            <div class="help-text">Category is set and cannot be changed - create a new rate to use a different category</div>
                            @error('category')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Location <span class="required">*</span></label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $rate->location) }}" placeholder="e.g., Accra, Kumasi, etc." required>
                            <div class="help-text">City, state, or service zone</div>
                            @error('location')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Collection Fee <span class="required">*</span></label>
                            <input type="number" step="0.01" min="0" name="collection_fee" class="form-control" value="{{ old('collection_fee', $rate->collection_fee) }}" placeholder="0.00" required>
                            <div class="help-text">Price per collection (in local currency)</div>
                            @error('collection_fee')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Service Frequency</label>
                            <select name="frequency" class="form-control">
                                <option value="">Any Frequency</option>
                                <option value="daily" {{ old('frequency', $rate->frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('frequency', $rate->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="bi-weekly" {{ old('frequency', $rate->frequency) == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                <option value="monthly" {{ old('frequency', $rate->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="per-trip" {{ old('frequency', $rate->frequency) == 'per-trip' ? 'selected' : '' }}>Per Trip</option>
                            </select>
                            <div class="help-text">Billing frequency (use "Per Trip" for construction waste, etc.)</div>
                            @error('frequency')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Optional description or notes about this rate">{{ old('description', $rate->description) }}</textarea>
                        <div class="help-text">Any additional information about this billing rate</div>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $rate->is_active) ? 'checked' : '' }}>
                            <label for="is_active">
                                <strong>Active Rate</strong>
                                <span style="display: block; font-size: 0.875rem; color: #666;">
                                    Contractors and clients can see this rate
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-section">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle me-2"></i>Update Billing Rate
                    </button>
                    <a href="{{ route('admin.billing.rates') }}" class="btn-cancel">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
