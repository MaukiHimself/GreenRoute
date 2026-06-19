@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('dashboard.contractor') }}" class="text-gray-600 hover:text-gray-800 mr-4 flex items-center" title="Home" target="_parent">
                    <i class="fas fa-home mr-1"></i>
                </a>
                <a href="{{ route('schedules.show', $schedule) }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Edit Schedule</h1>
            </div>
            <a href="{{ route('schedules.show', $schedule) }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-eye mr-1"></i>View
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('schedules.update', $schedule) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Client Selection -->
                <div class="mb-6">
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                    <select name="client_id" id="client_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('client_id') border-red-500 @enderror" required>
                        <option value="">Select a client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ (old('client_id', $schedule->client_id) == $client->id) ? 'selected' : '' }}>
                                {{ $client->name }} - {{ $client->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="pickup_date" class="block text-sm font-medium text-gray-700 mb-2">Pickup Date *</label>
                        <input type="date" name="pickup_date" id="pickup_date" value="{{ old('pickup_date', $schedule->pickup_date->format('Y-m-d')) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pickup_date') border-red-500 @enderror" required>
                        @error('pickup_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="pickup_time" class="block text-sm font-medium text-gray-700 mb-2">Pickup Time *</label>
                        <input type="time" name="pickup_time" id="pickup_time" value="{{ old('pickup_time', $schedule->pickup_time->format('H:i')) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pickup_time') border-red-500 @enderror" required>
                        @error('pickup_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Service Type -->
                <div class="mb-6">
                    <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">Service Type *</label>
                    <select name="service_type" id="service_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('service_type') border-red-500 @enderror" required>
                        <option value="">Select service type</option>
                        <option value="collection" {{ old('service_type', $schedule->service_type) == 'collection' ? 'selected' : '' }}>Collection</option>
                        <option value="disposal" {{ old('service_type', $schedule->service_type) == 'disposal' ? 'selected' : '' }}>Disposal</option>
                        <option value="both" {{ old('service_type', $schedule->service_type) == 'both' ? 'selected' : '' }}>Both</option>
                    </select>
                    @error('service_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" required>
                        <option value="scheduled" {{ old('status', $schedule->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="in_progress" {{ old('status', $schedule->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $schedule->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Billing Rate -->
                <div class="mb-6">
                    <label for="billing_rate_id" class="block text-sm font-medium text-gray-700 mb-2">Official Billing Rate</label>
                    <select name="billing_rate_id" id="billing_rate_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('billing_rate_id') border-red-500 @enderror">
                        <option value="">Select an official billing rate</option>
                        @foreach($billingRates as $rate)
                            <option value="{{ $rate->id }}" data-fee="{{ $rate->collection_fee }}" {{ old('billing_rate_id', $schedule->billing_rate_id) == $rate->id ? 'selected' : '' }}>
                                {{ $rate->label }} - TZS {{ number_format($rate->collection_fee, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('billing_rate_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="contractor_adjusted_fee" class="block text-sm font-medium text-gray-700 mb-2">Contractor Adjusted Price (TZS)</label>
                        <input type="number" name="contractor_adjusted_fee" id="contractor_adjusted_fee" value="{{ old('contractor_adjusted_fee', $schedule->contractor_adjusted_fee) }}"
                               step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('contractor_adjusted_fee') border-red-500 @enderror">
                        @error('contractor_adjusted_fee')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="schedule_price_preview" class="block text-sm font-medium text-gray-700 mb-2">Schedule Price (TZS)</label>
                        <input type="text" name="schedule_price_preview" id="schedule_price_preview" value="TZS {{ number_format($schedule->displayed_price ?? 0, 2) }}" readonly
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="billing_rate_change_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Rate Selection or Price Override</label>
                    <textarea name="billing_rate_change_reason" id="billing_rate_change_reason" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('billing_rate_change_reason') border-red-500 @enderror"
                              placeholder="Example: Client requested additional service, difficult access, or volume difference">{{ old('billing_rate_change_reason', $schedule->billing_rate_change_reason) }}</textarea>
                    @error('billing_rate_change_reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location Details -->
                <div class="mb-6">
                    <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">Pickup Location *</label>
                    <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location', $schedule->pickup_location) }}" 
                           placeholder="e.g., Front yard, Garage, Loading dock" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pickup_location') border-red-500 @enderror" required>
                    @error('pickup_location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="mb-6">
                    <label for="pickup_address" class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                    <input type="text" name="pickup_address" id="pickup_address" value="{{ old('pickup_address', $schedule->pickup_address) }}" 
                           placeholder="123 Main Street" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pickup_address') border-red-500 @enderror" required>
                    @error('pickup_address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City, State, Zip -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $schedule->city) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror" required>
                        @error('city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                        <input type="text" name="state" id="state" value="{{ old('state', $schedule->state) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('state') border-red-500 @enderror" required>
                        @error('state')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">Zip Code *</label>
                        <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $schedule->zip_code) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('zip_code') border-red-500 @enderror" required>
                        @error('zip_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Estimated Duration -->
                <div class="mb-6">
                    <label for="estimated_duration" class="block text-sm font-medium text-gray-700 mb-2">Estimated Duration (hours)</label>
                    <input type="number" name="estimated_duration" id="estimated_duration" value="{{ old('estimated_duration', $schedule->estimated_duration) }}" 
                           step="0.25" min="0.25" max="24" placeholder="2.5" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('estimated_duration') border-red-500 @enderror">
                    @error('estimated_duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="Special instructions, access codes, etc." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $schedule->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('schedules.show', $schedule) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        Update Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function updateBillingPreview() {
    const rateSelect = document.getElementById('billing_rate_id');
    const overrideInput = document.getElementById('contractor_adjusted_fee');
    const preview = document.getElementById('schedule_price_preview');
    const selectedOption = rateSelect.options[rateSelect.selectedIndex];
    const officialFee = selectedOption.dataset.fee ? parseFloat(selectedOption.dataset.fee) : null;
    const overrideFee = overrideInput.value.trim() === '' ? null : parseFloat(overrideInput.value);
    const finalFee = overrideFee !== null ? overrideFee : officialFee;

    preview.value = finalFee !== null ? 'TZS ' + finalFee.toFixed(2) : 'TZS 0.00';
}

document.getElementById('billing_rate_id').addEventListener('change', updateBillingPreview);
document.getElementById('contractor_adjusted_fee').addEventListener('input', updateBillingPreview);
updateBillingPreview();
</script>
@endsection