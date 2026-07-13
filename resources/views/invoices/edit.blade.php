<x-dashboard-layout title="Edit Invoice {{ $invoice->invoice_number }}">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.contractor') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('contractor.clients.index') }}"><i class="bi bi-people me-2"></i>Clients</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('schedules.index') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('invoices.index') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a></li>
        <li class="breadcrumb-item active">Edit</li>
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
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-0">Edit Invoice</h4>
                    <small class="text-muted">{{ $invoice->invoice_number }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-secondary"><i class="bi bi-eye me-1"></i> View</a>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" value="{{ $invoice->invoice_number }}" class="form-control bg-light" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="client_id" class="form-label">Client *</label>
                            <select name="client_id" id="client_id" class="form-select" required>
                                <option value="">Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} - {{ $client->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="schedule_id" class="form-label">Related Schedule</label>
                            <select name="schedule_id" id="schedule_id" class="form-select">
                                <option value="">No related schedule</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id', $invoice->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->client->name }} - {{ $schedule->pickup_date->format('M d, Y') }} ({{ $schedule->service_type }})@if($schedule->displayed_price !== null) - TZS {{ number_format($schedule->displayed_price, 2) }}@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="invoice_date" class="form-label">Invoice Date *</label>
                            <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="due_date" class="form-label">Due Date *</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="service_type" class="form-label">Service Type *</label>
                            <select name="service_type" id="service_type" class="form-select" required>
                                <option value="">Select service type</option>
                                <option value="Waste Collection" {{ old('service_type', $invoice->service_type) == 'Waste Collection' ? 'selected' : '' }}>Waste Collection</option>
                                <option value="Recycling" {{ old('service_type', $invoice->service_type) == 'Recycling' ? 'selected' : '' }}>Recycling</option>
                                <option value="Hazardous Waste" {{ old('service_type', $invoice->service_type) == 'Hazardous Waste' ? 'selected' : '' }}>Hazardous Waste</option>
                                <option value="Bulk Pickup" {{ old('service_type', $invoice->service_type) == 'Bulk Pickup' ? 'selected' : '' }}>Bulk Pickup</option>
                                <option value="Other" {{ old('service_type', $invoice->service_type) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="subtotal" class="form-label">Amount (TZS) *</label>
                            <input type="number" name="subtotal" id="subtotal" step="0.01" min="0" value="{{ old('subtotal', $invoice->subtotal) }}" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control" placeholder="Detailed description of services provided...">{{ old('description', $invoice->description) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Additional notes or payment terms...">{{ old('notes', $invoice->notes) }}</textarea>
                        </div>
                    </div>

                    {{-- Financial summary --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100 bg-light">
                                <h6 class="fw-bold mb-3">Current Summary</h6>
                                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Current Amount</span><span>TZS {{ number_format($invoice->subtotal, 2) }}</span></div>
                                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Current Total</span><span class="fw-bold">TZS {{ number_format($invoice->total_amount, 2) }}</span></div>
                                <div class="d-flex justify-content-between"><span class="text-muted">Amount Paid</span><span class="text-success">TZS {{ number_format($invoice->amount_paid, 2) }}</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100" style="background:#f0fdf4;border-color:#bbf7d0 !important;">
                                <h6 class="fw-bold text-success mb-3">Updated Calculation</h6>
                                <div class="d-flex justify-content-between mb-2"><span class="text-success">New Amount</span><span id="display-subtotal">TZS {{ number_format($invoice->subtotal, 2) }}</span></div>
                                <div class="d-flex justify-content-between"><span class="text-success">New Total</span><span id="display-total" class="fw-bold">TZS {{ number_format($invoice->total_amount, 2) }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function calculateTotals() {
        const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
        const fmt = subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('display-subtotal').textContent = 'TZS ' + fmt;
        document.getElementById('display-total').textContent = 'TZS ' + fmt;
    }
    document.getElementById('subtotal').addEventListener('input', calculateTotals);
    calculateTotals();
    </script>
</x-dashboard-layout>
