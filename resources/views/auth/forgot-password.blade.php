<x-guest-layout>
    <div class="text-center mb-4">
        <div class="icon-circle">
            <i class="bi bi-key-fill text-success"></i>
        </div>
        <h2 class="fw-bold text-dark mb-2">Forgot Password?</h2>
        <p class="text-muted">Enter your account email and we'll send you a link to reset your password.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
        </div>
    @endif

    @if (session('reset_link'))
        <div class="d-grid mb-4">
            <a href="{{ session('reset_link') }}" class="btn btn-success btn-lg">
                <i class="bi bi-shield-lock me-2"></i>Reset My Password Now
            </a>
        </div>
    @endif

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

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label fw-medium">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="form-control form-control-lg" placeholder="Enter your account email">
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100 mb-3">
            <i class="bi bi-send me-2"></i>Send Password Reset Link
        </button>
    </form>

    <div class="text-center">
        <a href="{{ session('password_back', url('/')) }}" class="text-success text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Back to Login
        </a>
    </div>
</x-guest-layout>
