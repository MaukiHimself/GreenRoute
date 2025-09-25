<x-guest-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-success">Disposal Schedule</h4>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Completed Collections - Record Disposal Data</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Route</th>
                                <th>Collection Date</th>
                                <th>Site Location</th>
                                <th>Volume (m³)</th>
                                <th>Disposal Site</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->pickup_location }}</td>
                                <td>{{ $schedule->pickup_date->format('M d, Y') }}</td>
                                <td>{{ $schedule->pickup_address }}</td>
                                <td>
                                    @if($schedule->total_volume)
                                        {{ number_format($schedule->total_volume, 2) }}
                                    @else
                                        <span class="text-muted">Not recorded</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->disposal_site)
                                        {{ $schedule->disposal_site }}
                                        <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $schedule->disposal_type)) }}</small>
                                    @else
                                        <span class="text-muted">Not recorded</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->total_volume && $schedule->disposal_site)
                                        <span class="badge bg-success">Recorded</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('disposal.show', $schedule) }}" class="btn btn-outline-primary">View</a>
                                        <a href="{{ route('disposal.edit', $schedule) }}" class="btn btn-outline-warning">Record Data</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No completed collections found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
</x-guest-layout>