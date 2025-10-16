<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Application Status</title>
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
            background: linear-gradient(135deg, #640404, #7c0505);
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
        .info-icon {
            width: 80px;
            height: 80px;
            background: #ef4444;
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #ffffff;
        }
        .email-body h2 {
            color: #333;
            font-size: 24px;
            margin: 0 0 20px;
            text-align: center;
        }
        .email-body p {
            color: #666;
            line-height: 1.6;
            margin: 0 0 15px;
        }
        .application-details {
            background: #f8f9fa;
            border-left: 4px solid #640404;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .application-details p {
            margin: 8px 0;
            color: #333;
        }
        .application-details strong {
            color: #640404;
        }
        .reasons-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .reasons-box h3 {
            color: #991b1b;
            font-size: 18px;
            margin: 0 0 15px;
        }
        .reasons-box p {
            color: #666;
            margin: 8px 0;
            line-height: 1.6;
        }
        .reapply-box {
            background: #e6f2f2;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .reapply-box h3 {
            color: #055c5c;
            font-size: 18px;
            margin: 0 0 15px;
        }
        .reapply-box p {
            color: #666;
            margin: 8px 0;
            line-height: 1.6;
        }
        .reapply-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .reapply-box li {
            color: #666;
            margin: 5px 0;
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
            <h1>Application Status Update</h1>
            <p>Regarding your contractor account application</p>
        </div>

        <div class="email-body">
            <div class="info-icon">
                ℹ
            </div>

            <h2>Account Application Notice</h2>

            <p>Dear {{ $contractor->name }},</p>

            <p>Thank you for your interest in joining AFIA ORBIT as a waste management contractor. After careful review of your application, we regret to inform you that we are unable to approve your contractor account at this time.</p>

            <div class="application-details">
                <p><strong>Application Details:</strong></p>
                <p><strong>Name:</strong> {{ $contractor->name }}</p>
                <p><strong>Email:</strong> {{ $contractor->email }}</p>
                <p><strong>Application Status:</strong> <span style="color: #ef4444;">Not Approved</span></p>
                <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
            </div>

            <div class="reasons-box">
                <h3>Common Reasons for Non-Approval</h3>
                <p>Applications may not be approved due to one or more of the following reasons:</p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li style="margin: 5px 0; color: #666;">Incomplete or missing required documentation</li>
                    <li style="margin: 5px 0; color: #666;">Business license verification issues</li>
                    <li style="margin: 5px 0; color: #666;">Service area coverage limitations</li>
                    <li style="margin: 5px 0; color: #666;">Insurance or certification requirements not met</li>
                    <li style="margin: 5px 0; color: #666;">Other regulatory or policy considerations</li>
                </ul>
            </div>

            <div class="reapply-box">
                <h3>Would You Like to Reapply?</h3>
                <p>If you believe this decision was made in error or if you can provide additional information or documentation, you are welcome to:</p>
                <ul>
                    <li><strong>Contact our support team</strong> to discuss your application</li>
                    <li><strong>Submit additional documentation</strong> that addresses the concerns</li>
                    <li><strong>Reapply</strong> after making the necessary updates</li>
                </ul>
            </div>

            <a href="mailto:support@afiaorbit.com" class="cta-button">
                Contact Support
            </a>

            <p style="margin-top: 30px; font-size: 14px; color: #999;">
                We appreciate your understanding and interest in AFIA ORBIT. If you have any questions about this decision or need clarification, please don't hesitate to reach out to our support team.
            </p>
        </div>

        <div class="email-footer">
            <p><strong>AFIA ORBIT</strong> - Waste Management System</p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>
                <a href="{{ url('/') }}">Visit Website</a> | 
                <a href="mailto:support@afiaorbit.com">Support</a>
            </p>
        </div>
    </div>
</body>
</html>
