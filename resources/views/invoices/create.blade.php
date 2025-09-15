<x-dashboard-layout title="Create Invoice">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.contractor') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('clients.index') }}"><i class="bi bi-people me-2"></i>Clients</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('schedules.index') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('invoices.index') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
        <li class="breadcrumb-item active">Create</li>
    </x-slot>

    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Create New Invoice</h4>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="client_id" class="form-label">Client *</label>
                            <select name="client_id" id="client_id" class="form-select" required>
                                <option value="">Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }} - {{ $client->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="schedule_id" class="form-label">Related Schedule</label>
                            <select name="schedule_id" id="schedule_id" class="form-select">
                                <option value="">No related schedule</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->client->name }} - {{ $schedule->pickup_date->format('M d, Y') }} ({{ $schedule->service_type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="invoice_date" class="form-label">Invoice Date *</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="due_date" class="form-label">Due Date *</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="service_type" class="form-label">Service Type *</label>
                            <select name="service_type" id="service_type" class="form-select" required>
                                <option value="">Select service type</option>
                                <option value="Waste Collection" {{ old('service_type') == 'Waste Collection' ? 'selected' : '' }}>Waste Collection</option>
                                <option value="Recycling" {{ old('service_type') == 'Recycling' ? 'selected' : '' }}>Recycling</option>
                                <option value="Hazardous Waste" {{ old('service_type') == 'Hazardous Waste' ? 'selected' : '' }}>Hazardous Waste</option>
                                <option value="Bulk Pickup" {{ old('service_type') == 'Bulk Pickup' ? 'selected' : '' }}>Bulk Pickup</option>
                                <option value="Other" {{ old('service_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="subtotal" class="form-label">Subtotal ($) *</label>
                            <input type="number" name="subtotal" id="subtotal" step="0.01" min="0" value="{{ old('subtotal') }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tax_rate" class="form-label">Tax Rate (%) *</label>
                            <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100" value="{{ old('tax_rate', '0') }}" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control" placeholder="Detailed description of services provided...">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Additional notes or payment terms...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal:</span><span id="display-subtotal">$0.00</span></div>
                                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Tax:</span><span id="display-tax">$0.00</span></div>
                                <div class="border-top pt-2 d-flex justify-content-between"><span class="fw-semibold">Total:</span><span id="display-total" class="fw-bold">$0.00</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Create Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function calculateTotals() {
        const sub = parseFloat(document.getElementById('subtotal').value) || 0;
        const rate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const tax = sub * (rate/100);
        const total = sub + tax;
        document.getElementById('display-subtotal').textContent = '$' + sub.toFixed(2);
        document.getElementById('display-tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('display-total').textContent = '$' + total.toFixed(2);
    }
    document.getElementById('subtotal').addEventListener('input', calculateTotals);
    document.getElementById('tax_rate').addEventListener('input', calculateTotals);
    calculateTotals();
    </script>
</x-dashboard-layout>