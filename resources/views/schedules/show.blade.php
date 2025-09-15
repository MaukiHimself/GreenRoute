@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('schedules.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Schedule Details</h1>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('schedules.edit', $schedule) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="inline" 
                      onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Schedule Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Schedule Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date & Time</label>
                        <div class="mt-1">
                            <p class="text-lg font-medium text-gray-900">{{ $schedule->pickup_date->format('l, F j, Y') }}</p>
                            <p class="text-gray-600">{{ $schedule->pickup_time->format('g:i A') }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Service Type</label>
                        <span class="inline-flex mt-1 px-3 py-1 text-sm font-semibold rounded-full 
                            @if($schedule->service_type === 'collection') bg-blue-100 text-blue-800
                            @elseif($schedule->service_type === 'disposal') bg-red-100 text-red-800
                            @else bg-purple-100 text-purple-800 @endif">
                            {{ ucfirst($schedule->service_type) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <span class="inline-flex mt-1 px-3 py-1 text-sm font-semibold rounded-full 
                            @if($schedule->status === 'scheduled') bg-yellow-100 text-yellow-800
                            @elseif($schedule->status === 'in_progress') bg-blue-100 text-blue-800
                            @elseif($schedule->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                        </span>
                    </div>

                    @if($schedule->estimated_duration)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Estimated Duration</label>
                            <p class="mt-1 text-gray-900">{{ $schedule->estimated_duration }} hours</p>
                        </div>
                    @endif

                    @if($schedule->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Notes</label>
                            <p class="mt-1 text-gray-900">{{ $schedule->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Client Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Client Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Client Name</label>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $schedule->client->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-gray-900">
                            <a href="mailto:{{ $schedule->client->email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $schedule->client->email }}
                            </a>
                        </p>
                    </div>

                    @if($schedule->client->phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Phone</label>
                            <p class="mt-1 text-gray-900">
                                <a href="tel:{{ $schedule->client->phone }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $schedule->client->phone }}
                                </a>
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Client Status</label>
                        <span class="inline-flex mt-1 px-2 py-1 text-xs font-semibold rounded-full 
                            @if($schedule->client->status === 'active') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($schedule->client->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Information -->
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pickup Location</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Pickup Location</label>
                    <p class="mt-1 text-gray-900">{{ $schedule->pickup_location }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500">Full Address</label>
                    <div class="mt-1 text-gray-900">
                        <p>{{ $schedule->pickup_address }}</p>
                        <p>{{ $schedule->city }}, {{ $schedule->state }} {{ $schedule->zip_code }}</p>
                    </div>
                    <a href="https://maps.google.com/?q={{ urlencode($schedule->full_address) }}" 
                       target="_blank" 
                       class="inline-flex items-center mt-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        View on Google Maps
                    </a>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <span class="font-medium">Created:</span> {{ $schedule->created_at->format('M j, Y g:i A') }}
                </div>
                <div>
                    <span class="font-medium">Last Updated:</span> {{ $schedule->updated_at->format('M j, Y g:i A') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection