<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unassigned Clients - GreenRoute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary-teal: #047857; --primary-red: #c0392b; }
        body { background: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .admin-container { max-width: 1400px; margin: 2rem auto; padding: 0 2rem; }
        .back-link a { color: var(--primary-teal); text-decoration: none; font-weight: 600; }
        .page-title { font-weight: 700; color: #111827; margin-bottom: .25rem; }
        .page-description { color: #6b7280; }
        .card-box { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); padding: 1.25rem; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #f9fafb; text-align: left; padding: .75rem; font-size: .85rem; color: #374151; border-bottom: 2px solid #e5e7eb; }
        tbody td { padding: .75rem; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        .muted { color: #6b7280; font-size: .85rem; }
        .badge-ward { background: #ecfdf5; color: var(--primary-teal); padding: .2rem .5rem; border-radius: 6px; font-size: .8rem; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link mb-3">
            <a href="{{ route('admin.clients') }}"><i class="bi bi-arrow-left me-2"></i>Back to Clients</a>
        </div>

        <div class="page-header mb-4">
            <h1 class="page-title">Unassigned Clients</h1>
            <p class="page-description">
                Self-registered clients whose area isn't covered by any active contractor route yet.
                Assign each to a contractor — they'll then appear in that contractor's pending list for approval.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card-box">
            @if($clients->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Contact</th>
                            <th>Area</th>
                            <th>Registered</th>
                            <th style="min-width:320px;">Assign to contractor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td>
                                    <div><strong>{{ $client->name }}</strong></div>
                                    <div class="muted">{{ $client->category }}</div>
                                </td>
                                <td>
                                    <div><i class="bi bi-telephone me-1"></i>{{ $client->phone }}</div>
                                    <div class="muted"><i class="bi bi-envelope me-1"></i>{{ $client->email }}</div>
                                </td>
                                <td>
                                    <div><span class="badge-ward">{{ $client->ward ?? '—' }}</span></div>
                                    <div class="muted">{{ $client->district }}{{ $client->district && $client->region ? ', ' : '' }}{{ $client->region }}</div>
                                </td>
                                <td class="muted">{{ $client->created_at?->diffForHumans() }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.clients.assign', $client) }}" class="d-flex gap-2">
                                        @csrf
                                        <select name="contractor_id" class="form-select form-select-sm" required>
                                            <option value="">— choose contractor —</option>
                                            @foreach($contractors as $c)
                                                <option value="{{ $c->user_id }}"
                                                    {{ $client->suggested_contractor_id == $c->user_id ? 'selected' : '' }}>
                                                    {{ $c->company_name ?? $c->name }}{{ $c->district ? ' — '.$c->district : '' }}
                                                    {{ $client->suggested_contractor_id == $c->user_id ? '  (suggested)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm text-white" style="background: var(--primary-teal); white-space:nowrap;">
                                            <i class="bi bi-person-check me-1"></i>Assign
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">{{ $clients->links() }}</div>
            @else
                <div class="text-center py-5 muted">
                    <i class="bi bi-check2-circle" style="font-size:2.5rem; color: var(--primary-teal);"></i>
                    <p class="mt-2 mb-0">No unassigned clients. Every self-registered client has a contractor.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
