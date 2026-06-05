<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GreenRoute') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #e8f5e8 0%, #f0f9ff 50%, #e0f2fe 100%);
                min-height: 100vh;
            }

            .auth-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
                position: relative;
            }

            .auth-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23198754" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23198754" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
                z-index: 0;
            }

            .auth-card {
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 450px;
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                padding: 3rem 2.5rem;
                border-top: 4px solid #198754;
            }

            .logo-section {
                text-align: center;
                margin-bottom: 2rem;
            }

            .logo {
                font-size: 2.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #198754 0%, #20c997 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                text-decoration: none;
                display: inline-block;
                transition: transform 0.3s ease;
            }

            .logo:hover {
                transform: scale(1.05);
            }

            .form-control:focus {
                border-color: #198754;
                box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
            }

            .btn-success {
                background: linear-gradient(135deg, #198754 0%, #20c997 100%);
                border: none;
                padding: 12px 24px;
                font-weight: 500;
                border-radius: 10px;
                transition: all 0.3s ease;
            }

            .btn-success:hover {
                background: linear-gradient(135deg, #157347 0%, #1aa179 100%);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(25, 135, 84, 0.3);
            }

            .icon-circle {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #e8f5e8 0%, #d1e7dd 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                font-size: 1.5rem;
            }

            .alert-danger {
                border-radius: 10px;
                border: none;
                background: #f8d7da;
                color: #721c24;
            }

            .alert-success {
                border-radius: 10px;
                border: none;
                background: #d1e7dd;
                color: #0f5132;
            }

            @media (max-width: 576px) {
                .auth-card {
                    padding: 2rem 1.5rem;
                    margin: 1rem;
                }

                .logo {
                    font-size: 2rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-card">
                <div class="logo-section">
                    <a href="/" class="logo text-decoration-none">
                        <x-greenroute-logo size="xl" />
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <x-footer />

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
