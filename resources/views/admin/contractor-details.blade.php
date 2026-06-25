<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Details - GreenRoute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #c0392b;
            --white: #ffffff;
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: var(--primary-teal);
            font-weight: 700;
        }

        .btn-primary-custom {
            background-color: var(--primary-teal);
            border-color: var(--primary-teal);
            color: white;
        }

        .btn-primary-custom:hover {
            background-color: #044545;
            border-color: #044545;
            color: white;
        }

        .btn-danger-custom {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
            color: white;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-suspended {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            background-color: var(--primary-teal);
            color: white;
            border-bottom: none;
        }

        .detail-row {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }

        .detail-value {
            color: #555;
            font-size: 1rem;
        }

        .document {
            padding: 0.75rem;
            background: var(--light-bg);
            border-radius: 4px;
            margin: 0.5rem 0;
        }

        .document a {
            color: var(--primary-teal);
            text-decoration: none;
        }

        .document a:hover {
            text-decoration: underline;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="has-portal-sidebar">
    <x-portal-sidebar portal="admin" />

    <div class="admin-layout">
        <div class="main-content portal-main">
            <div class="d-lg-none mb-3">
                <x-portal-mobile-toggle />
            </div>
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('admin.verification') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Verification
                </a>
            </div>

            <!-- Header -->
            <div class="header">
                <h1>Contractor Details</h1>
                <span class="status-badge status-{{ strtolower($user->status) }}">
                    {{ ucfirst($user->status) }}
                </span>
            </div>

            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Personal Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Personal Information</h5>
                </div>
                <div class="card-body p-0">
                    <div class="detail-row">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value">{{ $user->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">{{ $user->email }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Username</div>
                        <div class="detail-value">{{ $user->username }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Registration Date</div>
                        <div class="detail-value">{{ $user->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">{{ $user->updated_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>

            <!-- Contractor Information -->
            @if ($user->contractor)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-building"></i> Contractor Information</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="detail-row">
                            <div class="detail-label">Company Name</div>
                            <div class="detail-value">{{ $user->contractor->company_name ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Registration Number</div>
                            <div class="detail-value">{{ $user->contractor->registration_number ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value">{{ $user->contractor->phone ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Location</div>
                            <div class="detail-value">
                                @if ($user->contractor->region || $user->contractor->district)
                                    {{ $user->contractor->region }} - {{ $user->contractor->district }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Documents -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-earmark"></i> Documents</h5>
                </div>
                <div class="card-body">
                    @if ($user->business_license)
                        <div class="document">
                            <i class="bi bi-file-pdf"></i>
                            <a href="{{ asset('storage/' . $user->business_license) }}" target="_blank">
                                Business License
                            </a>
                        </div>
                    @endif

                    @if ($user->certificate_incorporation)
                        <div class="document">
                            <i class="bi bi-file-pdf"></i>
                            <a href="{{ asset('storage/' . $user->certificate_incorporation) }}" target="_blank">
                                Certificate of Incorporation
                            </a>
                        </div>
                    @endif

                    @if ($user->contract_document)
                        <div class="document">
                            <i class="bi bi-file-pdf"></i>
                            <a href="{{ asset('storage/' . $user->contract_document) }}" target="_blank">
                                Contract Document
                            </a>
                        </div>
                    @endif

                    @if (!$user->business_license && !$user->certificate_incorporation && !$user->contract_document)
                        <p class="text-muted">No documents uploaded</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if ($user->status === 'pending')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-check-circle"></i> Verification Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="action-buttons">
                            <form action="{{ route('admin.contractors.approve', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this contractor?')">
                                    <i class="bi bi-check"></i> Approve Contractor
                                </button>
                            </form>

                            <form action="{{ route('admin.contractors.reject', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this contractor?')">
                                    <i class="bi bi-x"></i> Reject Contractor
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif ($user->status === 'approved')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-shield-check"></i> Status Management</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.contractors.toggle', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Suspend this contractor?')">
                                <i class="bi bi-pause-circle"></i> Suspend Contractor
                            </button>
                        </form>
                    </div>
                </div>
            @elseif ($user->status === 'suspended')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-shield-check"></i> Status Management</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.contractors.toggle', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Reactivate this contractor?')">
                                <i class="bi bi-play-circle"></i> Reactivate Contractor
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
