@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Invoice</h1>
        <div class="flex space-x-2">
            <a href="{{ route('invoices.show', $invoice) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-eye mr-2"></i>View Invoice
            </a>
            <a href="{{ route('invoices.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back to Invoices
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('invoices.update', $invoice) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Invoice Number (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Number</label>
                    <input type="text" value="{{ $invoice->invoice_number }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Client Selection -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                    <select name="client_id" id="client_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select a client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} - {{ $client->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Schedule Selection (Optional) -->
                <div>
                    <label for="schedule_id" class="block text-sm font-medium text-gray-700 mb-2">Related Schedule</label>
                    <select name="schedule_id" id="schedule_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">No related schedule</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('schedule_id', $invoice->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->client->name }} - {{ $schedule->pickup_date->format('M d, Y') }} ({{ $schedule->service_type }})@if($schedule->displayed_price !== null) - TZS {{ number_format($schedule->displayed_price, 2) }}@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Invoice Date -->
                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Invoice Date *</label>
                    <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>

                <!-- Service Type -->
                <div>
                    <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">Service Type *</label>
                    <select name="service_type" id="service_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select service type</option>
                        <option value="Waste Collection" {{ old('service_type', $invoice->service_type) == 'Waste Collection' ? 'selected' : '' }}>Waste Collection</option>
                        <option value="Recycling" {{ old('service_type', $invoice->service_type) == 'Recycling' ? 'selected' : '' }}>Recycling</option>
                        <option value="Hazardous Waste" {{ old('service_type', $invoice->service_type) == 'Hazardous Waste' ? 'selected' : '' }}>Hazardous Waste</option>
                        <option value="Bulk Pickup" {{ old('service_type', $invoice->service_type) == 'Bulk Pickup' ? 'selected' : '' }}>Bulk Pickup</option>
                        <option value="Other" {{ old('service_type', $invoice->service_type) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Subtotal -->
                <div>
                    <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-2">Subtotal ($) *</label>
                    <input type="number" name="subtotal" id="subtotal" step="0.01" min="0" value="{{ old('subtotal', $invoice->subtotal) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>

                <!-- Tax Rate -->
                <div>
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%) *</label>
                    <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100" value="{{ old('tax_rate', $invoice->tax_rate) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Detailed description of services provided...">{{ old('description', $invoice->description) }}</textarea>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Additional notes or payment terms...">{{ old('notes', $invoice->notes) }}</textarea>
            </div>

            <!-- Current Financial Summary -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-medium text-gray-800 mb-3">Current Financial Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="font-medium">Current Subtotal:</span>
                        <span id="current-subtotal" class="block text-lg">${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Current Tax:</span>
                        <span id="current-tax" class="block text-lg">${{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Current Total:</span>
                        <span id="current-total" class="block text-lg font-bold">${{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="font-medium">Amount Paid:</span>
                        <span class="block text-lg text-green-600">${{ number_format($invoice->amount_paid, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- New Total Calculation Display -->
            <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                <h3 class="font-medium text-green-800 mb-3">Updated Calculation</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-green-700">New Subtotal:</span>
                        <span id="display-subtotal" class="block text-lg text-green-800">${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-green-700">New Tax:</span>
                        <span id="display-tax" class="block text-lg text-green-800">${{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-green-700">New Total:</span>
                        <span id="display-total" class="block text-lg font-bold text-green-800">${{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('invoices.show', $invoice) }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">
                    <i class="fas fa-save mr-2"></i>Update Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Calculate totals in real-time
function calculateTotals() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
    const taxAmount = subtotal * (taxRate / 100);
    const total = subtotal + taxAmount;
    
    document.getElementById('display-subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('display-tax').textContent = '$' + taxAmount.toFixed(2);
    document.getElementById('display-total').textContent = '$' + total.toFixed(2);
}

// Add event listeners
document.getElementById('subtotal').addEventListener('input', calculateTotals);
document.getElementById('tax_rate').addEventListener('input', calculateTotals);

// Calculate on page load
calculateTotals();
</script>
@endsection