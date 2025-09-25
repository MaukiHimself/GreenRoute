<x-guest-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-success">Collection Schedule - {{ $schedule->pickup_location }}</h4>
            <div>
                <a href="{{ route('schedules.print', $schedule) }}" class="btn btn-secondary" target="_blank">
                    <i class="bi bi-printer"></i> Print Schedule
                </a>
                <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Weekly Collection Status - {{ $schedule->pickup_date->format('M d, Y') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Address</th>
                                <th>Category</th>
                                <th>Phone</th>
                                <th>Collection Status</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locationSchedules as $locationSchedule)
                            <tr>
                                <td>{{ $locationSchedule->client->name }}</td>
                                <td>{{ $locationSchedule->pickup_address }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($locationSchedule->client->category) }}</span></td>
                                <td>{{ $locationSchedule->client->phone }}</td>
                                <td>
                                    <select class="form-select form-select-sm" onchange="updateStatus({{ $locationSchedule->id }}, this.value)">
                                        <option value="scheduled" {{ $locationSchedule->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="in_progress" {{ $locationSchedule->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $locationSchedule->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $locationSchedule->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="checkbox" class="form-check-input" 
                                           {{ $locationSchedule->status === 'completed' ? 'checked' : '' }}
                                           onchange="toggleComplete({{ $locationSchedule->id }}, this.checked)">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($schedule->notes)
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Comments</h6>
            </div>
            <div class="card-body">
                <p>{{ $schedule->notes }}</p>
            </div>
        </div>
        @endif
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

        function toggleComplete(scheduleId, isCompleted) {
            const status = isCompleted ? 'completed' : 'scheduled';
            updateStatus(scheduleId, status);
        }
    </script>
</x-guest-layout>