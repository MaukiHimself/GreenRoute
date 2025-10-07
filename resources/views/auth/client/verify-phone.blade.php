<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Phone - AFIA ORBIT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #20B2AA;
            --primary-teal-dark: #1a9b94;
            --primary-teal-light: #e6f7f6;
            --accent-orange: #FF6B35;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --border-light: #e9ecef;
            --success-green: #28a745;
            --warning-yellow: #ffc107;
            --danger-red: #dc3545;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-teal-light) 0%, #ffffff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .verification-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        
        .verification-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(32, 178, 170, 0.1);
            padding: 3rem;
            border: 1px solid var(--border-light);
        }
        
        .icon-circle {
            width: 80px;
            height: 80px;
            background: var(--primary-teal-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }
        
        .icon-circle i {
            font-size: 2rem;
            color: var(--primary-teal);
        }
        
        .verification-title {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .verification-subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
        }
        
        .form-control {
            border: 2px solid var(--border-light);
            border-radius: 12px;
            padding: 1rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
        }
        
        .btn-verify {
            background: var(--primary-teal);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-verify:hover {
            background: var(--primary-teal-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(32, 178, 170, 0.3);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-green);
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger-red);
        }
        
        .registration-info {
            background: var(--primary-teal-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .info-value {
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="verification-card">
                        <div class="text-center mb-4">
                            <div class="icon-circle">
                                <i class="bi bi-phone"></i>
                            </div>
                            <h2 class="verification-title">Verify Your Phone</h2>
                            <p class="verification-subtitle">Enter the 6-digit code sent to your phone</p>
                        </div>

                        <!-- Registration Info -->
                        <div class="registration-info" id="registrationInfo" style="display: none;">
                            <h6 class="mb-3">Registration Details:</h6>
                            <div class="info-item">
                                <span class="info-label">Registration #:</span>
                                <span class="info-value" id="regNumber"></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Contact Name:</span>
                                <span class="info-value" id="contactName"></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Phone:</span>
                                <span class="info-value" id="phoneNumber"></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email:</span>
                                <span class="info-value" id="emailAddress"></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="code" class="form-label fw-medium">Verification Code</label>
                            <input id="code" type="text" name="code" required autofocus maxlength="6"
                                   class="form-control text-center" 
                                   placeholder="000000" style="letter-spacing: 0.5em;">
                            <div class="form-text">Enter the 6-digit code sent to your phone</div>
                        </div>
                        
                        <button type="button" onclick="verifyCode()" class="btn btn-verify w-100 mb-3">
                            <i class="bi bi-check-circle me-2"></i>Verify & Complete Registration
                        </button>

                        <div class="text-center">
                            <p class="text-muted">Didn't receive the code? 
                                <a href="/client/register" class="text-decoration-none fw-medium" style="color: var(--primary-teal);">Try again</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load registration data from session storage
        document.addEventListener('DOMContentLoaded', function() {
            const registrationData = sessionStorage.getItem('clientRegistration');
            if (registrationData) {
                const data = JSON.parse(registrationData);
                document.getElementById('regNumber').textContent = data.registration_number;
                document.getElementById('contactName').textContent = data.contact_name;
                document.getElementById('phoneNumber').textContent = data.phone;
                document.getElementById('emailAddress').textContent = data.email;
                document.getElementById('registrationInfo').style.display = 'block';
            }
        });
        
        function verifyCode() {
            const code = document.getElementById('code').value;
            if (!code || code.length !== 6) {
                alert('Please enter a valid 6-digit code.');
                return;
            }
            
            // Store registration as completed
            const registrationData = sessionStorage.getItem('clientRegistration');
            if (registrationData) {
                localStorage.setItem('completedRegistration', registrationData);
                sessionStorage.removeItem('clientRegistration');
            }
            
            // Redirect to login page
            alert('Verification successful! Please login with your credentials.');
            window.location.href = '/client/login';
        }
    </script>
</body>
</html>