<x-dashboard-layout title="Account Settings">
    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Contractor</a></li>
        <li class="breadcrumb-item active">Account Settings</li>
    </x-slot>

    @php
        $contractor = $user->contractor;
        $paymentMethods = [
            'crdb_bank_lipa_no' => ['name' => 'CRDB Bank', 'logo' => 'crdb.png'],
            'nbc_bank_lipa_no' => ['name' => 'NBC Bank', 'logo' => 'nbc.png'],
            'nmb_bank_lipa_no' => ['name' => 'NMB Bank', 'logo' => 'nmb.png'],
            'vodacom_mpesa_lipa_no' => ['name' => 'Vodacom M-Pesa', 'logo' => 'mpesa.png'],
            'halopesa_lipa_no' => ['name' => 'Halopesa', 'logo' => 'halopesa.png'],
            'airtel_money_lipa_no' => ['name' => 'Airtel Money', 'logo' => 'airtel_money.png'],
            'mixx_by_yas_lipa_no' => ['name' => 'Mixx by Yas', 'logo' => 'mixx_by_yas.png'],
        ];
    @endphp

    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                        <div>
                            <p class="text-uppercase text-success fw-semibold small mb-2">Contractor settings</p>
                            <h1 class="h3 mb-2">{{ $user->name }}</h1>
                            <p class="text-muted mb-0">{{ $user->email }} · Configure your profile and Lipa Namba options.</p>
                        </div>
                        <a href="{{ route('dashboard.contractor') }}" class="btn btn-outline-secondary align-self-md-start">
                            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <form method="post" action="{{ route('profile.update') }}" class="card">
                @csrf
                @method('patch')

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <h2 class="h5 mb-3">Profile Information</h2>

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if (session('status') === 'profile-updated')
                                <div class="alert alert-success mb-0">Settings saved successfully.</div>
                            @endif
                        </div>

                        <div class="col-lg-7">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <h2 class="h5 mb-1">Lipa No Configuration</h2>
                                    <p class="text-muted mb-0">Add the Lipa Namba clients should use for each payment gateway.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                @foreach ($paymentMethods as $field => $method)
                                    <div class="col-md-6">
                                        <label for="{{ $field }}" class="form-label">{{ $method['name'] }} Lipa No</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <img src="{{ asset('assets/images/payments/' . $method['logo']) }}" alt="{{ $method['name'] }} logo" style="width: 42px; height: 30px; object-fit: contain;">
                                            </span>
                                            <input
                                                id="{{ $field }}"
                                                name="{{ $field }}"
                                                type="text"
                                                value="{{ old($field, $contractor?->{$field}) }}"
                                                class="form-control @error($field) is-invalid @enderror"
                                                placeholder="Enter Lipa No">
                                            @error($field)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 px-4 pb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
