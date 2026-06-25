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
        
        <h1 class="page-title">Add New Billing Rate</h1>
        <p class="page-description">Set collection fees based on GreenRoute by-laws pricing structure</p>

        <div class="info-box">
            <p><i class="bi bi-info-circle me-2"></i>
                Create billing rates for different client categories and locations. Categories are based on the official GreenRoute by-laws with default prices shown in TZS. You can adjust prices according to updates from the field.
            </p>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.billing.rates.store') }}" method="POST">
                @csrf

                <!-- Rate Information -->
                <div class="form-section">
                    <h3><i class="bi bi-cash-coin me-2"></i>Rate Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Client Category <span class="required">*</span></label>
                            <select name="category" class="form-control" required>
                                <option value="">Select Category</option>
                                <optgroup label="Residential">
                                    <option value="Residential (Unplanned)" {{ old('category') == 'Residential (Unplanned)' ? 'selected' : '' }}>Residential (Unplanned) - 10,000 TZS</option>
                                    <option value="Residential (Planned/Modern)" {{ old('category') == 'Residential (Planned/Modern)' ? 'selected' : '' }}>Residential (Planned/Modern) - 20,000 TZS</option>
                                </optgroup>
                                <optgroup label="Commercial Residential">
                                    <option value="Commercial Residential (Apartment)" {{ old('category') == 'Commercial Residential (Apartment)' ? 'selected' : '' }}>Commercial Residential (Apartment) - 30,000 TZS</option>
                                    <option value="Commercial Residential Storey" {{ old('category') == 'Commercial Residential Storey' ? 'selected' : '' }}>Commercial Residential Storey - 80,000 TZS</option>
                                    <option value="Commercial Residential above 2 storey" {{ old('category') == 'Commercial Residential above 2 storey' ? 'selected' : '' }}>Commercial Residential above 2 storey - 100,000 TZS</option>
                                    <option value="Commercial Industrial & Institutions" {{ old('category') == 'Commercial Industrial & Institutions' ? 'selected' : '' }}>Commercial Industrial & Institutions - 150,000 TZS</option>
                                </optgroup>
                                <optgroup label="Food & Beverage">
                                    <option value="Tea Room" {{ old('category') == 'Tea Room' ? 'selected' : '' }}>Tea Room - 10,000 TZS</option>
                                    <option value="Café" {{ old('category') == 'Café' ? 'selected' : '' }}>Café - 10,000 TZS</option>
                                    <option value="Ice Par Lour" {{ old('category') == 'Ice Par Lour' ? 'selected' : '' }}>Ice Par Lour - 10,000 TZS</option>
                                    <option value="Restaurant" {{ old('category') == 'Restaurant' ? 'selected' : '' }}>Restaurant - 15,000 TZS</option>
                                    <option value="Bar" {{ old('category') == 'Bar' ? 'selected' : '' }}>Bar - 15,000 TZS</option>
                                    <option value="Butcher" {{ old('category') == 'Butcher' ? 'selected' : '' }}>Butcher - 10,000 TZS</option>
                                </optgroup>
                                <optgroup label="Accommodation">
                                    <option value="Guest House" {{ old('category') == 'Guest House' ? 'selected' : '' }}>Guest House - 10,000 TZS</option>
                                    <option value="Hotels" {{ old('category') == 'Hotels' ? 'selected' : '' }}>Hotels - 150,000 TZS</option>
                                </optgroup>
                                <optgroup label="Healthcare">
                                    <option value="Dispensary (domestic waste)" {{ old('category') == 'Dispensary (domestic waste)' ? 'selected' : '' }}>Dispensary (domestic waste) - 15,000 TZS</option>
                                    <option value="Health Centre (Domestic waste)" {{ old('category') == 'Health Centre (Domestic waste)' ? 'selected' : '' }}>Health Centre (Domestic waste) - 20,000 TZS</option>
                                    <option value="Hospital (Domestic waste)" {{ old('category') == 'Hospital (Domestic waste)' ? 'selected' : '' }}>Hospital (Domestic waste) - 35,000 TZS</option>
                                </optgroup>
                                <optgroup label="Manufacturing & Workshops">
                                    <option value="Sawing mills" {{ old('category') == 'Sawing mills' ? 'selected' : '' }}>Sawing mills - 35,000 TZS</option>
                                    <option value="Furniture making" {{ old('category') == 'Furniture making' ? 'selected' : '' }}>Furniture making - 22,000 TZS</option>
                                    <option value="Metal workshops" {{ old('category') == 'Metal workshops' ? 'selected' : '' }}>Metal workshops - 22,000 TZS</option>
                                </optgroup>
                                <optgroup label="Industries">
                                    <option value="Industries (Light waste)" {{ old('category') == 'Industries (Light waste)' ? 'selected' : '' }}>Industries (Light waste) - 35,000 TZS</option>
                                    <option value="Industries (Heavy Industries)" {{ old('category') == 'Industries (Heavy Industries)' ? 'selected' : '' }}>Industries (Heavy Industries) - 40,000 TZS</option>
                                </optgroup>
                                <optgroup label="Retail & Shops">
                                    <option value="Wholesale shops (general)" {{ old('category') == 'Wholesale shops (general)' ? 'selected' : '' }}>Wholesale shops (general) - 15,000 TZS</option>
                                    <option value="Retail shops (food and other items)" {{ old('category') == 'Retail shops (food and other items)' ? 'selected' : '' }}>Retail shops (food and other items) - 10,000 TZS</option>
                                    <option value="Retail shops (other commodities)" {{ old('category') == 'Retail shops (other commodities)' ? 'selected' : '' }}>Retail shops (other commodities) - 10,000 TZS</option>
                                    <option value="Groceries" {{ old('category') == 'Groceries' ? 'selected' : '' }}>Groceries - 10,000 TZS</option>
                                    <option value="Pharmacy" {{ old('category') == 'Pharmacy' ? 'selected' : '' }}>Pharmacy - 15,000 TZS</option>
                                </optgroup>
                                <optgroup label="Education">
                                    <option value="Private Day Primary School" {{ old('category') == 'Private Day Primary School' ? 'selected' : '' }}>Private Day Primary School - 10,000 TZS</option>
                                    <option value="Private Boarding Secondary schools (Standard)" {{ old('category') == 'Private Boarding Secondary schools (Standard)' ? 'selected' : '' }}>Private Boarding Secondary schools (Standard) - 15,000 TZS</option>
                                    <option value="Private Day Secondary schools" {{ old('category') == 'Private Day Secondary schools' ? 'selected' : '' }}>Private Day Secondary schools - 10,000 TZS</option>
                                    <option value="Private Boarding Secondary schools (Full Service)" {{ old('category') == 'Private Boarding Secondary schools (Full Service)' ? 'selected' : '' }}>Private Boarding Secondary schools (Full Service) - 25,000 TZS</option>
                                    <option value="Institution per month" {{ old('category') == 'Institution per month' ? 'selected' : '' }}>Institution per month - 25,000 TZS</option>
                                </optgroup>
                                <optgroup label="Markets & Vendors">
                                    <option value="Markets" {{ old('category') == 'Markets' ? 'selected' : '' }}>Markets - 50,000 TZS</option>
                                    <option value="Street Market (Magenge) per table" {{ old('category') == 'Street Market (Magenge) per table' ? 'selected' : '' }}>Street Market (Magenge) per table - 2,000 TZS</option>
                                    <option value="Food vendors (Mama ntilie)" {{ old('category') == 'Food vendors (Mama ntilie)' ? 'selected' : '' }}>Food vendors (Mama ntilie) - 5,000 TZS</option>
                                </optgroup>
                                <optgroup label="Transport">
                                    <option value="Bus stations (per bus per day)" {{ old('category') == 'Bus stations (per bus per day)' ? 'selected' : '' }}>Bus stations (per bus per day) - 5,000 TZS</option>
                                    <option value="Petrol stations" {{ old('category') == 'Petrol stations' ? 'selected' : '' }}>Petrol stations - 30,000 TZS</option>
                                    <option value="Garage" {{ old('category') == 'Garage' ? 'selected' : '' }}>Garage - 10,000 TZS</option>
                                </optgroup>
                                <optgroup label="Religious & Public">
                                    <option value="Mosque/church" {{ old('category') == 'Mosque/church' ? 'selected' : '' }}>Mosque/church - 20,000 TZS</option>
                                    <option value="Offices" {{ old('category') == 'Offices' ? 'selected' : '' }}>Offices - 100,000 TZS</option>
                                </optgroup>
                                <optgroup label="Informal Sector">
                                    <option value="Informal dry cleaners, tailors" {{ old('category') == 'Informal dry cleaners, tailors' ? 'selected' : '' }}>Informal dry cleaners, tailors - 10,000 TZS</option>
                                    <option value="Informal Carpenter" {{ old('category') == 'Informal Carpenter' ? 'selected' : '' }}>Informal Carpenter - 10,000 TZS</option>
                                    <option value="Shoe makers" {{ old('category') == 'Shoe makers' ? 'selected' : '' }}>Shoe makers - 5,000 TZS</option>
                                    <option value="Electronic gadgets repair" {{ old('category') == 'Electronic gadgets repair' ? 'selected' : '' }}>Electronic gadgets repair - 10,000 TZS</option>
                                    <option value="Street Barbers" {{ old('category') == 'Street Barbers' ? 'selected' : '' }}>Street Barbers - 10,000 TZS</option>
                                    <option value="Female Saloons" {{ old('category') == 'Female Saloons' ? 'selected' : '' }}>Female Saloons - 15,000 TZS</option>
                                </optgroup>
                                <optgroup label="Storage & Construction">
                                    <option value="Warehouses" {{ old('category') == 'Warehouses' ? 'selected' : '' }}>Warehouses - 30,000 TZS</option>
                                    <option value="Construction waste per trip" {{ old('category') == 'Construction waste per trip' ? 'selected' : '' }}>Construction waste per trip - 25,000 TZS</option>
                                </optgroup>
                            </select>
                            <div class="help-text">Select from standard by-laws categories (default prices shown)</div>
                            @error('category')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Location <span class="required">*</span></label>
                            <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g., Accra, Kumasi, etc." required>
                            <div class="help-text">City, state, or service zone</div>
                            @error('location')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Collection Fee <span class="required">*</span></label>
                            <input type="number" step="0.01" min="0" name="collection_fee" class="form-control" value="{{ old('collection_fee') }}" placeholder="0.00" required>
                            <div class="help-text">Price per collection (in local currency)</div>
                            @error('collection_fee')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Service Frequency</label>
                            <select name="frequency" class="form-control">
                                <option value="">Any Frequency</option>
                                <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="bi-weekly" {{ old('frequency') == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="per-trip" {{ old('frequency') == 'per-trip' ? 'selected' : '' }}>Per Trip</option>
                            </select>
                            <div class="help-text">Billing frequency (use "Per Trip" for construction waste, etc.)</div>
                            @error('frequency')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Optional description or notes about this rate">{{ old('description') }}</textarea>
                        <div class="help-text">Any additional information about this billing rate</div>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
                        <i class="bi bi-check-circle me-2"></i>Create Billing Rate
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
