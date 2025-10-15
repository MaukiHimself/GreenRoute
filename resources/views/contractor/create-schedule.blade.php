@extends('layouts.app')

@section('title', 'Create Schedule')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create New Schedule</h1>
            <p class="text-gray-600 mt-2">Schedule will be automatically visible to your assigned client</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form id="scheduleForm" method="POST" action="{{ route('schedules.store') }}">
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
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            onchange="loadClientAddress()">
                        <option value="">Select a client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" 
                                data-reg-number="{{ $client->registration_number }}"
                                data-address="{{ $client->address }}"
                                data-city="{{ $client->city }}"
                                data-state="{{ $client->state }}"
                                data-zip="{{ $client->zip_code }}">
                            {{ $client->name }} ({{ $client->registration_number }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Schedule Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="pickup_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Pickup Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="pickup_date" id="pickup_date" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="pickup_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Pickup Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="pickup_time" id="pickup_time" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Location Details -->
                <div class="mb-6">
                    <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">
                        Pickup Location Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pickup_location" id="pickup_location" required
                           placeholder="e.g., Main Office, Warehouse A"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-6">
                    <label for="pickup_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Pickup Address <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pickup_address" id="pickup_address" required
                           placeholder="Street address"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="city" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                            State <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="state" id="state" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                            ZIP Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="zip_code" id="zip_code" required
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Service Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Service Type <span class="text-red-500">*</span>
                        </label>
                        <select name="service_type" id="service_type" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="collection">Collection</option>
                            <option value="disposal">Disposal</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <div>
                        <label for="estimated_duration" class="block text-sm font-medium text-gray-700 mb-2">
                            Estimated Duration (hours)
                        </label>
                        <input type="number" name="estimated_duration" id="estimated_duration" step="0.5" min="0"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Additional Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="total_volume" class="block text-sm font-medium text-gray-700 mb-2">
                            Total Volume (cubic yards)
                        </label>
                        <input type="number" name="total_volume" id="total_volume" step="0.1" min="0"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="disposal_site" class="block text-sm font-medium text-gray-700 mb-2">
                            Disposal Site
                        </label>
                        <input type="text" name="disposal_site" id="disposal_site"
                               placeholder="e.g., Landfill A, Recycling Center"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                              placeholder="Special instructions or additional information"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('schedules.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Create Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function loadClientAddress() {
    const select = document.getElementById('client_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('pickup_address').value = option.dataset.address || '';
        document.getElementById('city').value = option.dataset.city || '';
        document.getElementById('state').value = option.dataset.state || '';
        document.getElementById('zip_code').value = option.dataset.zip || '';
    }
}

// Alternative: Create schedule via API
async function createScheduleViaApi(formData) {
    try {
        const response = await fetch('/api/schedules', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(formData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Schedule created:', data.data.schedule);
            // Redirect or show success message
            window.location.href = '/schedules';
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Error creating schedule:', error);
    }
}
</script>
@endsection
