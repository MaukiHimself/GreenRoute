@extends('layouts.app')

@section('title', 'My Schedules')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">My Schedules</h1>
            <div class="text-sm text-gray-600">
                Registration Number: <span class="font-semibold">{{ $client->registration_number }}</span>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-600">Total Schedules</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_count'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-600">Upcoming</div>
                <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['upcoming'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-600">Completed</div>
                <div class="mt-2 text-3xl font-bold text-green-600">{{ $stats['completed'] }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('client.schedules') }}" class="flex gap-4">
                <select name="status" class="rounded-md border-gray-300">
                    <option value="">All Statuses</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="rounded-md border-gray-300" placeholder="From Date">
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="rounded-md border-gray-300" placeholder="To Date">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Filter
                </button>
            </form>
        </div>

        <!-- Schedules List -->
        <div class="space-y-4">
            @forelse($schedules as $schedule)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $schedule->pickup_location }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $schedule->pickup_address }}</p>
                        </div>
                        <div>
                            @switch($schedule->status)
                                @case('scheduled')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Scheduled
                                    </span>
                                    @break
                                @case('in_progress')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        In Progress
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Date:</span>
                            <p class="font-medium text-gray-900">{{ $schedule->pickup_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Time:</span>
                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($schedule->pickup_time)->format('h:i A') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Service Type:</span>
                            <p class="font-medium text-gray-900">{{ ucfirst($schedule->service_type) }}</p>
                        </div>
                        @if($schedule->estimated_duration)
                        <div>
                            <span class="text-gray-600">Duration:</span>
                            <p class="font-medium text-gray-900">{{ $schedule->estimated_duration }} hrs</p>
                        </div>
                        @endif
                    </div>

                    @if($schedule->notes)
                    <div class="mt-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-700"><strong>Notes:</strong> {{ $schedule->notes }}</p>
                    </div>
                    @endif

                    <div class="mt-4 flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            Contractor: <span class="font-medium text-gray-900">{{ $schedule->contractor->name ?? 'N/A' }}</span>
                        </div>
                        <a href="{{ route('client.schedules.show', $schedule->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            View Details →
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="text-4xl mb-4">📅</div>
                <p class="text-lg font-medium text-gray-900">No schedules found</p>
                <p class="text-sm text-gray-600 mt-2">Schedules created by your contractor will appear here automatically.</p>
            </div>
            @endforelse
        </div>

        @if($schedules->hasPages())
        <div class="mt-6">
            {{ $schedules->links() }}
        </div>
        @endif
    </div>
</div>

<script>
// Example: Fetch schedules via API (Alternative approach)
async function fetchSchedulesViaApi(clientRegistrationNumber) {
    try {
        const response = await fetch(`/api/clients/${clientRegistrationNumber}/schedules`);
        const data = await response.json();
        
        if (data.success) {
            console.log('Schedules:', data.data.schedules);
            console.log('Upcoming count:', data.data.upcoming);
            // Update UI with fetched data
        }
    } catch (error) {
        console.error('Error fetching schedules:', error);
    }
}
</script>
@endsection
