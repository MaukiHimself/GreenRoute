<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EcoPickup') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
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
            
            .auth-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 2rem;
                position: relative;
            }
            
            .auth-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23e5f3ff" opacity="0.3"/><circle cx="75" cy="75" r="1" fill="%23d1fae5" opacity="0.3"/><circle cx="50" cy="10" r="0.5" fill="%23a7f3d0" opacity="0.2"/><circle cx="10" cy="60" r="0.5" fill="%23a7f3d0" opacity="0.2"/><circle cx="90" cy="40" r="0.5" fill="%23a7f3d0" opacity="0.2"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
                opacity: 0.4;
                z-index: 0;
            }
            
            .logo-container {
                position: relative;
                z-index: 1;
                margin-bottom: 2rem;
            }
            
            .logo-link {
                display: inline-block;
                text-decoration: none;
                transition: transform 0.3s ease;
            }
            
            .logo-link:hover {
                transform: scale(1.05);
            }
            
            .logo {
                font-size: 2.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #059669 0%, #10b981 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                letter-spacing: -0.02em;
            }
            
            .auth-card {
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 28rem;
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1), 0 10px 10px rgba(0, 0, 0, 0.04);
                padding: 2.5rem;
                border: 1px solid #f3f4f6;
                position: relative;
                overflow: hidden;
            }
            
            .auth-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            }
            
            @media (max-width: 640px) {
                .auth-container {
                    padding: 1rem;
                }
                
                .auth-card {
                    padding: 2rem;
                }
                
                .logo {
                    font-size: 2rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="logo-container">
                <a href="/" class="logo-link">
                    <x-afia-orbit-logo class="h-16 w-auto" />
                </a>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
