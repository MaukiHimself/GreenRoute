<x-dashboard-layout title="Request Service">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.dashboard') }}">
                    <i class="bi bi-house me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.schedules') }}">
                    <i class="bi bi-calendar3 me-2"></i>Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('client.request.service') }}">
                    <i class="bi bi-plus-circle me-2"></i>Request Service
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.invoices') }}">
                    <i class="bi bi-receipt me-2"></i>Invoices
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.support') }}">
                    <i class="bi bi-headset me-2"></i>Support/Help
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Request Service</li>
    </x-slot>

    @if($servicePrices->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-semibold"><i class="bi bi-tag me-2 text-primary"></i>Service Price List</h5>
                            <p class="text-muted small mb-0">Transparent pricing from your contractor. Select a service to request.</p>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Service Type</th>
                                        <th>Volume</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th class="text-end pe-4">Price (TZS)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($servicePrices as $price)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold">{{ \App\Models\ServicePrice::getLabel($price->service_type) }}</div>
                                            </td>
                                            <td>{{ $price->volume_tier ? \App\Models\ServicePrice::getVolumeLabel($price->volume_tier) : 'Standard' }}</td>
                                            <td><span class="badge bg-light text-dark">{{ $price->category ? ucfirst($price->category) : 'All' }}</span></td>
                                            <td class="small text-muted">{{ $price->description ?: ($price->includes ? \Illuminate\Support\Str::limit($price->includes, 50) : '—') }}</td>
                                            <td class="text-end pe-4 fw-bold text-success">
                                                TZS {{ number_format($price->price, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Request Custom Waste Collection Service</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('client.request.service.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Service Type</label>
                                    <select class="form-select" name="service_type" required>
                                        <option value="">Select Service Type</option>
                                        <option value="regular_pickup">Regular Pickup</option>
                                        <option value="bulk_collection">Bulk Collection</option>
                                        <option value="hazardous_waste">Hazardous Waste</option>
                                        <option value="recycling">Recycling</option>
                                        <option value="organic_waste">Organic Waste</option>
                                        <option value="construction_debris">Construction Debris</option>
                                    </select>
                                    @if($servicePrices->count() > 0)
                                        <div class="form-text">
                                            @php
                                                $matchedPrice = $servicePrices->first();
                                            @endphp
                                            Starting from <strong>TZS {{ number_format($matchedPrice->price, 2) }}</strong> — check table above
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Waste Type</label>
                                    <select class="form-select" name="waste_type" required>
                                        <option value="">Select Waste Type</option>
                                        <option value="general">General Waste</option>
                                        <option value="recyclable">Recyclable Materials</option>
                                        <option value="organic">Organic/Compostable</option>
                                        <option value="electronic">Electronic Waste</option>
                                        <option value="medical">Medical Waste</option>
                                        <option value="industrial">Industrial Waste</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Preferred Pickup Date</label>
                                    <input type="date" class="form-control" name="pickup_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Preferred Time</label>
                                    <select class="form-select" name="pickup_time" required>
                                        <option value="">Select Time Slot</option>
                                        <option value="08:00-10:00">8:00 AM - 10:00 AM</option>
                                        <option value="10:00-12:00">10:00 AM - 12:00 PM</option>
                                        <option value="12:00-14:00">12:00 PM - 2:00 PM</option>
                                        <option value="14:00-16:00">2:00 PM - 4:00 PM</option>
                                        <option value="16:00-18:00">4:00 PM - 6:00 PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimated Volume</label>
                            <select class="form-select" name="estimated_volume" required>
                                <option value="">Select Volume</option>
                                <option value="small">Small (1-5 bags)</option>
                                <option value="medium">Medium (6-15 bags)</option>
                                <option value="large">Large (16-30 bags)</option>
                                <option value="extra_large">Extra Large (30+ bags)</option>
                                <option value="container">Full Container</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Special Instructions</label>
                            <textarea class="form-control" name="special_instructions" rows="4" placeholder="Any special handling requirements, access instructions, or additional notes..."></textarea>
                        </div>

                        @if($servicePrices->count() > 0)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Estimate:</strong> Your contractor has listed prices above. The final price will be confirmed after they review your request.
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Note:</strong> Your service request will be reviewed by your assigned contractor. You will receive confirmation with pricing within 24 hours.
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Submit Request
                        </button>
                        <a href="{{ route('client.schedules') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
