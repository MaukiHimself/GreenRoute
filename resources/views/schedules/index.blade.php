<x-guest-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-success">Collection Schedules</h4>
            <a href="{{ route('schedules.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New Schedule
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Scheduled Collections</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Route Name</th>
                                <th>Client</th>
                                <th>Location</th>
                                <th>Pickup Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->pickup_location }}</td>
                                <td>{{ $schedule->client->name }}</td>
                                <td>{{ $schedule->pickup_address }}</td>
                                <td>{{ $schedule->pickup_date->format('M d, Y') }}</td>
                                <td>
                                    <select class="form-select form-select-sm" onchange="updateStatus({{ $schedule->id }}, this.value)">
                                        <option value="scheduled" {{ $schedule->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="in_progress" {{ $schedule->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $schedule->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $schedule->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-outline-primary">View</a>
                                        <a href="{{ route('schedules.print', $schedule) }}" class="btn btn-outline-secondary" target="_blank">Print</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No schedules found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $schedules->links() }}
            </div>
        </div>
    </div>

    <script>
        function updateStatus(scheduleId, status) {
            fetch(`/schedules/${scheduleId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    </script>
</x-guest-layout>