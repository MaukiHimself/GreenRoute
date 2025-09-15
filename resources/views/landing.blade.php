<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoPickup - Waste Management System</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdf4 100%);
            min-height: 100vh;
            color: #1f2937;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header {
            text-align: center;
            padding: 3rem 0;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }
        
        .subtitle {
            font-size: 1.25rem;
            color: #6b7280;
            margin-bottom: 2rem;
            font-weight: 400;
        }
        
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 10px 15px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #f3f4f6;
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1), 0 10px 10px rgba(0, 0, 0, 0.04);
        }
        
        .card-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .card-description {
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
            font-size: 0.95rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            margin: 0.5rem;
            border: 2px solid transparent;
            cursor: pointer;
            text-align: center;
            min-width: 120px;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        
        .btn-login {
            background: white;
            color: #059669;
            border: 2px solid #10b981;
        }
        
        .btn-login:hover {
            background: #f0fdf4;
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.2);
        }
        
        .footer {
            text-align: center;
            margin-top: 4rem;
            padding: 2rem 0;
            color: #6b7280;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .features {
            margin: 3rem 0;
            text-align: center;
        }
        
        .features h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .features p {
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .logo {
                font-size: 2.5rem;
            }
            
            .card-container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .card {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🌱 EcoPickup</div>
            <div class="subtitle">Sustainable Waste Management Solutions for a Greener Future</div>
        </div>
        
        <div class="features">
            <h3>Choose Your Role</h3>
            <p>Join our platform and contribute to a cleaner, more sustainable environment</p>
        </div>
        
        <div class="card-container">
            <div class="card">
                <div class="card-icon">👤</div>
                <div class="card-title">Client</div>
                <div class="card-description">
                    Schedule waste pickups, track your collection history, and manage your account with our easy-to-use client portal.
                </div>
                <div>
                    <a href="{{ route('register.client') }}" class="btn btn-register">Get Started</a>
                    <a href="{{ route('login.client') }}" class="btn btn-login">Sign In</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-icon">🚛</div>
                <div class="card-title">Contractor</div>
                <div class="card-description">
                    Manage your waste collection operations, track client assignments, generate invoices, and grow your business.
                </div>
                <div>
                    <a href="{{ route('register.contractor') }}" class="btn btn-register">Join Us</a>
                    <a href="{{ route('login.contractor') }}" class="btn btn-login">Sign In</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-icon">⚙️</div>
                <div class="card-title">Administrator</div>
                <div class="card-description">
                    Oversee the entire platform, manage users, monitor system performance, and ensure smooth operations.
                </div>
                <div>
                    <a href="{{ route('register.admin') }}" class="btn btn-register">Access Panel</a>
                    <a href="{{ route('login.admin') }}" class="btn btn-login">Sign In</a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} AFIA IT Orbit Department. All rights reserved.</p>
            <p style="margin-top: 0.5rem; font-size: 0.9rem;">Building a sustainable future, one pickup at a time.</p>
        </div>
    </div>
</body>
</html>