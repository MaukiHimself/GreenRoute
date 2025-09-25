<x-guest-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sms-container { background: #f8f9fa; min-height: 100vh; padding: 20px; }
        .sms-header { background: linear-gradient(135deg, #198754, #20c997); color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .compose-card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; border-radius: 10px; }
        .sidebar-card { box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: none; border-radius: 10px; margin-bottom: 15px; }
        .recipients-box { background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; max-height: 180px; overflow-y: auto; }
        .template-item { padding: 12px; border-left: 4px solid #198754; margin-bottom: 8px; background: #f8f9fa; border-radius: 5px; }
        .quick-action-btn { border-radius: 8px; padding: 10px; font-weight: 500; }
        .message-textarea { border-radius: 8px; border: 2px solid #e9ecef; }
        .message-textarea:focus { border-color: #198754; box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25); }
        .send-btn { background: linear-gradient(135deg, #198754, #20c997); border: none; border-radius: 8px; padding: 12px 30px; font-weight: 600; }
    </style>
    
    <div class="container-fluid sms-container">
        <div class="sms-header text-center">
            <h3 class="mb-2"><i class="bi bi-chat-dots me-2"></i>SMS Manager</h3>
            <p class="mb-0">Send notifications and reminders to your clients</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card compose-card">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="text-success mb-0"><i class="bi bi-pencil-square me-2"></i>Compose Message</h5>
                    </div>
                    <div class="card-body pt-3">
                        <form method="POST" action="{{ route('sms.send') }}">
                            @csrf
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold"><i class="bi bi-tag me-1"></i>Message Type</label>
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
                                    <label class="form-label fw-semibold"><i class="bi bi-people me-1"></i>Recipients (<span id="selectedCount">0</span> selected)</label>
                                    <div class="recipients-box p-3">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleAll()">
                                            <label class="form-check-label fw-bold text-primary" for="selectAll">Select All Clients</label>
                                        </div>
                                        <hr class="my-2">
                                        @foreach($clients as $client)
                                        <div class="form-check mb-1">
                                            <input class="form-check-input client-checkbox" type="checkbox" name="recipients[]" value="{{ $client->id }}" id="client{{ $client->id }}" onchange="updateCount()">
                                            <label class="form-check-label" for="client{{ $client->id }}">
                                                <strong>{{ $client->name }}</strong> - {{ $client->phone }}
                                                <small class="text-muted d-block">{{ ucfirst($client->category) }} • {{ $client->address }}</small>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="form-label fw-semibold"><i class="bi bi-chat-text me-1"></i>Message Content</label>
                                <textarea class="form-control message-textarea" id="message" name="message" rows="6" maxlength="1000" required placeholder="Type your message here..."></textarea>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted">Variables: {client_name}, {date}, {time}, {amount}, {invoice_number}, {due_date}</small>
                                    <small class="text-muted"><span id="charCount">0</span>/1000 characters</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="schedule_now" value="1" id="scheduleNow" checked>
                                    <label class="form-check-label" for="scheduleNow">
                                        <i class="bi bi-send me-1"></i>Send immediately
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-success send-btn">
                                    <i class="bi bi-send me-2"></i>Send Messages
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card sidebar-card">
                    <div class="card-header bg-white border-0 pb-0">
                        <h6 class="text-success mb-0"><i class="bi bi-collection me-2"></i>Message Templates</h6>
                    </div>
                    <div class="card-body pt-3">
                        <div class="template-item">
                            <strong>📅 Pickup Schedule</strong>
                            <small class="d-block text-muted">Notify clients about upcoming collections</small>
                        </div>
                        <div class="template-item">
                            <strong>🗑️ Trash Reminder</strong>
                            <small class="d-block text-muted">Remind clients to put out bins</small>
                        </div>
                        <div class="template-item">
                            <strong>📄 Invoice Notification</strong>
                            <small class="d-block text-muted">Send new invoice alerts</small>
                        </div>
                        <div class="template-item">
                            <strong>💳 Payment Reminder</strong>
                            <small class="d-block text-muted">Remind about overdue payments</small>
                        </div>
                        <div class="template-item">
                            <strong>🌱 Sustainability Tips</strong>
                            <small class="d-block text-muted">Share eco-friendly practices</small>
                        </div>
                    </div>
                </div>
                
                <div class="card sidebar-card">
                    <div class="card-header bg-white border-0 pb-0">
                        <h6 class="text-success mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
                    </div>
                    <div class="card-body pt-3">
                        <div class="d-grid gap-2">
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
    </div>

    <script>
        const templates = @json($templates);
        
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
            const checkboxes = document.querySelectorAll('.client-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
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
            document.getElementById('selectedCount').textContent = checked;
        }
        
        document.getElementById('message').addEventListener('input', updateCharCount);
        
        function sendPickupReminders() {
            // Auto-select pickup reminder template and tomorrow's clients
            document.getElementById('messageType').value = 'trash_reminder';
            loadTemplate();
            
            // Select all clients (in real implementation, filter by tomorrow's schedule)
            document.getElementById('selectAll').checked = true;
            toggleAll();
        }
        
        function sendPaymentReminders() {
            document.getElementById('messageType').value = 'payment_reminder';
            loadTemplate();
        }
        
        function sendSustainabilityTip() {
            document.getElementById('messageType').value = 'sustainability_tip';
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
    </script>
</x-guest-layout>