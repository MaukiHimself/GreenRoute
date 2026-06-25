@extends('layouts.contractor-sidebar')

@section('title', 'Create Collection Schedule')

@section('styles')
<style>
    :root {
        --primary-teal: #055c5c;
        --primary-red: #c0392b;
        --white: #ffffff;
    }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-teal) 0%, #077777 100%);
            color: var(--white);
            padding: 2rem;
            border-radius: 12px 12px 0 0;
            margin-bottom: 0;
        }
        
        .form-container {
            background: var(--white);
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        
        .form-label {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .required-star {
            color: var(--primary-red);
            font-weight: bold;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
            outline: none;
        }
        
        .btn-primary-custom {
            background: var(--primary-teal);
            color: var(--white);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-primary-custom:hover {
            background: #044a4a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 92, 92, 0.3);
        }
        
        .btn-secondary-custom {
            background: var(--white);
            color: var(--primary-red);
            border: 2px solid var(--primary-red);
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-secondary-custom:hover {
            background: var(--primary-red);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(100, 4, 4, 0.3);
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <h1 class="mb-0" style="font-size: 1.75rem; font-weight: 700;">
                    <i class="bi bi-calendar-plus me-2"></i>Create Collection Schedule
                </h1>
            </div>
            <p class="mb-0" style="opacity: 0.95;">Create a bulk schedule for clients at a specific location</p>

                <!-- Form Container -->
                <div class="form-container">
                    <form method="POST" action="{{ route('schedules.store') }}">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="route_name" class="form-label">
                                    Route Name <span class="required-star">*</span>
                                </label>
                                <input type="text" class="form-control @error('route_name') is-invalid @enderror" 
                                       id="route_name" name="route_name" value="{{ old('route_name') }}" 
                                       list="route_names_list" placeholder="Select or type route name..." required>
                                <datalist id="route_names_list">
                                    @foreach($routeNames as $routeName)
                                        <option value="{{ $routeName }}">{{ $routeName }}</option>
                                    @endforeach
                                </datalist>
                                @error('route_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="site_location" class="form-label">
                                    Site Location <span class="required-star">*</span>
                                </label>
                                <input type="text" class="form-control @error('site_location') is-invalid @enderror" 
                                       id="site_location" name="site_location" value="{{ old('site_location') }}" 
                                       list="site_locations_list" placeholder="Select or type location..." required>
                                <datalist id="site_locations_list">
                                    @foreach($siteLocations as $region => $districts)
                                        @foreach($districts as $district)
                                            <option value="{{ $region }} → {{ $district }}">{{ $region }} → {{ $district }}</option>
                                        @endforeach
                                    @endforeach
                                </datalist>
                                @error('site_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="start_date" class="form-label">
                                    Start Date <span class="required-star">*</span>
                                </label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end_date" class="form-label">
                                    End Date <span class="required-star">*</span>
                                </label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pickup_time" class="form-label">
                                    Pickup Time <span class="required-star">*</span>
                                </label>
                                <input type="time" class="form-control @error('pickup_time') is-invalid @enderror" 
                                       id="pickup_time" name="pickup_time" value="{{ old('pickup_time', '09:00') }}" required>
                                @error('pickup_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea class="form-control" id="comments" name="comments" rows="3">{{ old('comments') }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('schedules.index') }}" class="btn-secondary-custom">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-check-circle me-1"></i> Create Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Enhanced autocomplete functionality
        document.addEventListener('DOMContentLoaded', function() {
            const routeNameInput = document.getElementById('route_name');
            const siteLocationInput = document.getElementById('site_location');
            const routeDatalist = document.getElementById('route_names_list');
            const locationDatalist = document.getElementById('site_locations_list');
            
            // Validate if selected value exists in datalist
            function validateDatalistInput(input, datalist) {
                const value = input.value.trim();
                if (value === '') return;
                
                const options = Array.from(datalist.options).map(opt => opt.value);
                const isValid = options.includes(value);
                
                if (isValid) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    // Don't mark as invalid while typing
                }
            }
            
            // Add validation on blur
            routeNameInput.addEventListener('blur', function() {
                validateDatalistInput(this, routeDatalist);
            });
            
            siteLocationInput.addEventListener('blur', function() {
                validateDatalistInput(this, locationDatalist);
            });
            
            // Add click event to show all options when input is focused
            routeNameInput.addEventListener('focus', function() {
                this.click();
            });
            
            siteLocationInput.addEventListener('focus', function() {
                this.click();
            });
            
            // Clear validation classes when typing
            routeNameInput.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    validateDatalistInput(this, routeDatalist);
                } else {
                    this.classList.remove('is-valid', 'is-invalid');
                }
            });
            
            siteLocationInput.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    validateDatalistInput(this, locationDatalist);
                } else {
                    this.classList.remove('is-valid', 'is-invalid');
                }
            });
        });
    </script>
@endsection

@push('scripts')
@endpush
