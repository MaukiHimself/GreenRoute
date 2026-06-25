<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Pending Approval - greenroute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #c0392b;
        }

        body {
            background: linear-gradient(135deg, var(--primary-teal) 0%, #087f8c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .pending-container {
            max-width: 600px;
            width: 90%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            text-align: center;
        }

        .pending-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 15px rgba(251, 191, 36, 0);
            }
        }

        .pending-icon i {
            font-size: 3.5rem;
            color: white;
        }

        .pending-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-teal);
            margin-bottom: 1rem;
        }

        .pending-subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #fbbf24;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
            text-align: left;
        }

        .info-box h5 {
            color: var(--primary-teal);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .info-box h5 i {
            margin-right: 0.5rem;
            color: #fbbf24;
        }

        .info-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-box li {
            padding: 0.5rem 0;
            color: #666;
            display: flex;
            align-items: start;
        }

        .info-box li i {
            color: #10b981;
            margin-right: 0.75rem;
            margin-top: 0.25rem;
        }

        .user-info {
            background: #e6f2f2;
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .user-info p {
            margin: 0.5rem 0;
            color: #333;
        }

        .user-info strong {
            color: var(--primary-teal);
        }

        .btn-primary {
            background: var(--primary-teal);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: #044a4a;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 92, 92, 0.3);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            margin-left: 1rem;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background: #4b5563;
            color: white;
        }

        .contact-support {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e5e7eb;
        }

        .contact-support p {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .contact-support a {
            color: var(--primary-teal);
            font-weight: 600;
            text-decoration: none;
        }

        .contact-support a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="pending-container">
        <div class="pending-icon">
            <i class="bi bi-hourglass-split"></i>
        </div>

        <h1 class="pending-title">Account Pending Approval</h1>

        <p class="pending-subtitle">
            Thank you for registering with greenroute! Your contractor account has been successfully created and is currently under review by our administrators.
        </p>

        @if(session('name') || session('email'))
        <div class="user-info">
            @if(session('name'))
            <p><strong>Name:</strong> {{ session('name') }}</p>
            @endif
            @if(session('email'))
            <p><strong>Email:</strong> {{ session('email') }}</p>
            @endif
        </div>
        @endif

        <div class="info-box">
            <h5><i class="bi bi-info-circle-fill"></i> What Happens Next?</h5>
            <ul>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Our team is reviewing your documents and credentials</span>
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>This process typically takes 1-3 business days</span>
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>You will receive an email notification once your account is reviewed</span>
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Once approved, you'll be able to access your contractor dashboard</span>
                </li>
            </ul>
        </div>

        <div class="info-box">
            <h5><i class="bi bi-shield-check"></i> We're Verifying:</h5>
            <ul>
                <li>
                    <i class="bi bi-file-earmark-check"></i>
                    <span>Company registration and licenses</span>
                </li>
                <li>
                    <i class="bi bi-file-earmark-check"></i>
                    <span>Certificate and documentation</span>
                </li>
                <li>
                    <i class="bi bi-file-earmark-check"></i>
                    <span>Service area coverage</span>
                </li>
                <li>
                    <i class="bi bi-file-earmark-check"></i>
                    <span>Contact information validity</span>
                </li>
            </ul>
        </div>

        <div class="mt-4">
            <a href="{{ url('/') }}" class="btn-primary">
                <i class="bi bi-house-door me-2"></i>Return to Homepage
            </a>
            <a href="{{ route('login.contractor') }}" class="btn-secondary">
                <i class="bi bi-box-arrow-in-right me-2"></i>Back to Login
            </a>
        </div>

        <div class="contact-support">
            <p><strong>Need Help?</strong></p>
            <p>
                If you have any questions or concerns, please contact us:<br>
                <a href="mailto:support@greenroute.co.tz"><i class="bi bi-envelope me-2"></i>support@greenroute.co.tz</a><br>
                <a href="tel:+255123456789"><i class="bi bi-telephone me-2"></i>+255 123 456 789</a>
            </p>
        </div>
    </div>
</body>
</html>
