<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Submitted — GreenRoute</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --teal: #047857; }
        body { background: linear-gradient(135deg, #f0f9f9, #e2e8f0); min-height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .icon-circle { width: 100px; height: 100px; background: rgba(5,92,92,.1); border: 3px solid var(--teal); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 2.5rem; color: var(--teal); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="icon-circle mb-4"><i class="bi bi-hourglass-split"></i></div>
            <h2 class="fw-bold" style="color:var(--teal);">Registration Submitted!</h2>
            <p class="text-muted mt-2 mb-4">Your registration has been received. Your contractor will review it and send you an email with your login credentials once approved.</p>
            <div class="card border-0 shadow-sm p-4 text-start mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2" style="color:var(--teal)"></i>What happens next?</h5>
                <div class="d-flex mb-3">
                    <div class="me-3 fw-bold" style="color:var(--teal);font-size:1.3rem;">1</div>
                    <div>Your contractor reviews your registration details and location.</div>
                </div>
                <div class="d-flex mb-3">
                    <div class="me-3 fw-bold" style="color:var(--teal);font-size:1.3rem;">2</div>
                    <div>Once approved, you will receive an <strong>email with your login credentials</strong> (email + temporary password).</div>
                </div>
                <div class="d-flex">
                    <div class="me-3 fw-bold" style="color:var(--teal);font-size:1.3rem;">3</div>
                    <div>Log in and change your password. You can then view schedules, invoices, and more.</div>
                </div>
            </div>
            <a href="{{ route('client.login') }}" class="btn btn-outline-secondary">
                <i class="bi bi-box-arrow-in-right me-2"></i>Go to Login
            </a>
        </div>
    </div>
</div>
</body>
</html>
