<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login - AFIA ORBIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #640404;
            --white: #ffffff;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-teal) 0%, #077777 50%, var(--primary-red) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .login-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        
        .login-header {
            background: var(--primary-teal);
            padding: 2.5rem 2rem;
            text-align: center;
            color: var(--white);
        }
        
        .logo-container {
            background: var(--white);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .logo-container img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }
        
        .logo-container i {
            font-size: 3rem;
            color: var(--primary-teal);
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .login-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .login-body {
            padding: 2.5rem 2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.15);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group .form-control {
            padding-left: 2.75rem;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-teal);
            z-index: 10;
            font-size: 1.1rem;
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            z-index: 10;
            font-size: 1.1rem;
        }
        
        .password-toggle:hover {
            color: var(--primary-teal);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-teal);
            border-color: var(--primary-teal);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-teal), #077777);
            color: var(--white);
            border: none;
            border-radius: 10px;
            padding: 0.875rem;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(5, 92, 92, 0.3);
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #044a4a, var(--primary-teal));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 92, 92, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .alert-danger {
            background-color: #fee;
            color: var(--primary-red);
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-link a {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .back-link a:hover {
            color: var(--primary-red);
            text-decoration: underline;
        }
        
        .security-notice {
            background: #f8f9fa;
            border-left: 4px solid var(--primary-teal);
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: #666;
        }
        
        .security-notice i {
            color: var(--primary-teal);
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Header -->
        <div class="login-header">
            <div class="logo-container">
                @if(file_exists(public_path('your-logo2.png')))
                    <img src="/your-logo2.png" alt="AFIA ORBIT Logo">
                @else
                    <i class="bi bi-shield-lock-fill"></i>
                @endif
            </div>
            <h1>Administrator Login</h1>
            <p>Secure Access Portal</p>
        </div>

        <!-- Login Body -->
        <div class="login-body">
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Error:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <i class="bi bi-envelope-fill input-icon"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            placeholder="admin@afiaorbit.com">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            required
                            placeholder="Enter your password">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Remember me on this device
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Dashboard
                </button>
            </form>

            <!-- Security Notice -->
            <div class="security-notice">
                <i class="bi bi-shield-check"></i>
                <strong>Security Notice:</strong> This is a restricted area. All access attempts are logged and monitored.
            </div>

            <!-- Back Link -->
            <div class="back-link">
                <a href="/">
                    <i class="bi bi-arrow-left me-1"></i>Back to Main Site
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
