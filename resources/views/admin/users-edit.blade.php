<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - GreenRoute Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #047857;
            --primary-red: #c0392b;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .back-link {
            margin-bottom: 1.5rem;
        }
        
        .back-link a {
            color: var(--primary-teal);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .page-description {
            color: #666;
        }
        
        .user-id {
            background: #e6f2f2;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            text-align: right;
        }
        
        .user-id strong {
            color: var(--primary-teal);
            display: block;
            font-size: 0.875rem;
        }
        
        .user-id span {
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section h3 {
            font-size: 1.2rem;
            color: var(--primary-teal);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-teal);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-group label .required {
            color: #ef4444;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-teal);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .btn-submit {
            background: var(--primary-teal);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-right: 1rem;
        }
        
        .btn-submit:hover {
            background: #065f46;
        }
        
        .btn-cancel {
            background: #6b7280;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-cancel:hover {
            background: #4b5563;
            color: white;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .help-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        .info-box {
            background: #e6f2f2;
            border-left: 4px solid var(--primary-teal);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .info-box p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .warning-box p {
            margin: 0;
            color: #92400e;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link">
            <a href="{{ route('admin.users') }}">
                <i class="bi bi-arrow-left me-2"></i>Back to Users List
            </a>
        </div>
        
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit User</h1>
                <p class="page-description">Update user information and settings</p>
            </div>
            <div class="user-id">
                <strong>User ID</strong>
                <span>#{{ $user->id }}</span>
            </div>
        </div>

        <div class="info-box">
            <p><i class="bi bi-info-circle me-2"></i>
                Update user details including name, email, type, and status. Changes will be applied immediately.
            </p>
        </div>

        @if($user->id === auth()->id())
            <div class="warning-box">
                <p><i class="bi bi-exclamation-triangle me-2"></i>
                    You are editing your own account. Be careful when changing your user type or status.
                </p>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section">
                    <h3><i class="bi bi-person me-2"></i>Basic Information</h3>
                    
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Username <span class="required">*</span></label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Settings -->
                <div class="form-section">
                    <h3><i class="bi bi-gear me-2"></i>Account Settings</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>User Type <span class="required">*</span></label>
                            <select name="user_type" class="form-control" required>
                                <option value="admin" {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                <option value="contractor" {{ old('user_type', $user->user_type) == 'contractor' ? 'selected' : '' }}>Contractor</option>
                                <option value="client" {{ old('user_type', $user->user_type) == 'client' ? 'selected' : '' }}>Client</option>
                            </select>
                            <div class="help-text">User role and permissions level</div>
                            @error('user_type')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Account Status</label>
                            <select name="status" class="form-control">
                                <option value="">Not Set</option>
                                <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $user->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <div class="help-text">Approval status for contractors</div>
                            @error('status')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Subscription Status</label>
                        <select name="subscription_status" class="form-control">
                            <option value="">Not Set</option>
                            <option value="active" {{ old('subscription_status', $user->subscription_status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('subscription_status', $user->subscription_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="expired" {{ old('subscription_status', $user->subscription_status) == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                        <div class="help-text">Current subscription status</div>
                        @error('subscription_status')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="form-section">
                    <h3><i class="bi bi-info-circle me-2"></i>Account Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Registered</label>
                            <input type="text" class="form-control" value="{{ $user->created_at->format('M d, Y h:i A') }}" disabled>
                            <div class="help-text">{{ $user->created_at->diffForHumans() }}</div>
                        </div>

                        <div class="form-group">
                            <label>Last Updated</label>
                            <input type="text" class="form-control" value="{{ $user->updated_at->format('M d, Y h:i A') }}" disabled>
                            <div class="help-text">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-section">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle me-2"></i>Update User
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn-cancel">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
