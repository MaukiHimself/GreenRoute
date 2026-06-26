<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal Access - GreenRoute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #047857;
            --primary-red: #c0392b;
            --light-teal: rgba(5, 92, 92, 0.1);
            --dark-teal: #065f46;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header-icon {
            width: 100px;
            height: 100px;
            background: var(--light-teal);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: var(--primary-teal);
            border: 3px solid var(--primary-teal);
        }

        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 400;
        }

        .login-form-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 15px 35px rgba(5, 92, 92, 0.1);
            border: 1px solid rgba(5, 92, 92, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 0.3rem rgba(5, 92, 92, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--dark-teal) 100%);
            border: none;
            border-radius: 12px;
            padding: 1.25rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(5, 92, 92, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 92, 92, 0.4);
            color: white;
        }

        .support-section {
            border-top: 1px solid #e9ecef;
            padding-top: 1.5rem;
            text-align: center;
        }

        .support-text {
            color: #6c757d;
            margin-bottom: 0;
        }

        .support-link {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .support-link:hover {
            color: var(--dark-teal);
            text-decoration: underline;
        }

        .alert-custom {
            border-radius: 12px;
            border: 2px solid;
            padding: 1.25rem;
        }

        .alert-success-custom {
            background-color: rgba(5, 92, 92, 0.05);
            border-color: var(--primary-teal);
            color: var(--primary-teal);
        }

        .alert-danger-custom {
            background-color: rgba(100, 4, 4, 0.05);
            border-color: var(--primary-red);
            color: var(--primary-red);
        }

        .features-sidebar {
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--dark-teal) 100%);
            color: white;
            padding: 3rem;
            border-radius: 20px;
            height: fit-content;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.25rem;
        }

        .feature-text {
            flex: 1;
        }

        .feature-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .feature-desc {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .security-notice {
            background: var(--light-teal);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-teal);
        }

        .security-title {
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }

        .security-text {
            color: var(--primary-teal);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 2rem 0;
            }

            .login-form-container {
                padding: 2rem;
            }

            .login-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-10">
                    <div class="row">
                        <!-- Login Form Column -->
                        <div class="col-lg-7">
                            <div class="login-form-container">
                                <!-- Logo -->
                                <div class="text-center mb-4">
                                    <img src="{{ asset('result.png') }}" alt="GreenRoute Logo" style="max-height: 80px; width: auto;">
                                </div>

                                <!-- Header -->
                                <div class="login-header">
                                    <div class="header-icon">
                                        <i class="bi bi-person-check"></i>
                                    </div>
                                    <h1 class="login-title">Client Portal Access</h1>
                                    <p class="login-subtitle">Enter your account details to access the portal</p>
                                </div>

                                <!-- Security Notice -->
                                <div class="security-notice">
                                    <h6 class="security-title"><i class="bi bi-shield-check me-2"></i>Secure Login</h6>
                                    <p class="security-text">Your account information is protected with enterprise-grade security measures.</p>
                                </div>

                                <!-- Success Message -->
                                @if(session('success'))
                                    <div class="alert alert-custom alert-success-custom mb-4">
                                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                                    </div>
                                @endif

                                <!-- Error Messages -->
                                @if($errors->any())
                                    <div class="alert alert-custom alert-danger-custom mb-4">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                                            <div>
                                                <h5 class="alert-heading mb-2">Access failed:</h5>
                                                <ul class="mb-0 ps-3">
                                                    @foreach($errors->all() as $error)
                                                        <li class="mb-1">{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Login Form -->
                                <form method="POST" action="{{ route('client.login.submit') }}">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="email" class="form-label">
                                            <i class="bi bi-envelope me-2"></i>Email or Phone Number
                                        </label>
                                        <input id="email" type="text" name="email"
                                               value="{{ old('email') }}"
                                               required autofocus
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="Enter your email or phone number">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Use the email address provided in your invitation
                                        </small>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label">
                                            <i class="bi bi-lock me-2"></i>Password
                                        </label>
                                        <input id="password" type="password" name="password"
                                               required
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Enter your password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Use the temporary password from your invitation email or your updated password
                                        </small>
                                    </div>

                                    <div class="mb-4 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember me for 30 days
                                        </label>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-login">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Access Client Portal
                                        </button>
                                    </div>
                                </form>

                                <!-- Support Link -->
                                <div class="support-section">
                                    <p class="support-text">Need help accessing your account?
                                        <a href="#" class="support-link">Contact Support</a>
                                    </p>
                                    <p class="support-text mt-2">New client? 
                                        <a href="{{ route('client.self-register') }}" class="support-link fw-bold">Register yourself here</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Features Sidebar -->
                        <div class="col-lg-5 d-none d-lg-block">
                            <div class="features-sidebar">
                                <h3 class="mb-4">Welcome to Your Client Portal</h3>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div class="feature-text">
                                        <div class="feature-title">Schedule Management</div>
                                        <div class="feature-desc">View and manage your waste collection schedules</div>
                                    </div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <div class="feature-text">
                                        <div class="feature-title">Invoice Tracking</div>
                                        <div class="feature-desc">Access and manage your service invoices</div>
                                    </div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <div class="feature-text">
                                        <div class="feature-title">Service Tracking</div>
                                        <div class="feature-desc">Monitor your waste collection services in real-time</div>
                                    </div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-graph-up"></i>
                                    </div>
                                    <div class="feature-text">
                                        <div class="feature-title">Usage Analytics</div>
                                        <div class="feature-desc">Track your waste management patterns and costs</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
