<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Submitted — GreenRoute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --teal: #047857; --teal-dark: #065f46; }
        * { font-family: 'Segoe UI', Tahoma, sans-serif; }
        body {
            background: linear-gradient(135deg, #f0f9f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .pending-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(5, 92, 92, .15);
            overflow: hidden;
        }
        .pending-header {
            background: linear-gradient(135deg, var(--teal), #059669);
            color: #fff;
            padding: 2.5rem 2rem 3.5rem;
            text-align: center;
            position: relative;
        }
        .icon-circle {
            width: 96px; height: 96px;
            background: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.75rem; color: var(--teal);
            margin: 0 auto -48px;
            box-shadow: 0 8px 20px rgba(0,0,0,.15);
            position: relative; top: 24px;
        }
        .pending-header .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%   { transform: scale(1);   opacity: 1; }
            50%  { transform: scale(1.08); opacity: .85; }
            100% { transform: scale(1);   opacity: 1; }
        }
        .step {
            display: flex; align-items: flex-start; gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .step:last-child { border-bottom: none; }
        .step-num {
            flex-shrink: 0;
            width: 36px; height: 36px;
            background: rgba(4, 120, 87, .1);
            color: var(--teal);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }
        .step-icon { color: var(--teal); font-size: 1.1rem; margin-right: .35rem; }
        .status-chip {
            display: inline-flex; align-items: center; gap: .5rem;
            background: #fffbeb; color: #b45309;
            border: 1px solid #fde68a;
            border-radius: 999px;
            padding: .5rem 1rem; font-weight: 600; font-size: .9rem;
        }
        .btn-teal { background: var(--teal); color: #fff; border: none; border-radius: 10px; padding: .7rem 1.5rem; font-weight: 600; }
        .btn-teal:hover { background: var(--teal-dark); color: #fff; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card pending-card mb-4">
                <div class="pending-header">
                    <div class="icon-circle">
                        <i class="bi bi-hourglass-split pulse"></i>
                    </div>
                </div>
                <div class="card-body px-4 px-md-5 pt-5 pb-4 text-center">
                    <span class="status-chip mb-3">
                        <i class="bi bi-clock-history"></i> Awaiting Approval
                    </span>
                    <h2 class="fw-bold mb-2" style="color:var(--teal);">Registration Submitted!</h2>
                    <p class="text-muted mb-0">
                        We've received your details and matched you to a waste contractor in your area.
                        They'll review your registration and email your login credentials once approved.
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success mt-4 mb-0 text-start">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-2"><i class="bi bi-list-check step-icon"></i>What happens next?</h5>
                    <div class="step">
                        <div class="step-num">1</div>
                        <div>
                            <div class="fw-semibold">Contractor review</div>
                            <div class="text-muted small">Your contractor checks your registration details and location.</div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div>
                        <div>
                            <div class="fw-semibold">Approval email</div>
                            <div class="text-muted small">Once approved, you'll receive an <strong>email with your login credentials</strong> (email + a temporary password).</div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div>
                        <div>
                            <div class="fw-semibold">Log in &amp; get started</div>
                            <div class="text-muted small">Sign in, change your password, and view schedules, invoices, and more.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-light border d-flex align-items-start gap-2 small text-muted">
                <i class="bi bi-envelope-paper text-teal" style="color:var(--teal);"></i>
                <div>
                    Didn't get an email after approval? Check your <strong>spam/junk</strong> folder, and make sure the
                    email address you registered with is correct.
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('client.login') }}" class="btn btn-teal">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Go to Login
                </a>
                <a href="{{ url('/') }}" class="btn btn-link text-muted text-decoration-none">
                    <i class="bi bi-house me-1"></i>Back to Home
                </a>
            </div>

        </div>
    </div>
</div>
</body>
</html>
