<x-guest-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="text-success mb-0">Client Details</h5>
                        <div>
                            <a href="{{ route('contractor.clients.edit', $client) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('contractor.clients.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success">Basic Information</h6>
                                <table class="table table-borderless">
                                    <tr><td><strong>Registration Number:</strong></td><td>{{ $client->registration_number }}</td></tr>
                                    <tr><td><strong>Business Name:</strong></td><td>{{ $client->name }}</td></tr>
                                    <tr><td><strong>Contact Person:</strong></td><td>{{ $client->contact_name }}</td></tr>
                                    <tr><td><strong>Category:</strong></td><td><span class="badge bg-info">{{ ucfirst($client->category) }}</span></td></tr>
                                    <tr><td><strong>Status:</strong></td><td><span class="badge bg-success">{{ ucfirst($client->status) }}</span></td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success">Contact Information</h6>
                                <table class="table table-borderless">
                                    <tr><td><strong>Phone 1:</strong></td><td>{{ $client->phone }}</td></tr>
                                    <tr><td><strong>Phone 2:</strong></td><td>{{ $client->phone_2 ?: 'N/A' }}</td></tr>
                                    <tr><td><strong>Phone 3:</strong></td><td>{{ $client->phone_3 ?: 'N/A' }}</td></tr>
                                    <tr><td><strong>Email 1:</strong></td><td>{{ $client->email }}</td></tr>
                                    <tr><td><strong>Email 2:</strong></td><td>{{ $client->email_2 ?: 'N/A' }}</td></tr>
                                    <tr><td><strong>Email 3:</strong></td><td>{{ $client->email_3 ?: 'N/A' }}</td></tr>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="text-success">Location Information</h6>
                                <table class="table table-borderless">
                                    <tr><td><strong>Address:</strong></td><td>{{ $client->address }}</td></tr>
                                    <tr><td><strong>City:</strong></td><td>{{ $client->city ?: 'N/A' }}</td></tr>
                                    <tr><td><strong>State:</strong></td><td>{{ $client->state ?: 'N/A' }}</td></tr>
                                    <tr><td><strong>ZIP Code:</strong></td><td>{{ $client->zip_code ?: 'N/A' }}</td></tr>
                                    <tr><td><strong>Coordinates:</strong></td><td>{{ $client->latitude }}, {{ $client->longitude }}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success">Additional Information</h6>
                                <table class="table table-borderless">
                                    <tr><td><strong>Notes:</strong></td><td>{{ $client->notes ?: 'No notes' }}</td></tr>
                                    <tr><td><strong>Created:</strong></td><td>{{ $client->created_at->format('M d, Y H:i') }}</td></tr>
                                    <tr><td><strong>Updated:</strong></td><td>{{ $client->updated_at->format('M d, Y H:i') }}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>