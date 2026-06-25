<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #055c5c, #077777);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: #2e7d32;
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #ffffff;
        }
        .email-body h2 {
            color: #055c5c;
            font-size: 24px;
            margin: 0 0 20px;
            text-align: center;
        }
        .email-body p {
            color: #666;
            line-height: 1.6;
            margin: 0 0 15px;
        }
        .contractor-details {
            background: #f8f9fa;
            border-left: 4px solid #055c5c;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .contractor-details p {
            margin: 8px 0;
            color: #333;
        }
        .contractor-details strong {
            color: #055c5c;
        }
        .next-steps {
            background: #e6f2f2;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .next-steps h3 {
            color: #055c5c;
            font-size: 18px;
            margin: 0 0 15px;
        }
        .next-steps ol {
            margin: 0;
            padding-left: 20px;
        }
        .next-steps li {
            color: #666;
            margin: 8px 0;
            line-height: 1.6;
        }
        .cta-button {
            display: block;
            width: fit-content;
            margin: 30px auto;
            padding: 15px 40px;
            background: #055c5c;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(5, 92, 92, 0.3);
        }
        .cta-button:hover {
            background: #044a4a;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }
        .email-footer p {
            color: #999;
            font-size: 14px;
            margin: 5px 0;
        }
        .email-footer a {
            color: #055c5c;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🎉 Congratulations!</h1>
            <p>Your contractor account has been approved</p>
        </div>

        <div class="email-body">
            <div class="success-icon">
                ✓
            </div>

            <h2>Welcome to GreenRoute!</h2>

            <p>Dear {{ $contractor->name }},</p>

            <p>We're excited to inform you that your contractor account has been successfully approved by our administrator. You can now access all contractor features on the GreenRoute platform.</p>

            <div class="contractor-details">
                <p><strong>Account Details:</strong></p>
                <p><strong>Name:</strong> {{ $contractor->name }}</p>
                <p><strong>Email:</strong> {{ $contractor->email }}</p>
                @if(!empty($password))
                <p><strong>Temporary Password:</strong> {{ $password }}</p>
                <p style="font-size: 13px; color: #dc2626; margin-top: 5px;">
                    <em>Please change this password immediately after logging in.</em>
                </p>
                @else
                <p><strong>Password:</strong> Use the password you created when you registered.</p>
                @endif
                <p><strong>Status:</strong> <span style="color: #2e7d32;">✓ Approved</span></p>
                <p><strong>Account Type:</strong> Waste Contractor</p>
            </div>

            <div class="next-steps">
                <h3>What's Next?</h3>
                <ol>
                    <li><strong>Log in to your account</strong> using your credentials</li>
                    <li><strong>Complete your profile</strong> with business details</li>
                    <li><strong>Add your clients</strong> and service areas</li>
                    <li><strong>Create schedules</strong> for waste collection</li>
                    <li><strong>Manage invoices</strong> and track payments</li>
                </ol>
            </div>

            <a href="{{ url('/login/contractor') }}" class="cta-button">
                Login to Your Dashboard
            </a>

            <p style="margin-top: 30px; font-size: 14px; color: #999;">
                If you have any questions or need assistance getting started, please don't hesitate to contact our support team.
            </p>
        </div>

        <div class="email-footer">
            <p><strong>GreenRoute</strong> - Waste Management System</p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>
                <a href="{{ url('/') }}">Visit Website</a> |
                <a href="mailto:support@greenroute.co.tz">Support</a>
            </p>
        </div>
    </div>
</body>
</html>
