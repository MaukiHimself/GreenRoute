<x-dashboard-layout title="Contractor Information">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.dashboard') }}">
                    <i class="bi bi-house me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.profile') }}">
                    <i class="bi bi-person me-2"></i>Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.schedules') }}">
                    <i class="bi bi-calendar3 me-2"></i>Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.request.service') }}">
                    <i class="bi bi-plus-circle me-2"></i>Request Service
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.equipment') }}">
                    <i class="bi bi-tools me-2"></i>Equipment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('client.contractor.info') }}">
                    <i class="bi bi-building me-2"></i>Contractor Info
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.invoices') }}">
                    <i class="bi bi-receipt me-2"></i>Invoices
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.payments') }}">
                    <i class="bi bi-credit-card me-2"></i>Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.feedback') }}">
                    <i class="bi bi-chat-dots me-2"></i>Feedback
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Contractor Info</li>
    </x-slot>

    @if($contractor)
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Your Assigned Contractor</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">Contractor Name</h6>
                                <p class="fw-semibold">{{ $contractor->name }}</p>
                                
                                <h6 class="text-muted mt-3">Email Address</h6>
                                <p>{{ $contractor->email }}</p>
                                
                                @if($contractor->contractor && $contractor->contractor->company_name)
                                    <h6 class="text-muted mt-3">Company Name</h6>
                                    <p>{{ $contractor->contractor->company_name }}</p>
                                @endif
                                
                                @if($contractor->contractor && $contractor->contractor->phone)
                                    <h6 class="text-muted mt-3">Phone Number</h6>
                                    <p>{{ $contractor->contractor->phone }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if($contractor->contractor && $contractor->contractor->address)
                                    <h6 class="text-muted">Address</h6>
                                    <p>{{ $contractor->contractor->address }}</p>
                                @endif
                                
                                @if($contractor->contractor && $contractor->contractor->license_number)
                                    <h6 class="text-muted mt-3">License Number</h6>
                                    <p>{{ $contractor->contractor->license_number }}</p>
                                @endif
                                
                                @if($contractor->contractor && $contractor->contractor->vehicle_type)
                                    <h6 class="text-muted mt-3">Vehicle Type</h6>
                                    <p>{{ $contractor->contractor->vehicle_type }}</p>
                                @endif
                                
                                @if($contractor->contractor && $contractor->contractor->license_plate)
                                    <h6 class="text-muted mt-3">Vehicle License Plate</h6>
                                    <p>{{ $contractor->contractor->license_plate }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Service Areas & Routes</h5>
                    </div>
                    <div class="card-body">
                        @if($contractor->contractor && $contractor->contractor->site_locations)
                            <h6 class="text-muted">Service Locations</h6>
                            <p>{{ $contractor->contractor->site_locations }}</p>
                        @else
                            <p class="text-muted">Service area information not available. Contact your contractor for details.</p>
                        @endif
                        
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Collection Route:</strong> Your location is included in the contractor's regular collection route. 
                            Check your schedules for specific pickup times and dates.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('client.request.service') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Request Service
                            </a>
                            <a href="{{ route('client.schedules') }}" class="btn btn-outline-primary">
                                <i class="bi bi-calendar me-2"></i>View Schedules
                            </a>
                            <a href="{{ route('client.feedback') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-chat-dots me-2"></i>Send Feedback
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Email</small>
                            <div>
                                <a href="mailto:{{ $contractor->email }}" class="text-decoration-none">
                                    <i class="bi bi-envelope me-2"></i>{{ $contractor->email }}
                                </a>
                            </div>
                        </div>
                        
                        @if($contractor->contractor && $contractor->contractor->phone)
                            <div class="mb-3">
                                <small class="text-muted">Phone</small>
                                <div>
                                    <a href="tel:{{ $contractor->contractor->phone }}" class="text-decoration-none">
                                        <i class="bi bi-telephone me-2"></i>{{ $contractor->contractor->phone }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        <div class="alert alert-warning">
                            <small>
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                For emergencies or urgent requests, please contact your contractor directly.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
                        <h5 class="mt-3">No Contractor Assigned</h5>
                        <p class="text-muted">You don't have a contractor assigned to your account yet. Please contact support for assistance.</p>
                        <a href="{{ route('client.feedback') }}" class="btn btn-primary">
                            <i class="bi bi-headset me-2"></i>Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-dashboard-layout>