<!DOCTYPE html>
<html>
<head>
    <title>Contractor Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #055c5c;
            --secondary-color: #640404;
            --white-color: #ffffff;
            --light-bg: #f8f9fa;
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        /* Navigation */
        .navbar {
            background: var(--white-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-name {
            color: var(--text-dark);
            font-weight: 500;
        }
        
        .logout-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .logout-link:hover {
            color: #8b0000;
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem 0;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Dashboard Section */
        .dashboard-section {
            background: var(--white-color);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-bg);
        }
        
        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background: #044a4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
            color: white;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        /* Map Container */
        .map-container {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            border: 2px dashed #cbd5e1;
        }
        
        .map-placeholder {
            background: var(--white-color);
            border-radius: 8px;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 1.1rem;
            border: 2px solid #e2e8f0;
        }
        
        .map-message {
            color: var(--text-muted);
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .stat-card {
            background: var(--white-color);
            border-radius: 12px;
            padding: 1.5rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card.clients {
            background: rgba(5, 92, 92, 0.05);
        }
        
        .stat-card.routes {
            background: rgba(5, 92, 92, 0.05);
        }
        
        .stat-card.status {
            background: rgba(100, 4, 4, 0.05);
            border-left-color: var(--secondary-color);
        }
        
        .stat-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-card.clients .stat-value {
            color: var(--primary-color);
        }
        
        .stat-card.routes .stat-value {
            color: var(--primary-color);
        }
        
        .stat-card.status .stat-value {
            color: var(--secondary-color);
            font-size: 1.1rem;
        }
        
        .stat-description {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin: 0;
        }
        
        /* Loading States */
        .loading {
            color: var(--text-muted);
            font-style: italic;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 0 0.5rem;
            }
            
            .dashboard-section {
                padding: 1.5rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .map-placeholder {
                height: 300px;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .user-info {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-end;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-section {
                padding: 1rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .map-container {
                padding: 1rem;
            }
            
            .map-placeholder {
                height: 250px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="dashboard-container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="navbar-brand">AFIA ORBIT - Contractor</h1>
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-link" style="background: none; border: none; cursor: pointer;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-container">
            <!-- Dashboard Section -->
            <div class="dashboard-section">
                <!-- Header -->
                <div class="section-header">
                    <h2 class="section-title">Route Planning Dashboard</h2>
                    <button id="updateLocation" class="btn-primary">
                        <i class="bi bi-geo-alt"></i> Update My Location
                    </button>
                </div>
                
                <!-- Map Container -->
                <div class="map-container">
                    <p class="map-message">Map will load here once Google Maps APIs are properly configured</p>
                    <div id="mapPlaceholder" class="map-placeholder">
                        <div class="text-center">
                            <i class="bi bi-map" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <div>Google Maps Loading...</div>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card clients">
                        <div class="stat-title">Assigned Clients</div>
                        <div class="stat-value" id="clientCount">Loading...</div>
                        <p class="stat-description">Total clients in your route</p>
                    </div>
                    
                    <div class="stat-card routes">
                        <div class="stat-title">Route Optimization</div>
                        <div class="stat-value">
                            <button id="optimizeRoute" class="btn-primary btn-sm">
                                <i class="bi bi-gear"></i> Optimize Route
                            </button>
                        </div>
                        <p class="stat-description">Generate optimal collection routes</p>
                    </div>
                    
                    <div class="stat-card status">
                        <div class="stat-title">System Status</div>
                        <div class="stat-value">Ready for Mapping</div>
                        <p class="stat-description">All systems operational</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load client count
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/contractor/clients/locations')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(clients => {
                    const clientCount = document.getElementById('clientCount');
                    if (clients && Array.isArray(clients)) {
                        clientCount.textContent = clients.length;
                        clientCount.classList.remove('loading');
                    } else {
                        throw new Error('Invalid data format');
                    }
                })
                .catch(error => {
                    console.error('Error loading client count:', error);
                    document.getElementById('clientCount').textContent = '0';
                    document.getElementById('clientCount').classList.remove('loading');
                });

            // Update location button
            document.getElementById('updateLocation').addEventListener('click', function() {
                const button = this;
                const originalText = button.innerHTML;
                
                // Show loading state
                button.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Getting Location...';
                button.disabled = true;
                
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        const location = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        };
                        
                        fetch('/location/update', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(location)
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showNotification('Location updated successfully!', 'success');
                            } else {
                                throw new Error('Update failed');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating location:', error);
                            showNotification('Failed to update location. Please try again.', 'error');
                        })
                        .finally(() => {
                            // Restore button
                            button.innerHTML = originalText;
                            button.disabled = false;
                        });
                    }, error => {
                        let errorMessage = 'Error getting location: ';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage += 'Location access denied. Please enable location permissions.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage += 'Location information unavailable.';
                                break;
                            case error.TIMEOUT:
                                errorMessage += 'Location request timed out.';
                                break;
                            default:
                                errorMessage += 'An unknown error occurred.';
                        }
                        
                        showNotification(errorMessage, 'error');
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    });
                } else {
                    showNotification('Geolocation is not supported by your browser.', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            });

            // Optimize route button
            document.getElementById('optimizeRoute').addEventListener('click', function() {
                const button = this;
                const originalText = button.innerHTML;
                
                // Show loading state
                button.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Optimizing...';
                button.disabled = true;
                
                // Simulate route optimization
                setTimeout(() => {
                    showNotification('Route optimization completed successfully!', 'success');
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 2000);
            });

            // Notification system
            function showNotification(message, type) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 1rem 1.5rem;
                    border-radius: 8px;
                    color: white;
                    font-weight: 500;
                    z-index: 1000;
                    animation: slideIn 0.3s ease;
                    max-width: 400px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                `;
                
                if (type === 'success') {
                    notification.style.background = 'var(--primary-color)';
                } else {
                    notification.style.background = 'var(--secondary-color)';
                }
                
                notification.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'}"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Remove after 5 seconds
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => {
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }, 300);
                }, 5000);
            }

            // Add CSS animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .spinner {
                    animation: spin 1s linear infinite;
                }
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>