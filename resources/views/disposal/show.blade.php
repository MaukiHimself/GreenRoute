<x-guest-layout>
    <div class="container py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="text-success mb-0">Disposal Record - {{ $schedule->pickup_location }}</h5>
                <div>
                    <a href="{{ route('disposal.edit', $schedule) }}" class="btn btn-warning btn-sm">Edit Data</a>
                    <a href="{{ route('disposal.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">Collection Information</h6>
                        <p><strong>Route Name:</strong> {{ $schedule->pickup_location }}</p>
                        <p><strong>Collection Date:</strong> {{ $schedule->pickup_date->format('M d, Y') }}</p>
                        <p><strong>Collection Time:</strong> {{ $schedule->pickup_time }}</p>
                        <p><strong>Site Location:</strong> {{ $schedule->pickup_address }}</p>
                        <p><strong>Client:</strong> {{ $schedule->client->name }}</p>
                        <p><strong>Service Type:</strong> {{ ucfirst($schedule->service_type) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Disposal Information</h6>
                        @if($schedule->total_volume)
                            <p><strong>Total Volume Collected:</strong> {{ number_format($schedule->total_volume, 2) }} m³</p>
                            <p><strong>Disposal Site:</strong> {{ $schedule->disposal_site }}</p>
                            <p><strong>Disposal Type:</strong> {{ ucfirst(str_replace('_', ' ', $schedule->disposal_type)) }}</p>
                            @if($schedule->disposal_notes)
                                <p><strong>Disposal Notes:</strong> {{ $schedule->disposal_notes }}</p>
                            @endif
                        @else
                            <p class="text-muted">Disposal data not yet recorded</p>
                            <a href="{{ route('disposal.edit', $schedule) }}" class="btn btn-warning btn-sm">Record Disposal Data</a>
                        @endif
                    </div>
                </div>
                
                @if($schedule->notes)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-success">Collection Notes</h6>
                        <p>{{ $schedule->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>