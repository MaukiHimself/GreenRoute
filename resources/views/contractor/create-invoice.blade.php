@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create New Invoice</h1>
            <p class="text-gray-600 mt-2">Invoice will be automatically visible to your assigned client</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form id="invoiceForm" method="POST" action="{{ route('invoices.store') }}">
                @csrf

                <!-- Contractor Info (Auto-filled) -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-2">Your Information</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Registration Number:</span>
                            <span class="font-medium text-gray-900">{{ $contractor->registration_number }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Assigned Client:</span>
                            <span class="font-medium text-gray-900">{{ $assignedClient->name ?? 'Not assigned' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Client Selection -->
                <div class="mb-6">
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Client <span class="text-red-500">*</span>
                    </label>
                    <select name="client_id" id="client_id" required 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select a client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" data-reg-number="{{ $client->registration_number }}">
                            {{ $client->name }} ({{ $client->registration_number }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Invoice Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Invoice Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="invoice_date" id="invoice_date" required
                               value="{{ date('Y-m-d') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Due Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="due_date" id="due_date" required
                               value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Service Information -->
                <div class="mb-6">
                    <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Service Type <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="service_type" id="service_type" required
                           placeholder="e.g., Waste Collection, Disposal Service"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Detailed description of services provided"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <!-- Financial Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-2">
                            Subtotal (TZS) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="subtotal" id="subtotal" step="0.01" min="0" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               oninput="calculateTotals()">
                    </div>

                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">
                            Tax Rate (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100" value="0"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               oninput="calculateTotals()">
                    </div>
                </div>

                <!-- Calculated Totals -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold" id="display_subtotal">TZS 0.00</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Tax:</span>
                        <span class="font-semibold" id="display_tax">TZS 0.00</span>
                    </div>
                    <div class="flex justify-between text-lg border-t pt-2">
                        <span class="font-bold text-gray-900">Total:</span>
                        <span class="font-bold text-blue-600" id="display_total">TZS 0.00</span>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              placeholder="Internal notes (optional)"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <!-- Link to Schedule (Optional) -->
                <div class="mb-6">
                    <label for="schedule_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Link to Schedule (Optional)
                    </label>
                    <select name="schedule_id" id="schedule_id"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">No schedule</option>
                        @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}">
                            {{ $schedule->pickup_location }} - {{ $schedule->pickup_date->format('M d, Y') }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('invoices.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Create Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateTotals() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
    
    const taxAmount = subtotal * (taxRate / 100);
    const total = subtotal + taxAmount;
    
    document.getElementById('display_subtotal').textContent = 'TZS ' + subtotal.toFixed(2);
    document.getElementById('display_tax').textContent = 'TZS ' + taxAmount.toFixed(2);
    document.getElementById('display_total').textContent = 'TZS ' + total.toFixed(2);
}

// Alternative: Create invoice via API
async function createInvoiceViaApi(formData) {
    try {
        const response = await fetch('/api/invoices', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Invoice created:', data.data.invoice);
            // Redirect or show success message
            window.location.href = '/invoices';
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Error creating invoice:', error);
    }
}
</script>
@endsection
