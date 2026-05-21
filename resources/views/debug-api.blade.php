<!DOCTYPE html>
<html>
<head>
    <title>API Debug</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>API Endpoints Debug</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test API Endpoints</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2" onclick="testEndpoint('/contractor/clients/locations', 'clients-result')">Test Clients API</button>
                        <button class="btn btn-primary mb-2" onclick="testEndpoint('/contractor/dashboard-stats', 'stats-result')">Test Stats API</button>
                        <button class="btn btn-primary mb-2" onclick="testEndpoint('/trucks/locations', 'trucks-result')">Test Trucks API</button>
                        <button class="btn btn-primary mb-2" onclick="testEndpoint('/routes/optimize', 'routes-result', 'POST', {site_location: 'test'})">Test Routes API</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Results</h5>
                    </div>
                    <div class="card-body">
                        <div id="clients-result" class="mb-3"></div>
                        <div id="stats-result" class="mb-3"></div>
                        <div id="trucks-result" class="mb-3"></div>
                        <div id="routes-result" class="mb-3"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Environment Check</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Maps:</strong> OpenStreetMap + Leaflet (free, no API key required)</p>
                        <p><strong>App Environment:</strong> {{ env('APP_ENV') }}</p>
                        <p><strong>Database:</strong> {{ env('DB_CONNECTION') }}</p>
                        <p><strong>Current User:</strong> {{ auth()->check() ? auth()->user()->name . ' (' . auth()->user()->user_type . ')' : 'Not logged in' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testEndpoint(url, resultId, method = 'GET', data = null) {
            const resultDiv = document.getElementById(resultId);
            resultDiv.innerHTML = '<div class="alert alert-info">Testing...</div>';
            
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            };
            
            if (data && method === 'POST') {
                options.body = JSON.stringify(data);
            }
            
            fetch(url, options)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <strong>✓ ${url}</strong><br>
                            <small>${JSON.stringify(data, null, 2)}</small>
                        </div>
                    `;
                })
                .catch(error => {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <strong>✗ ${url}</strong><br>
                            <small>${error.message}</small>
                        </div>
                    `;
                });
        }
    </script>
</body>
</html>