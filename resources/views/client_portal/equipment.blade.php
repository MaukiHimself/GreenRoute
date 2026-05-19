<x-dashboard-layout title="Waste Storage Equipment">
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
                <a class="nav-link active" href="{{ route('client.equipment') }}">
                    <i class="bi bi-tools me-2"></i>Equipment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('client.contractor.info') }}">
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
        <li class="breadcrumb-item active">Equipment</li>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Available Waste Storage Equipment</h5>
                </div>
                <div class="card-body">
                    @if($products->count() > 0)
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $product->name ?? $product->username ?? 'Equipment item' }}</h6>
                                            @if(!empty($product->description))
                                            <p class="card-text text-muted">{{ $product->description }}</p>
                                            @endif
                                            
                                            @if($product->price)
                                                <div class="mb-2">
                                                    <strong class="text-success">TZS {{ number_format($product->price, 2) }}</strong>
                                                    @if($product->unit)
                                                        <small class="text-muted">per {{ $product->unit }}</small>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            @if($product->specifications)
                                                <div class="mb-2">
                                                    <small class="text-muted">{{ $product->specifications }}</small>
                                                </div>
                                            @endif
                                            
                                            <button class="btn btn-outline-primary btn-sm" onclick="requestEquipment('{{ $product->name }}')">
                                                <i class="bi bi-envelope me-1"></i>Request Info
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-tools display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">No Equipment Available</h5>
                            <p class="text-muted">Contact your contractor for equipment options.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Equipment Request</h5>
                </div>
                <div class="card-body">
                    <form id="equipmentRequestForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Equipment Type</label>
                                    <select class="form-select" name="equipment_type" required>
                                        <option value="">Select Equipment Type</option>
                                        <option value="waste_bin">Waste Bins</option>
                                        <option value="recycling_container">Recycling Containers</option>
                                        <option value="dumpster">Dumpsters</option>
                                        <option value="compactor">Compactors</option>
                                        <option value="specialized_container">Specialized Containers</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Quantity Needed</label>
                                    <input type="number" class="form-control" name="quantity" min="1" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Additional Requirements</label>
                            <textarea class="form-control" name="requirements" rows="3" placeholder="Specify size, capacity, special features, or any other requirements..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Submit Equipment Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function requestEquipment(productName) {
            document.querySelector('select[name="equipment_type"]').value = 'specialized_container';
            document.querySelector('textarea[name="requirements"]').value = 'Requesting information about: ' + productName;
            document.querySelector('textarea[name="requirements"]').focus();
        }
        
        document.getElementById('equipmentRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would normally send the request to the server
            // For now, we'll show a success message
            alert('Equipment request submitted successfully! Your contractor will contact you within 24 hours.');
            this.reset();
        });
    </script>
</x-dashboard-layout>