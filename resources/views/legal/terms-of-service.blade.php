<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Terms of Service - {{ config('app.name', 'GreenRoute') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .legal-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }
        
        .legal-header {
            background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
            color: white;
            padding: 3rem 1.5rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .legal-content {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .legal-content h2 {
            color: #2e7d32;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        
        .legal-content h3 {
            color: #157347;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        
        .legal-content p {
            line-height: 1.8;
            color: #64748b;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: #2e7d32;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            color: #157347;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="legal-container">
        <a href="/" class="back-link">← Back to Home</a>
        
        <div class="legal-header">
            <h1>Terms of Service</h1>
            <p class="mb-0">Last Updated: October 27, 2025</p>
        </div>
        
        <div class="legal-content">
            <h2>1. Agreement to Terms</h2>
            <p>
                By accessing and using the GreenRoute platform, you agree to be bound by these Terms of Service. 
                If you do not agree to these terms, please do not use our services.
            </p>
            
            <h2>2. Description of Service</h2>
            <p>
                GreenRoute provides waste management and collection services through our platform, connecting 
                contractors with clients for efficient waste collection, scheduling, and billing management.
            </p>
            
            <h2>3. User Accounts</h2>
            <h3>3.1 Account Creation</h3>
            <p>
                To use our services, you must create an account by providing accurate and complete information. 
                You are responsible for maintaining the confidentiality of your account credentials.
            </p>
            
            <h3>3.2 Account Types</h3>
            <p>
                Our platform supports three types of user accounts: Clients, Contractors, and Administrators. 
                Each account type has specific permissions and responsibilities.
            </p>
            
            <h2>4. User Responsibilities</h2>
            <p>
                Users agree to use the platform in compliance with all applicable laws and regulations. 
                You must not misuse the service, attempt to gain unauthorized access, or interfere with other users' access.
            </p>
            
            <h2>5. Service Availability</h2>
            <p>
                While we strive to maintain continuous service availability, we do not guarantee uninterrupted access. 
                We reserve the right to modify, suspend, or discontinue services with or without notice.
            </p>
            
            <h2>6. Payment Terms</h2>
            <p>
                Clients agree to pay for services as agreed upon with their assigned contractor. Contractors agree 
                to provide accurate billing information and maintain transparency in their pricing.
            </p>
            
            <h2>7. Intellectual Property</h2>
            <p>
                All content, features, and functionality of the GreenRoute platform are owned by us and are 
                protected by copyright, trademark, and other intellectual property laws.
            </p>
            
            <h2>8. Limitation of Liability</h2>
            <p>
                To the maximum extent permitted by law, GreenRoute shall not be liable for any indirect, 
                incidental, special, consequential, or punitive damages resulting from your use of the platform.
            </p>
            
            <h2>9. Termination</h2>
            <p>
                We reserve the right to terminate or suspend your account at our discretion, without notice, 
                for conduct that we believe violates these Terms or is harmful to other users or our business.
            </p>
            
            <h2>10. Changes to Terms</h2>
            <p>
                We may modify these Terms at any time. Continued use of the platform after changes constitutes 
                acceptance of the modified Terms.
            </p>
            
            <h2>11. Contact Information</h2>
            <p>
                For questions about these Terms of Service, please contact us at: <a href="mailto:legal@greenroute.co.tz">legal@greenroute.co.tz</a>
            </p>
        </div>
    </div>
    
    <!-- Footer Component -->
    <x-footer />
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
