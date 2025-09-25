<x-guest-layout>
    <div class="container py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="text-success mb-0">Client Details</h5>
                <div>
                    <a href="/contractor/clients/{{ $client->id }}/edit" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success">Basic Information</h6>
                        <p><strong>Registration Number:</strong> {{ $client->registration_number }}</p>
                        <p><strong>Business Name:</strong> {{ $client->name }}</p>
                        <p><strong>Contact Person:</strong> {{ $client->contact_name }}</p>
                        <p><strong>Category:</strong> {{ ucfirst($client->category) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($client->status) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Contact Information</h6>
                        <p><strong>Phone 1:</strong> {{ $client->phone }}</p>
                        <p><strong>Phone 2:</strong> {{ $client->phone_2 ?: 'N/A' }}</p>
                        <p><strong>Phone 3:</strong> {{ $client->phone_3 ?: 'N/A' }}</p>
                        <p><strong>Email 1:</strong> {{ $client->email }}</p>
                        <p><strong>Email 2:</strong> {{ $client->email_2 ?: 'N/A' }}</p>
                        <p><strong>Email 3:</strong> {{ $client->email_3 ?: 'N/A' }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-8">
                        <h6 class="text-success">Location Information</h6>
                        <p><strong>Address:</strong> {{ $client->address }}</p>
                        <p><strong>City:</strong> {{ $client->city ?: 'N/A' }} | <strong>State:</strong> {{ $client->state ?: 'N/A' }} | <strong>ZIP:</strong> {{ $client->zip_code ?: 'N/A' }}</p>
                        <p><strong>Coordinates:</strong> {{ $client->latitude }}, {{ $client->longitude }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-success">Record Information</h6>
                        <p><strong>Created:</strong> {{ $client->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Updated:</strong> {{ $client->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                @if($client->notes)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-success">Notes</h6>
                        <p>{{ $client->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>