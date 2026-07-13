<x-guest-layout>
    <div class="text-center mb-4">
        <div class="icon-circle">
            <i class="bi bi-shield-lock-fill text-success"></i>
        </div>
        <h2 class="fw-bold text-dark mb-2">Reset Password</h2>
        <p class="text-muted">Choose a new password for your account.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                <div>
                    <strong>Please correct the following:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label fw-medium">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                   class="form-control form-control-lg" placeholder="Your account email">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-medium">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="form-control form-control-lg" placeholder="Enter a new password">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-medium">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="form-control form-control-lg" placeholder="Re-enter the new password">
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100 mb-3">
            <i class="bi bi-check2-circle me-2"></i>Reset Password
        </button>
    </form>

    <div class="text-center">
        <a href="{{ session('password_back', route('login.client')) }}" class="text-success text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Back to Login
        </a>
    </div>
</x-guest-layout>
