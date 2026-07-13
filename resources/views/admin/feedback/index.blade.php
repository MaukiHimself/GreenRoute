<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Feedback - GreenRoute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary-teal: #047857; }
        body { background: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .admin-container { max-width: 1400px; margin: 2rem auto; padding: 0 2rem; }
        .back-link { margin-bottom: 1.5rem; }
        .back-link a { color: var(--primary-teal); text-decoration: none; font-weight: 500; }
        .back-link a:hover { text-decoration: underline; }
        .page-title { font-size: 2rem; font-weight: 600; color: var(--primary-teal); margin-bottom: 0.5rem; }
        .page-description { color: #666; margin-bottom: 2rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
        .stat-card { background: white; border-radius: 12px; padding: 1.25rem 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 4px solid var(--primary-teal); }
        .stat-card.orange { border-left-color: #f59e0b; }
        .stat-card.blue { border-left-color: #3b82f6; }
        .stat-card.green { border-left-color: #10b981; }
        .stat-label { font-size: 0.85rem; color: #666; margin-bottom: 0.35rem; }
        .stat-value { font-size: 2rem; font-weight: bold; color: #1e293b; }
        .table-container { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link">
            <a href="{{ route('dashboard.admin') }}"><i class="bi bi-arrow-left me-1"></i>Back to Dashboard</a>
        </div>

        <h1 class="page-title">System Feedback</h1>
        <p class="page-description">Problems and suggestions submitted by clients and contractors about the GreenRoute platform.</p>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card orange">
                <div class="stat-label">Open</div>
                <div class="stat-value">{{ $counts['open'] }}</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-label">Responded</div>
                <div class="stat-value">{{ $counts['responded'] }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Resolved</div>
                <div class="stat-value">{{ $counts['resolved'] }}</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Received</th>
                            <th>From</th>
                            <th>Role</th>
                            <th>Category</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedback as $item)
                            @php
                                $statusClass = match($item->status) {
                                    'open' => 'bg-warning text-dark',
                                    'responded' => 'bg-info text-dark',
                                    'resolved' => 'bg-success',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted small">{{ $item->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $item->user->name ?? 'Unknown' }}</td>
                                <td><span class="badge bg-light text-dark border text-capitalize">{{ $item->role }}</span></td>
                                <td class="text-muted">{{ $item->category ?? '—' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($item->subject, 45) }}</td>
                                <td><span class="badge {{ $statusClass }} text-capitalize">{{ $item->status }}</span></td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.feedback.show', $item) }}" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>No system feedback yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $feedback->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
