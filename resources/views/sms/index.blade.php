<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Manager</title>
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
        
        .container-fluid {
            padding: 2rem;
            max-width: 1400px;
        }
        
        /* Header Section */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), #087272);
            color: var(--white-color);
            padding: 2.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(5, 92, 92, 0.2);
        }
        
        .page-title {
            font-size: 2.25rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }
        
        /* Content Sections */
        .content-section {
            background: var(--white-color);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-bg);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        .section-icon {
            margin-right: 0.75rem;
            font-size: 1.3rem;
        }
        
        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
        }
        
        /* Recipients Box */
        .recipients-container {
            background: var(--light-bg);
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 1.5rem;
            max-height: 280px;
            overflow-y: auto;
        }
        
        .recipients-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .recipients-count {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .client-item {
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .client-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(5, 92, 92, 0.1);
        }
        
        .client-item:last-child {
            margin-bottom: 0;
        }
        
        /* Message Textarea */
        .message-container {
            position: relative;
        }
        
        .message-textarea {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 1.25rem;
            font-size: 1rem;
            line-height: 1.5;
            resize: vertical;
            min-height: 180px;
            transition: all 0.3s ease;
        }
        
        .message-textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 92, 92, 0.1);
        }
        
        .message-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 0.75rem;
            font-size: 0.85rem;
        }
        
        .variables-info {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .char-count {
            color: var(--text-muted);
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #065f46;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.3);
        }
        
        .quick-action-btn {
            border-radius: 10px;
            padding: 1rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            text-align: left;
        }
        
        .quick-action-btn:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-warning {
            color: #d97706;
            border-color: #d97706;
        }
        
        .btn-outline-warning:hover {
            background: #d97706;
            border-color: #d97706;
            color: white;
        }
        
        .btn-outline-success {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-success:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* Template Items */
        .template-section {
            margin-bottom: 2rem;
        }
        
        .template-item {
            padding: 1.25rem;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 1rem;
            background: var(--light-bg);
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .template-item:hover {
            background: #e8f4f4;
            transform: translateX(5px);
        }
        
        .template-item:last-child {
            margin-bottom: 0;
        }
        
        .template-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .template-description {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin: 0;
        }
        
        /* Form Controls */
        .form-check {
            padding-left: 2rem;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-label {
            color: var(--text-dark);
            font-weight: 500;
        }
        
        /* Route Groups */
        .route-group {
            margin-bottom: 1.5rem;
        }
        
        .route-header {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            border-left: 3px solid var(--primary-color);
        }
        
        .route-header .badge {
            font-size: 0.85rem;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .container-fluid {
                padding: 1.5rem;
            }
            
            .page-header {
                padding: 2rem 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .container-fluid {
                padding: 1rem;
            }
            
            .content-section {
                padding: 1.5rem;
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="bi bi-chat-dots me-2"></i>SMS Manager
                    </h1>
                    <p class="page-subtitle mb-0">Send notifications and reminders to your clients</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard.contractor') }}" class="btn btn-outline-light d-flex align-items-center gap-2" style="border: 1px solid rgba(255,255,255,0.5);" target="_parent">
                        <i class="bi bi-house-door-fill"></i> Home
                    </a>
                    <a href="{{ route('sms.inbox') }}" class="btn btn-light" style="border-radius: 8px;" target="_parent">
                        <i class="bi bi-arrow-left me-2"></i>Back to Inbox
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content - Compose Message -->
            <div class="col-lg-8">
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="bi bi-pencil-square section-icon"></i>Compose Message
                        </h2>
                    </div>
                    
                    <form method="POST" action="{{ route('sms.send') }}">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-tag"></i>Message Type
                                </label>
                                <select class="form-select" id="messageType" name="message_type" onchange="loadTemplate()" required>
                                    <option value="">Choose template...</option>
                                    <option value="pickup_schedule">📅 Pickup Schedule</option>
                                    <option value="trash_reminder">🗑️ Trash Reminder</option>
                                    <option value="invoice_notification">📄 Invoice Notification</option>
                                    <option value="receipt_notification">🧾 Receipt Notification</option>
                                    <option value="payment_reminder">💳 Payment Reminder</option>
                                    <option value="sustainability_tip">🌱 Sustainability Tip</option>
                                    <option value="custom">✏️ Custom Message</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-funnel"></i>Filter by Route
                                </label>
                                <select class="form-select" id="routeFilter" onchange="filterByRoute()">
                                    <option value="all">All Routes ({{ $clients->count() }} clients)</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route }}">{{ $route }} ({{ $clientsByRoute[$route]->count() }} clients)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="recipients-container">
                                    <div class="recipients-header">
                                        <label class="form-label mb-0">
                                            <i class="bi bi-people"></i>Recipients
                                        </label>
                                        <span class="recipients-count" id="selectedCount">0 selected</span>
                                    </div>
                                    
                                    <div class="d-flex gap-2 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleAll()">
                                            <label class="form-check-label fw-bold text-primary" for="selectAll">
                                                Select All
                                            </label>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" onclick="selectByRoute()">
                                            <i class="bi bi-check-square"></i> Select Current Route
                                        </button>
                                    </div>
                                    
                                    <div class="clients-list" id="clientsList">
                                        @foreach($clientsByRoute as $route => $routeClients)
                                            <div class="route-group" data-route="{{ $route }}">
                                                @if($route)
                                                <div class="route-header">
                                                    <span class="badge bg-primary">{{ $route }}</span>
                                                    <small class="text-muted ms-2">{{ $routeClients->count() }} clients</small>
                                                </div>
                                                @endif
                                                @foreach($routeClients as $client)
                                                <div class="client-item" data-route="{{ $client->route ?? '' }}">
                                                    <div class="form-check">
                                                        <input class="form-check-input client-checkbox" type="checkbox" name="recipients[]" value="{{ $client->id }}" id="client{{ $client->id }}" onchange="updateCount()" data-route="{{ $client->route ?? '' }}">
                                                        <label class="form-check-label w-100" for="client{{ $client->id }}">
                                                            <div class="fw-semibold text-dark">{{ $client->name }}</div>
                                                            <div class="text-muted small">{{ $client->phone }}</div>
                                                            <div class="text-muted small">
                                                                {{ ucfirst($client->category) }}
                                                                @if($client->route)
                                                                    <span class="badge badge-sm bg-secondary">{{ $client->route }}</span>
                                                                @endif
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label">
                                <i class="bi bi-chat-text"></i>Message Content
                            </label>
                            <div class="message-container">
                                <textarea class="form-control message-textarea" id="message" name="message" rows="6" maxlength="1000" required placeholder="Type your message here..."></textarea>
                                <div class="message-meta">
                                    <div class="variables-info">
                                        Variables: {client_name}, {date}, {time}, {amount}, {invoice_number}, {due_date}
                                    </div>
                                    <div class="char-count">
                                        <span id="charCount">0</span>/1000 characters
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="schedule_now" value="1" id="scheduleNow" checked>
                                <label class="form-check-label" for="scheduleNow">
                                    <i class="bi bi-send me-1"></i>Send immediately
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>Send Messages
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Sidebar Content -->
            <div class="col-lg-4">
                <!-- Message Templates -->
                <div class="content-section template-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="bi bi-collection section-icon"></i>Message Templates
                        </h2>
                    </div>
                    
                    <div class="template-item" onclick="setTemplate('pickup_schedule')">
                        <div class="template-title">📅 Pickup Schedule</div>
                        <div class="template-description">Notify clients about upcoming collections</div>
                    </div>
                    <div class="template-item" onclick="setTemplate('trash_reminder')">
                        <div class="template-title">🗑️ Trash Reminder</div>
                        <div class="template-description">Remind clients to put out bins</div>
                    </div>
                    <div class="template-item" onclick="setTemplate('invoice_notification')">
                        <div class="template-title">📄 Invoice Notification</div>
                        <div class="template-description">Send new invoice alerts</div>
                    </div>
                    <div class="template-item" onclick="setTemplate('payment_reminder')">
                        <div class="template-title">💳 Payment Reminder</div>
                        <div class="template-description">Remind about overdue payments</div>
                    </div>
                    <div class="template-item" onclick="setTemplate('sustainability_tip')">
                        <div class="template-title">🌱 Sustainability Tips</div>
                        <div class="template-description">Share eco-friendly practices</div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="bi bi-lightning section-icon"></i>Quick Actions
                        </h2>
                    </div>
                    
                    <div class="d-grid">
                        <button class="btn btn-outline-primary quick-action-btn" onclick="sendPickupReminders()">
                            <i class="bi bi-calendar3 me-2"></i>Tomorrow's Pickups
                        </button>
                        <button class="btn btn-outline-warning quick-action-btn" onclick="sendPaymentReminders()">
                            <i class="bi bi-credit-card me-2"></i>Payment Reminders
                        </button>
                        <button class="btn btn-outline-success quick-action-btn" onclick="sendSustainabilityTip()">
                            <i class="bi bi-leaf me-2"></i>Weekly Eco-Tip
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const templates = @json($templates);
        
        function setTemplate(templateType) {
            document.getElementById('messageType').value = templateType;
            loadTemplate();
        }
        
        function loadTemplate() {
            const messageType = document.getElementById('messageType').value;
            const messageTextarea = document.getElementById('message');
            
            if (templates[messageType]) {
                messageTextarea.value = templates[messageType];
                updateCharCount();
            }
        }
        
        function toggleAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.client-checkbox:not([style*="display: none"])');
            
            checkboxes.forEach(checkbox => {
                const clientItem = checkbox.closest('.client-item');
                if (clientItem && clientItem.style.display !== 'none') {
                    checkbox.checked = selectAll.checked;
                }
            });
            updateCount();
        }
        
        function filterByRoute() {
            const routeFilter = document.getElementById('routeFilter').value;
            const routeGroups = document.querySelectorAll('.route-group');
            const clientItems = document.querySelectorAll('.client-item');
            
            if (routeFilter === 'all') {
                // Show all
                routeGroups.forEach(group => group.style.display = 'block');
                clientItems.forEach(item => item.style.display = 'block');
            } else {
                // Show only selected route
                routeGroups.forEach(group => {
                    if (group.dataset.route === routeFilter) {
                        group.style.display = 'block';
                    } else {
                        group.style.display = 'none';
                    }
                });
            }
            
            // Reset select all
            document.getElementById('selectAll').checked = false;
            updateCount();
        }
        
        function selectByRoute() {
            const routeFilter = document.getElementById('routeFilter').value;
            
            if (routeFilter === 'all') {
                alert('Please select a specific route first');
                return;
            }
            
            const checkboxes = document.querySelectorAll('.client-checkbox');
            checkboxes.forEach(checkbox => {
                const clientItem = checkbox.closest('.client-item');
                if (clientItem && clientItem.style.display !== 'none' && checkbox.dataset.route === routeFilter) {
                    checkbox.checked = true;
                }
            });
            
            updateCount();
        }
        
        function updateCharCount() {
            const message = document.getElementById('message');
            const charCount = document.getElementById('charCount');
            charCount.textContent = message.value.length;
        }
        
        function updateCount() {
            const checked = document.querySelectorAll('.client-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = `${checked} selected`;
        }
        
        document.getElementById('message').addEventListener('input', updateCharCount);
        
        function sendPickupReminders() {
            // Auto-select pickup reminder template and tomorrow's clients
            setTemplate('trash_reminder');
            
            // Select all clients (in real implementation, filter by tomorrow's schedule)
            document.getElementById('selectAll').checked = true;
            toggleAll();
        }
        
        function sendPaymentReminders() {
            setTemplate('payment_reminder');
        }
        
        function sendSustainabilityTip() {
            setTemplate('sustainability_tip');
            const tips = [
                'Reduce, reuse, recycle - the 3 R\'s of waste management',
                'Compost organic waste to reduce landfill burden',
                'Use reusable bags instead of plastic bags',
                'Separate recyclables properly for better processing'
            ];
            const randomTip = tips[Math.floor(Math.random() * tips.length)];
            document.getElementById('message').value = templates.sustainability_tip.replace('{tip}', randomTip);
            updateCharCount();
        }
        
        // Initialize character count
        document.addEventListener('DOMContentLoaded', function() {
            updateCharCount();
            updateCount();
        });
    </script>
</body>
</html>