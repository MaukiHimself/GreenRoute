<x-dashboard-layout title="Account Settings">
    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Contractor</a></li>
        <li class="breadcrumb-item active">Account Settings</li>
    </x-slot>

    @php
        $contractor = $user->contractor;
        $paymentMethods = [
            'crdb_bank_lipa_no' => ['name' => 'CRDB Bank', 'logo' => 'crdb.png', 'name_field' => 'crdb_bank_lipa_name'],
            'nbc_bank_lipa_no' => ['name' => 'NBC Bank', 'logo' => 'nbc.png', 'name_field' => 'nbc_bank_lipa_name'],
            'nmb_bank_lipa_no' => ['name' => 'NMB Bank', 'logo' => 'nmb.png', 'name_field' => 'nmb_bank_lipa_name'],
            'vodacom_mpesa_lipa_no' => ['name' => 'Vodacom M-Pesa', 'logo' => 'mpesa.png', 'name_field' => 'vodacom_mpesa_lipa_name'],
            'halopesa_lipa_no' => ['name' => 'Halopesa', 'logo' => 'halopesa.png', 'name_field' => 'halopesa_lipa_name'],
            'airtel_money_lipa_no' => ['name' => 'Airtel Money', 'logo' => 'airtel_money.png', 'name_field' => 'airtel_money_lipa_name'],
            'mixx_by_yas_lipa_no' => ['name' => 'Mixx by Yas', 'logo' => 'mixx_by_yas.png', 'name_field' => 'mixx_by_yas_lipa_name'],
        ];
    @endphp

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center text-white fw-bold" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    <form method="POST" action="{{ route('profile.picture') }}" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <input type="file" name="profile_picture" accept="image/*" class="form-control form-control-sm mb-2" required>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-camera me-1"></i>Change Photo
                        </button>
                    </form>
                    @if(session('status') === 'profile-picture-updated')
                        <div class="alert alert-success py-1 small mb-0">Picture updated!</div>
                    @endif
                    @if($user->dark_mode)
                        <span class="badge bg-dark"><i class="bi bi-moon me-1"></i> Dark Mode</span>
                    @else
                        <span class="badge bg-light text-dark"><i class="bi bi-sun me-1"></i> Light Mode</span>
                    @endif
                </div>
            </div>
            <div class="list-group">
                <a href="#profile-section" class="list-group-item list-group-item-action active" data-bs-toggle="list">Profile Information</a>
                <a href="#settings-section" class="list-group-item list-group-item-action" data-bs-toggle="list">Settings</a>
                <a href="#password-section" class="list-group-item list-group-item-action" data-bs-toggle="list">Change Password</a>
                <a href="#payments-section" class="list-group-item list-group-item-action" data-bs-toggle="list">Lipa No Configuration</a>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="profile-section" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('profile.update') }}">
                                @csrf
                                @method('patch')
                                @if (session('status') === 'profile-updated')
                                    <div class="alert alert-success mb-3">Settings saved successfully.</div>
                                @endif
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Name</label>
                                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required autocomplete="name">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Save Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="settings-section" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-md-8">
                                    <h6 class="mb-1"><i class="bi bi-moon-stars me-2"></i>Dark Mode</h6>
                                    <p class="text-muted small mb-0">Switch between light and dark display theme</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <form method="POST" action="{{ route('profile.toggle-dark-mode') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $user->dark_mode ? 'btn-dark' : 'btn-outline-dark' }}">
                                            @if($user->dark_mode)
                                                <i class="bi bi-moon-fill me-1"></i>Dark Mode ON
                                            @else
                                                <i class="bi bi-sun-fill me-1"></i>Light Mode
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="row align-items-center py-3 border-bottom">
                                <div class="col-md-8">
                                    <h6 class="mb-1"><i class="bi bi-bell me-2"></i>Notifications</h6>
                                    <p class="text-muted small mb-0">Receive email notifications for new clients and payments</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input" type="checkbox" checked disabled id="notifSwitch">
                                        <label class="form-check-label" for="notifSwitch">Enabled</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center py-3">
                                <div class="col-md-8">
                                    <h6 class="mb-1"><i class="bi bi-shield-check me-2"></i>Two-Factor Authentication</h6>
                                    <p class="text-muted small mb-0">Add extra security to your account (coming soon)</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Coming Soon</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="password-section" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-key me-2"></i>Change Password</h5>
                        </div>
                        <div class="card-body">
                            @if(session('status') === 'password-updated')
                                <div class="alert alert-success mb-3">Password changed successfully.</div>
                            @endif
                            <form method="POST" action="{{ route('profile.update-password') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control" required autocomplete="new-password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                                </div>
                                <button type="submit" class="btn btn-warning"><i class="bi bi-shield-lock me-1"></i>Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="payments-section" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-credit-card-2-front me-2"></i>Lipa No Configuration</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Add the Lipa Namba and display name clients will see when paying for each payment gateway.</p>
                            <form method="post" action="{{ route('profile.update') }}">
                                @csrf
                                @method('patch')
                                <div class="row g-3">
                                    @foreach ($paymentMethods as $field => $method)
                                        <div class="col-md-6">
                                            <label for="{{ $field }}" class="form-label fw-bold">{{ $method['name'] }} Lipa No</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text bg-white">
                                                    <img src="{{ asset('assets/images/payments/' . $method['logo']) }}" alt="{{ $method['name'] }}" style="width: 42px; height: 30px; object-fit: contain;">
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
                                            <label for="{{ $method['name_field'] }}" class="form-label small text-muted">Display Name (what clients see)</label>
                                            <input
                                                id="{{ $method['name_field'] }}"
                                                name="{{ $method['name_field'] }}"
                                                type="text"
                                                value="{{ old($method['name_field'], $contractor?->{$method['name_field']}) }}"
                                                class="form-control form-control-sm @error($method['name_field']) is-invalid @enderror"
                                                placeholder="e.g., John Doe Waste Services">
                                            @error($method['name_field'])
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Save Payment Settings</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
