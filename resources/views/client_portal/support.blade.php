<x-dashboard-layout title="Support & Help">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link" href="{{ route('client.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.invoices') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('client.support') }}"><i class="bi bi-headset me-2"></i>Support/Help</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.client') }}">Client</a></li>
        <li class="breadcrumb-item active">Support</li>
    </x-slot>

    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0"><i class="bi bi-life-preserver me-2 text-primary"></i>How can we help you?</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('client.support.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" placeholder="Briefly describe your issue" required>
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Provide details so we can assist you faster" required></textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i> Submit Ticket</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0"><i class="bi bi-lightning-charge me-2 text-warning"></i>Quick Links</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('client.schedules') }}" class="list-group-item list-group-item-action border-0 px-0"><i class="bi bi-calendar-plus me-2 text-primary"></i>View Schedules</a>
                            <a href="{{ route('client.invoices') }}" class="list-group-item list-group-item-action border-0 px-0"><i class="bi bi-receipt me-2 text-primary"></i>View Invoices</a>
                            <a href="#" class="list-group-item list-group-item-action border-0 px-0"><i class="bi bi-credit-card me-2 text-success"></i>Payments</a>
                            <a href="#" class="list-group-item list-group-item-action border-0 px-0"><i class="bi bi-file-text me-2 text-info"></i>FAQs</a>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0"><i class="bi bi-telephone me-2 text-success"></i>Contact</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1 text-muted">Email: support@greenrouteorbit.com</p>
                        <p class="mb-1 text-muted">Phone: +233 00 000 0000</p>
                        <p class="mb-0 text-muted">Hours: Mon–Fri, 9:00–17:00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
