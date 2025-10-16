<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - AFIA ORBIT Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .container { max-width: 1200px; margin: 3rem auto; padding: 0 2rem; }
        .back-link { margin-bottom: 2rem; }
        .back-link a { color: #055c5c; text-decoration: none; font-weight: 500; }
        .back-link a:hover { text-decoration: underline; }
        .page-title { font-size: 2rem; font-weight: 600; color: #055c5c; margin-bottom: 0.5rem; }
        .page-description { color: #666; margin-bottom: 2rem; }
        .empty-state { background: #ffffff; border-radius: 12px; padding: 4rem 2rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .empty-state i { font-size: 4rem; color: #d1d5db; margin-bottom: 1rem; }
        .empty-state h3 { color: #6b7280; margin-bottom: 0.5rem; }
        .empty-state p { color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-link">
            <a href="{{ route('dashboard.admin') }}"><i class="bi bi-arrow-left me-2"></i>Back to Dashboard</a>
        </div>
        
        <h1 class="page-title">Users Management</h1>
        <p class="page-description">Manage all system users (Admins, Contractors, Clients)</p>

        <div class="empty-state">
            <i class="bi bi-person-gear"></i>
            <h3>User Management</h3>
            <p>User management interface coming soon.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
