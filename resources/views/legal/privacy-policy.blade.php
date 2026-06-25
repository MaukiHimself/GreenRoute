<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Privacy Policy - {{ config('app.name', 'GreenRoute') }}</title>
    
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
        
        .legal-content ul {
            color: #64748b;
            line-height: 1.8;
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
            <h1>Privacy Policy</h1>
            <p class="mb-0">Last Updated: October 27, 2025</p>
        </div>
        
        <div class="legal-content">
            <h2>1. Introduction</h2>
            <p>
                GreenRoute ("we," "our," or "us") respects your privacy and is committed to protecting your personal data. 
                This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.
            </p>
            
            <h2>2. Information We Collect</h2>
            <h3>2.1 Personal Information</h3>
            <p>We collect the following types of personal information:</p>
            <ul>
                <li>Name, email address, and phone number</li>
                <li>Business information (for contractors)</li>
                <li>Address and location data</li>
                <li>Payment and billing information</li>
                <li>Account credentials</li>
            </ul>
            
            <h3>2.2 Usage Data</h3>
            <p>
                We automatically collect information about your interaction with our platform, including IP address, 
                browser type, pages visited, and time spent on the platform.
            </p>
            
            <h3>2.3 Location Data</h3>
            <p>
                With your permission, we collect location data to provide route optimization and scheduling services.
            </p>
            
            <h2>3. How We Use Your Information</h2>
            <p>We use your information to:</p>
            <ul>
                <li>Provide and maintain our services</li>
                <li>Process transactions and send notifications</li>
                <li>Improve and personalize your experience</li>
                <li>Send administrative information and updates</li>
                <li>Monitor and analyze usage patterns</li>
                <li>Detect and prevent fraud</li>
                <li>Comply with legal obligations</li>
            </ul>
            
            <h2>4. Information Sharing</h2>
            <h3>4.1 With Service Providers</h3>
            <p>
                We may share your information with third-party service providers who perform services on our behalf, 
                such as payment processing, data analysis, and email delivery.
            </p>
            
            <h3>4.2 Between Users</h3>
            <p>
                Client information is shared with assigned contractors to facilitate service delivery. Contractor 
                information is shared with clients to enable communication and scheduling.
            </p>
            
            <h3>4.3 Legal Requirements</h3>
            <p>
                We may disclose your information if required by law or in response to valid requests by public authorities.
            </p>
            
            <h2>5. Data Security</h2>
            <p>
                We implement appropriate technical and organizational measures to protect your personal data against 
                unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over 
                the Internet is 100% secure.
            </p>
            
            <h2>6. Data Retention</h2>
            <p>
                We retain your personal information only for as long as necessary to fulfill the purposes outlined in 
                this Privacy Policy, unless a longer retention period is required by law.
            </p>
            
            <h2>7. Your Rights</h2>
            <p>Depending on your location, you may have the following rights:</p>
            <ul>
                <li>Access to your personal data</li>
                <li>Correction of inaccurate data</li>
                <li>Deletion of your data</li>
                <li>Restriction of processing</li>
                <li>Data portability</li>
                <li>Objection to processing</li>
                <li>Withdrawal of consent</li>
            </ul>
            
            <h2>8. Cookies and Tracking</h2>
            <p>
                We use cookies and similar tracking technologies to track activity on our platform and store certain 
                information. You can instruct your browser to refuse cookies, but this may limit your ability to use 
                some features.
            </p>
            
            <h2>9. Children's Privacy</h2>
            <p>
                Our services are not intended for individuals under the age of 18. We do not knowingly collect 
                personal information from children.
            </p>
            
            <h2>10. Changes to This Policy</h2>
            <p>
                We may update this Privacy Policy from time to time. We will notify you of any changes by posting 
                the new Privacy Policy on this page and updating the "Last Updated" date.
            </p>
            
            <h2>11. Contact Us</h2>
            <p>
                If you have questions about this Privacy Policy, please contact us at: 
                <a href="mailto:privacy@greenroute.co.tz">privacy@greenroute.co.tz</a>
            </p>
        </div>
    </div>
    
    <!-- Footer Component -->
    <x-footer />
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
