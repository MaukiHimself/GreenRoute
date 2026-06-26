<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #047857;
            --secondary-color: #c0392b;
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
            padding: 0;
            margin: 0;
        }
        
        .container {
            max-width: 1200px;
            padding: 2rem;
        }
        
        /* Header Section */
        .page-header {
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .page-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        /* Details Section */
        .details-section {
            background: var(--white-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 2px solid var(--light-bg);
            background: linear-gradient(135deg, var(--primary-color), #087272);
            color: var(--white-color);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }
        
        .btn-warning {
            background: #d97706;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            background: #b45309;
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-secondary {
            background: var(--text-muted);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #475569;
            transform: translateY(-2px);
            color: white;
        }
        
        /* Content Sections */
        .content-section {
            padding: 2rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .info-group {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .info-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--text-dark);
            flex: 1;
        }
        
        .info-value {
            color: var(--text-muted);
            flex: 1;
            text-align: right;
        }
        
        .info-value.na {
            color: #94a3b8;
            font-style: italic;
        }
        
        /* Status Badge */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-active {
            background: var(--primary-color);
            color: white;
        }
        
        .status-inactive {
            background: var(--text-muted);
            color: white;
        }
        
        /* Category Badge */
        .category-badge {
            background: rgba(5, 92, 92, 0.1);
            color: var(--primary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        /* Notes Section */
        .notes-section {
            background: rgba(5, 92, 92, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .notes-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .notes-content {
            color: var(--text-dark);
            line-height: 1.6;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        /* Coordinates Display */
        .coordinates {
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .container {
                padding: 1.5rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .action-buttons {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .content-section {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .info-item {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .info-value {
                text-align: left;
            }
        }
        
        @media (max-width: 480px) {
            .content-section {
                padding: 1rem;
            }
            
            .info-group {
                padding: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <h1 class="page-title">Client Details</h1>
            <a href="{{ route('dashboard.contractor') }}" class="btn btn-outline-dark d-flex align-items-center gap-2" style="border-color: #cbd5e1;">
                <i class="bi bi-house-door-fill" style="color: var(--primary-color);"></i> Home
            </a>
        </div>

        <!-- Client Details Section -->
        <div class="details-section">
            <!-- Header with Actions -->
            <div class="section-header">
                <h2 class="section-title">
                    <i class="bi bi-person-badge"></i>Client Information
                </h2>
                <div class="action-buttons">
                    <a href="/contractor/clients/{{ $client->id }}/edit" class="btn-warning">
                        <i class="bi bi-pencil"></i> Edit Client
                    </a>
                    <a href="javascript:history.back()" class="btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            
            <!-- Content -->
            <div class="content-section">
                <!-- Basic and Contact Information -->
                <div class="info-grid">
                    <!-- Basic Information -->
                    <div class="info-group">
                        <h3 class="info-title">
                            <i class="bi bi-info-circle"></i>Basic Information
                        </h3>
                        <div class="info-item">
                            <span class="info-label">Registration Number</span>
                            <span class="info-value">{{ $client->registration_number }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Business Name</span>
                            <span class="info-value">{{ $client->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Contact Person</span>
                            <span class="info-value">{{ $client->contact_name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Category</span>
                            <span class="category-badge">{{ ucfirst($client->category) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="status-badge {{ $client->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ ucfirst($client->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="info-group">
                        <h3 class="info-title">
                            <i class="bi bi-telephone"></i>Contact Information
                        </h3>
                        <div class="info-item">
                            <span class="info-label">Phone 1</span>
                            <span class="info-value">{{ $client->phone }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone 2</span>
                            <span class="info-value {{ !$client->phone_2 ? 'na' : '' }}">
                                {{ $client->phone_2 ?: 'N/A' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone 3</span>
                            <span class="info-value {{ !$client->phone_3 ? 'na' : '' }}">
                                {{ $client->phone_3 ?: 'N/A' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email 1</span>
                            <span class="info-value">{{ $client->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email 2</span>
                            <span class="info-value {{ !$client->email_2 ? 'na' : '' }}">
                                {{ $client->email_2 ?: 'N/A' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email 3</span>
                            <span class="info-value {{ !$client->email_3 ? 'na' : '' }}">
                                {{ $client->email_3 ?: 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Location and Record Information -->
                <div class="info-grid">
                    <!-- Location Information -->
                    <div class="info-group">
                        <h3 class="info-title">
                            <i class="bi bi-geo-alt"></i>Location Information
                        </h3>
                        <div class="info-item">
                            <span class="info-label">Address</span>
                            <span class="info-value">{{ $client->address }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">City</span>
                            <span class="info-value {{ !$client->city ? 'na' : '' }}">
                                {{ $client->city ?: 'N/A' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">State</span>
                            <span class="info-value {{ !$client->state ? 'na' : '' }}">
                                {{ $client->state ?: 'N/A' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">ZIP Code</span>
                            <span class="info-value {{ !$client->zip_code ? 'na' : '' }}">
                                {{ $client->zip_code ?: 'N/A' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Coordinates</span>
                            <span class="coordinates">{{ $client->latitude }}, {{ $client->longitude }}</span>
                        </div>
                    </div>
                    
                    <!-- Record Information -->
                    <div class="info-group">
                        <h3 class="info-title">
                            <i class="bi bi-clock-history"></i>Record Information
                        </h3>
                        <div class="info-item">
                            <span class="info-label">Created</span>
                            <span class="info-value">{{ $client->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Last Updated</span>
                            <span class="info-value">{{ $client->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Notes Section -->
                @if($client->notes)
                <div class="notes-section">
                    <h3 class="notes-title">
                        <i class="bi bi-journal-text"></i>Additional Notes
                    </h3>
                    <div class="notes-content">
                        {{ $client->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Add some interactive enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add click effect to action buttons
            const actionButtons = document.querySelectorAll('.action-buttons .btn');
            actionButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Add ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.6);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Add copy functionality for coordinates
            const coordinates = document.querySelector('.coordinates');
            if (coordinates) {
                coordinates.style.cursor = 'pointer';
                coordinates.title = 'Click to copy coordinates';
                
                coordinates.addEventListener('click', function() {
                    const text = this.textContent;
                    navigator.clipboard.writeText(text).then(() => {
                        const originalText = this.textContent;
                        this.textContent = 'Copied!';
                        this.style.color = 'var(--primary-color)';
                        
                        setTimeout(() => {
                            this.textContent = originalText;
                            this.style.color = 'var(--primary-color)';
                        }, 2000);
                    });
                });
            }
        });
        
        // Add CSS for ripple effect
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>