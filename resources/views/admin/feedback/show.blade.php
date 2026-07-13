<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Detail - GreenRoute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary-teal: #047857; }
        body { background: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .admin-container { max-width: 900px; margin: 2rem auto; padding: 0 2rem; }
        .back-link { margin-bottom: 1.5rem; }
        .back-link a { color: var(--primary-teal); text-decoration: none; font-weight: 500; }
        .back-link a:hover { text-decoration: underline; }
        .page-title { font-size: 1.75rem; font-weight: 600; color: var(--primary-teal); margin-bottom: 1.5rem; }
        .card-soft { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: none; }
        .meta-label { font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: .03em; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link">
            <a href="{{ route('admin.feedback') }}"><i class="bi bi-arrow-left me-1"></i>Back to System Feedback</a>
        </div>

        <h1 class="page-title">Feedback Detail</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $statusClass = match($feedback->status) {
                'open' => 'bg-warning text-dark',
                'responded' => 'bg-info text-dark',
                'resolved' => 'bg-success',
                default => 'bg-secondary',
            };
        @endphp

        <div class="card card-soft mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="mb-0">{{ $feedback->subject }}</h4>
                    <span class="badge {{ $statusClass }} text-capitalize">{{ $feedback->status }}</span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="meta-label">From</div>
                        <div>{{ $feedback->user->name ?? 'Unknown' }}</div>
                        <div class="text-muted small">{{ $feedback->user->email ?? '' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="meta-label">Role</div>
                        <div class="text-capitalize">{{ $feedback->role }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="meta-label">Category</div>
                        <div>{{ $feedback->category ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="meta-label">Received</div>
                        <div>{{ $feedback->created_at->format('M d, Y H:i') }}</div>
                    </div>
                </div>

                <div class="meta-label">Message</div>
                <p class="mb-0" style="white-space: pre-line;">{{ $feedback->message }}</p>
            </div>
        </div>

        @if($feedback->admin_response)
            <div class="card card-soft mb-4" style="border-left: 4px solid var(--primary-teal);">
                <div class="card-body p-4">
                    <div class="meta-label" style="color: var(--primary-teal);"><i class="bi bi-reply me-1"></i>Current Response</div>
                    <p class="mb-2" style="white-space: pre-line;">{{ $feedback->admin_response }}</p>
                    <div class="text-muted small">
                        {{ $feedback->responder->name ?? 'Admin' }}
                        @if($feedback->responded_at) · {{ $feedback->responded_at->format('M d, Y H:i') }} @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="card card-soft">
            <div class="card-body p-4">
                <h5 class="mb-3">{{ $feedback->admin_response ? 'Update response' : 'Reply' }}</h5>
                <form method="POST" action="{{ route('admin.feedback.respond', $feedback) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Response</label>
                        <textarea name="admin_response" rows="5" class="form-control @error('admin_response') is-invalid @enderror" placeholder="Write your reply to the submitter" required>{{ old('admin_response', $feedback->admin_response) }}</textarea>
                        @error('admin_response')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="responded" {{ $feedback->status === 'responded' ? 'selected' : '' }}>Responded</option>
                            <option value="resolved" {{ $feedback->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="open" {{ $feedback->status === 'open' ? 'selected' : '' }}>Open</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn text-white" style="background: var(--primary-teal);">
                            <i class="bi bi-send me-1"></i>Send Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
