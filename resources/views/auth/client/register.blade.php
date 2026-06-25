<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Account Activation - GreenRoute</title>
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
        
        .activation-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .activation-header {
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
        
        .activation-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .activation-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 400;
        }
        
        .activation-form-container {
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
        
        .form-text {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        .verification-methods {
            background: var(--light-teal);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .verification-title {
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 1rem;
        }
        
        .form-check {
            margin-bottom: 0.75rem;
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
            display: flex;
            align-items: center;
        }
        
        .btn-activate {
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
        
        .btn-activate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 92, 92, 0.4);
            color: white;
        }
        
        .login-section {
            border-top: 1px solid #e9ecef;
            padding-top: 1.5rem;
            text-align: center;
        }
        
        .login-text {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .login-link {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .login-link:hover {
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
        
        .activation-steps {
            background: var(--light-teal);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            background: var(--primary-teal);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 1rem;
            font-size: 0.875rem;
        }
        
        .step-text {
            color: var(--primary-teal);
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .activation-container {
                padding: 2rem 0;
            }
            
            .activation-form-container {
                padding: 2rem;
            }
            
            .activation-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="activation-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="activation-form-container">
                                <!-- Header -->
                                <div class="activation-header">
                                    <div class="header-icon">
                                        <i class="bi bi-person-plus"></i>
                                    </div>
                                    <h1 class="activation-title">Client Account Activation</h1>
                                    <p class="activation-subtitle">Use the details provided by your contractor to activate your account</p>
                                </div>

                                <!-- Activation Steps -->
                                <div class="activation-steps">
                                    <div class="step-item">
                                        <div class="step-number">1</div>
                                        <div class="step-text">Enter your registration details</div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-number">2</div>
                                        <div class="step-text">Review and submit</div>
                                    </div>
                                    <div class="step-item">
                                        <div class="step-number">3</div>
                                        <div class="step-text">Complete registration</div>
                                    </div>
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

                                <!-- Activation Form -->
                                <form method="POST" action="{{ route('client.register.submit') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="registration_number" class="form-label">Registration Number</label>
                                        <input id="registration_number" type="text" name="registration_number" required autofocus
                                               class="form-control" placeholder="Enter registration number provided by contractor"
                                               value="{{ old('registration_number') }}">
                                        <small class="form-text">This unique number was provided by your waste management contractor</small>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="contact_name" class="form-label">Contact Name</label>
                                        <input id="contact_name" type="text" name="contact_name" required
                                               class="form-control" placeholder="Enter your full name as registered with contractor"
                                               value="{{ old('contact_name') }}">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input id="phone" type="text" name="phone" required
                                               class="form-control" placeholder="Enter your active phone number"
                                               value="{{ old('phone') }}">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input id="email" type="email" name="email" required
                                               class="form-control" placeholder="Enter your email address"
                                               value="{{ old('email') }}">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input id="password" type="password" name="password" required
                                               class="form-control" placeholder="Create a secure password">
                                        <small class="form-text">Password must be at least 8 characters long</small>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input id="password_confirmation" type="password" name="password_confirmation" required
                                               class="form-control" placeholder="Confirm your password">
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-activate">
                                            <i class="bi bi-shield-check me-2"></i>Activate Account
                                        </button>
                                    </div>
                                </form>

                                <!-- Login Link -->
                                <div class="login-section">
                                    <p class="login-text">Already activated your account? 
                                        <a href="{{ route('client.login') }}" class="login-link">Login here</a>
                                    </p>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>