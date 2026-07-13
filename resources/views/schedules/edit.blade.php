@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('dashboard.contractor') }}" class="text-gray-500 hover:text-gray-700 mr-4 flex items-center" title="Home" target="_parent">
                    <i class="fas fa-home"></i>
                </a>
                <a href="{{ route('schedules.show', $schedule) }}" class="text-teal-600 hover:text-teal-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Schedule</h1>
                    <p class="text-sm text-gray-500">Update pickup details, pricing and location</p>
                </div>
            </div>
            <a href="{{ route('schedules.show', $schedule) }}" class="hidden sm:inline-flex items-center text-sm text-gray-600 hover:text-gray-800">
                <i class="fas fa-eye mr-1"></i>View
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('schedules.update', $schedule) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Client & Service -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center">
                        <i class="fas fa-user-circle text-teal-600 mr-2"></i>Client &amp; Service
                    </h2>
                </div>
                <div class="p-5 space-y-5">
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                        <select name="client_id" id="client_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('client_id') border-red-500 @enderror" required>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">Service Type *</label>
                            <select name="service_type" id="service_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('service_type') border-red-500 @enderror" required>
                                <option value="">Select service type</option>
                                <option value="collection" {{ old('service_type', $schedule->service_type) == 'collection' ? 'selected' : '' }}>Collection</option>
                                <option value="disposal" {{ old('service_type', $schedule->service_type) == 'disposal' ? 'selected' : '' }}>Disposal</option>
                                <option value="both" {{ old('service_type', $schedule->service_type) == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                            @error('service_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('status') border-red-500 @enderror" required>
                                <option value="scheduled" {{ old('status', $schedule->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="in_progress" {{ old('status', $schedule->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $schedule->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timing -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center">
                        <i class="fas fa-clock text-teal-600 mr-2"></i>Timing
                    </h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="pickup_date" class="block text-sm font-medium text-gray-700 mb-2">Pickup Date *</label>
                            <input type="date" name="pickup_date" id="pickup_date" value="{{ old('pickup_date', $schedule->pickup_date->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('pickup_date') border-red-500 @enderror" required>
                            @error('pickup_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="pickup_time" class="block text-sm font-medium text-gray-700 mb-2">Pickup Time *</label>
                            <input type="time" name="pickup_time" id="pickup_time" value="{{ old('pickup_time', $schedule->pickup_time->format('H:i')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('pickup_time') border-red-500 @enderror" required>
                            @error('pickup_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="estimated_duration" class="block text-sm font-medium text-gray-700 mb-2">Est. Duration (hrs)</label>
                            <input type="number" name="estimated_duration" id="estimated_duration" value="{{ old('estimated_duration', $schedule->estimated_duration) }}"
                                   step="0.25" min="0.25" max="24" placeholder="2.5"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('estimated_duration') border-red-500 @enderror">
                            @error('estimated_duration')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center">
                        <i class="fas fa-coins text-teal-600 mr-2"></i>Billing
                    </h2>
                </div>
                <div class="p-5 space-y-5">
                    <div>
                        <label for="billing_rate_id" class="block text-sm font-medium text-gray-700 mb-2">Official Billing Rate</label>
                        <select name="billing_rate_id" id="billing_rate_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('billing_rate_id') border-red-500 @enderror">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="contractor_adjusted_fee" class="block text-sm font-medium text-gray-700 mb-2">Contractor Adjusted Price (TZS)</label>
                            <input type="number" name="contractor_adjusted_fee" id="contractor_adjusted_fee" value="{{ old('contractor_adjusted_fee', $schedule->contractor_adjusted_fee) }}"
                                   step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('contractor_adjusted_fee') border-red-500 @enderror">
                            @error('contractor_adjusted_fee')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="schedule_price_preview" class="block text-sm font-medium text-gray-700 mb-2">Schedule Price (TZS)</label>
                            <input type="text" name="schedule_price_preview" id="schedule_price_preview" value="TZS {{ number_format($schedule->displayed_price ?? 0, 2) }}" readonly
                                   class="w-full px-3 py-2 border border-gray-200 rounded-md bg-teal-50 text-teal-800 font-semibold">
                        </div>
                    </div>

                    <div>
                        <label for="billing_rate_change_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Rate Selection or Price Override</label>
                        <textarea name="billing_rate_change_reason" id="billing_rate_change_reason" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('billing_rate_change_reason') border-red-500 @enderror"
                                  placeholder="Example: Client requested additional service, difficult access, or volume difference">{{ old('billing_rate_change_reason', $schedule->billing_rate_change_reason) }}</textarea>
                        @error('billing_rate_change_reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center">
                        <i class="fas fa-map-marker-alt text-teal-600 mr-2"></i>Location
                    </h2>
                </div>
                <div class="p-5 space-y-5">
                    <div>
                        <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">Pickup Location *</label>
                        <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location', $schedule->pickup_location) }}"
                               placeholder="e.g., Front yard, Garage, Loading dock"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('pickup_location') border-red-500 @enderror" required>
                        @error('pickup_location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pickup_address" class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                        <input type="text" name="pickup_address" id="pickup_address" value="{{ old('pickup_address', $schedule->pickup_address) }}"
                               placeholder="123 Main Street"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('pickup_address') border-red-500 @enderror" required>
                        @error('pickup_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $schedule->city) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('city') border-red-500 @enderror" required>
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                            <input type="text" name="state" id="state" value="{{ old('state', $schedule->state) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('state') border-red-500 @enderror" required>
                            @error('state')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">Zip Code *</label>
                            <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $schedule->zip_code) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('zip_code') border-red-500 @enderror" required>
                            @error('zip_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center">
                        <i class="fas fa-sticky-note text-teal-600 mr-2"></i>Notes
                    </h2>
                </div>
                <div class="p-5">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              placeholder="Special instructions, access codes, etc."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('notes') border-red-500 @enderror">{{ old('notes', $schedule->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('schedules.show', $schedule) }}" class="px-5 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition duration-200 shadow-sm">
                    <i class="fas fa-save mr-1"></i>Update Schedule
                </button>
            </div>
        </form>
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
