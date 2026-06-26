<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenRoute - Waste Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-teal: #047857;
            --primary-red: #c0392b;
            --light-teal: rgba(5, 92, 92, 0.1);
            --dark-teal: #065f46;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #f0f9ff 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(5, 92, 92, 0.8) 0%, rgba(4, 74, 74, 0.9) 100%);
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .logo-text {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 2rem;
            text-shadow: none;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo-text img {
            height: 240px;
            max-width: 560px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 3rem;
            color: rgba(255,255,255,0.9);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cards-section {
            padding: 80px 0;
            background: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-teal);
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.2rem;
            color: #6c757d;
            max-width: 600px;
            margin: 0 auto;
        }

        .role-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.4s ease;
            border: 1px solid #f8f9fa;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--dark-teal) 100%);
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(5, 92, 92, 0.2);
        }

        .card-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, var(--light-teal) 0%, rgba(5, 92, 92, 0.3) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary-teal);
            transition: all 0.3s ease;
        }

        .role-card:hover .card-icon {
            transform: scale(1.1);
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--dark-teal) 100%);
            color: white;
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 1.5rem;
        }

        .card-description {
            color: #6c757d;
            margin-bottom: 2.5rem;
            line-height: 1.7;
            font-size: 1rem;
        }

        .btn-custom {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.5rem;
            display: inline-block;
            min-width: 140px;
            font-size: 0.95rem;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--dark-teal) 100%);
            color: white;
            border: 2px solid transparent;
            box-shadow: 0 4px 15px rgba(5, 92, 92, 0.3);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--dark-teal) 0%, #033333 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(5, 92, 92, 0.4);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            color: var(--primary-teal);
            border: 2px solid var(--primary-teal);
        }

        .btn-outline-custom:hover {
            background: var(--primary-teal);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(5, 92, 92, 0.3);
        }

        .features-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 80px 0;
        }

        .feature-item {
            text-align: center;
            padding: 2rem;
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--primary-teal);
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .logo-text {
                font-size: 3rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .role-card {
                margin-bottom: 2rem;
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 hero-content text-center">
                    <div class="logo-text animate-fade-in">
                        <img src="{{ asset('result.png') }}" alt="Logo">
                    </div>
                    <p class="hero-subtitle animate-fade-in">Smart Waste Management Solutions for a Sustainable Future</p>

                </div>
            </div>
        </div>
    </section>

    <!-- Cards Section -->
    <section class="cards-section">
        <div class="container">
            <div class="section-title">
                <h2>Choose Your Role</h2>
                <p>Join our platform and be part of the sustainable waste management revolution</p>
            </div>

            <div class="row g-4">
                <!-- Client Card -->
                <div class="col-lg-6">
                    <div class="role-card animate-fade-in">
                        <div class="card-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h3 class="card-title">Client Portal</h3>
                        <p class="card-description">
                            Schedule waste pickups, track collection history, manage invoices, and access support through our intuitive client dashboard.
                        </p>
                        <div>
                            <a href="{{ route('client.self-register') }}" class="btn-custom btn-primary-custom">
                                <i class="bi bi-person-plus me-2"></i>Get Started
                            </a>
                            <a href="{{ route('client.login') }}" class="btn-custom btn-outline-custom">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contractor Card -->
                <div class="col-lg-6">
                    <div class="role-card animate-fade-in">
                        <div class="card-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h3 class="card-title">Contractor Hub</h3>
                        <p class="card-description">
                            Manage operations, track routes with GPS, handle client assignments, generate invoices, and grow your waste management business.
                        </p>
                        <div>
                            <a href="{{ route('register.contractor') }}" class="btn-custom btn-primary-custom">
                                <i class="bi bi-briefcase me-2"></i>Join Network
                            </a>
                            <a href="{{ route('login.contractor') }}" class="btn-custom btn-outline-custom">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 feature-item">
                    <div class="feature-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h4>GPS Tracking</h4>
                    <p>Real-time location tracking and route optimization for efficient waste collection</p>
                </div>
                <div class="col-md-4 feature-item">
                    <div class="feature-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h4>Smart Scheduling</h4>
                    <p>Automated scheduling system with notifications and reminders</p>
                </div>
                <div class="col-md-4 feature-item">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h4>Analytics Dashboard</h4>
                    <p>Comprehensive reporting and analytics for better decision making</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.role-card, .feature-item').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>
