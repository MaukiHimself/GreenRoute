<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Login - GreenRoute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #c0392b;
            --light-teal: rgba(5, 92, 92, 0.1);
            --dark-teal: #044a4a;
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
        
        .logo-icon {
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
        
        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0.15em;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-teal);
            border-color: var(--primary-teal);
        }
        
        .form-check-label {
            color: var(--primary-teal);
            font-weight: 500;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--dark-teal) 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
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
        
        .forgot-link {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-link:hover {
            color: var(--dark-teal);
            text-decoration: underline;
        }
        
        .register-section {
            border-top: 1px solid #e9ecef;
            padding-top: 1.5rem;
            text-align: center;
        }
        
        .register-text {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .register-link {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .register-link:hover {
            color: var(--dark-teal);
            text-decoration: underline;
        }
        
        .alert-custom {
            border-radius: 12px;
            border: 2px solid;
            padding: 1.25rem;
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
                        <div class="col-lg-6 mx-auto">
                            <div class="login-form-container">
                                <!-- Header -->
                                <div class="login-header">
                                    <div class="logo-icon">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <h1 class="login-title">Contractor Login</h1>
                                    <p class="login-subtitle">Sign in to manage your business operations</p>
                                </div>

                                <!-- Error Messages -->
                                @if($errors->any())
                                    <div class="alert alert-custom alert-danger-custom mb-4">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                                            <div>
                                                <h5 class="alert-heading mb-2">Please correct the following errors:</h5>
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
                                <form method="POST" action="{{ route('login.contractor.authenticate') }}">
                                    @csrf
                                    <input type="hidden" name="user_type" value="contractor">
                                    
                                    <div class="mb-4">
                                        <label for="email" class="form-label">Business Email</label>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                               class="form-control" placeholder="Enter your business email">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input id="password" type="password" name="password" required
                                               class="form-control" placeholder="Enter your password">
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input id="remember" name="remember" type="checkbox" class="form-check-input">
                                            <label for="remember" class="form-check-label">Remember me</label>
                                        </div>
                                        
                                        <a href="{{ route('password.request') }}" class="forgot-link">
                                            Forgot password?
                                        </a>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-login w-100 mb-4">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to Dashboard
                                    </button>
                                </form>

                                <!-- Registration Link -->
                                <div class="register-section">
                                    <p class="register-text">Don't have an account? 
                                        <a href="{{ route('register.contractor') }}" class="register-link">Register here</a>
                                    </p>
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