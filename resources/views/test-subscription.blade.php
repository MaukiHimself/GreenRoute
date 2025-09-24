<!DOCTYPE html>
<html>
<head>
    <title>Test Subscription System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Subscription System Test</h3>
                    </div>
                    <div class="card-body">
                        @if(auth()->check())
                            <div class="alert alert-success">
                                <strong>Logged in as:</strong> {{ auth()->user()->name }} ({{ auth()->user()->user_type }})
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>User Details:</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item">Email: {{ auth()->user()->email }}</li>
                                        <li class="list-group-item">User Type: {{ auth()->user()->user_type }}</li>
                                        <li class="list-group-item">Subscription Completed: 
                                            <span class="badge {{ auth()->user()->subscription_completed ? 'bg-success' : 'bg-warning' }}">
                                                {{ auth()->user()->subscription_completed ? 'Yes' : 'No' }}
                                            </span>
                                        </li>
                                        <li class="list-group-item">Subscription Status: 
                                            <span class="badge bg-info">{{ auth()->user()->subscription_status }}</span>
                                        </li>
                                        <li class="list-group-item">Remember Login: 
                                            <span class="badge {{ auth()->user()->remember_login ? 'bg-success' : 'bg-secondary' }}">
                                                {{ auth()->user()->remember_login ? 'Yes' : 'No' }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Actions:</h5>
                                    <div class="d-grid gap-2">
                                        @if(auth()->user()->needsSubscription())
                                            <a href="{{ route('subscription.profile') }}" class="btn btn-warning">
                                                Complete Subscription
                                            </a>
                                        @else
                                            <span class="badge bg-success p-2">Subscription Complete</span>
                                        @endif
                                        
                                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                            Go to Dashboard
                                        </a>
                                        
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger w-100">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <strong>Not logged in.</strong> Please login to test the subscription system.
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('login.client') }}" class="btn btn-outline-primary">Login as Client</a>
                                <a href="{{ route('login.contractor') }}" class="btn btn-outline-success">Login as Contractor</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>