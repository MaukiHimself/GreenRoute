<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Campaign - GreenRoute Admin</title>
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
            max-width: 1200px;
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
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }
        
        .page-description {
            color: #666;
            margin-bottom: 2rem;
        }
        
        .campaign-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
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
        
        .recipient-option {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .recipient-option:hover {
            border-color: var(--primary-teal);
            background: #f0f9f9;
        }
        
        .recipient-option input[type="radio"] {
            margin-right: 1rem;
            width: 20px;
            height: 20px;
        }
        
        .recipient-option .option-info {
            flex: 1;
        }
        
        .recipient-option .option-title {
            font-weight: 600;
            color: #333;
            display: block;
        }
        
        .recipient-option .option-desc {
            font-size: 0.875rem;
            color: #666;
        }
        
        .recipient-option .option-count {
            background: var(--primary-teal);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .message-preview {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .message-preview .preview-label {
            font-weight: 600;
            color: #666;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .message-preview .preview-text {
            color: #333;
            line-height: 1.6;
        }
        
        .char-counter {
            text-align: right;
            font-size: 0.875rem;
            color: #666;
            margin-top: 0.25rem;
        }
        
        .char-counter.warning {
            color: #f59e0b;
        }
        
        .char-counter.danger {
            color: #ef4444;
        }
        
        .btn-send {
            background: var(--primary-teal);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }
        
        .btn-send:hover {
            background: #065f46;
        }
        
        .btn-send:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        
        .info-box {
            background: #e6f2f2;
            border-left: 4px solid var(--primary-teal);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .info-box h4 {
            color: var(--primary-teal);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .info-box p {
            font-size: 0.875rem;
            color: #666;
            margin: 0;
        }
        
        .templates-box {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .templates-box h3 {
            font-size: 1.1rem;
            color: var(--primary-teal);
            margin-bottom: 1rem;
        }
        
        .template-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .template-item:hover {
            background: #e6f2f2;
        }
        
        .template-item .template-title {
            font-weight: 600;
            color: #333;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .template-item .template-text {
            font-size: 0.875rem;
            color: #666;
            line-height: 1.5;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        #contractor-select-group {
            display: none;
        }
        
        #client-list-group {
            display: none;
        }
        
        .client-checkbox {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .client-checkbox input {
            margin-right: 0.75rem;
        }
        
        .client-checkbox label {
            margin: 0;
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="back-link">
            <a href="{{ route('admin.clients') }}">
                <i class="bi bi-arrow-left me-2"></i>Back to Clients
            </a>
        </div>
        
        <h1 class="page-title">SMS Campaign</h1>
        <p class="page-description">Send sustainability campaigns to improve waste management practices</p>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="info-box">
            <h4><i class="bi bi-info-circle me-2"></i>About SMS Campaigns</h4>
            <p>Use SMS campaigns to educate clients about proper waste disposal, recycling tips, collection schedules, and environmental sustainability. Messages are limited to 500 characters.</p>
        </div>

        <div class="campaign-grid">
            <div class="form-card">
                <form action="{{ route('admin.sms.send') }}" method="POST" id="campaignForm">
                    @csrf

                    <!-- Campaign Name -->
                    <div class="form-section">
                        <h3><i class="bi bi-tag me-2"></i>Campaign Details</h3>
                        
                        <div class="form-group">
                            <label>Campaign Name</label>
                            <input type="text" name="campaign_name" class="form-control" placeholder="e.g., Recycling Awareness Week" required>
                        </div>
                    </div>

                    <!-- Recipients Selection -->
                    <div class="form-section">
                        <h3><i class="bi bi-people me-2"></i>Select Recipients</h3>
                        
                        <label class="recipient-option">
                            <input type="radio" name="recipients" value="all" checked>
                            <div class="option-info">
                                <span class="option-title">All Clients</span>
                                <span class="option-desc">Send to all registered clients</span>
                            </div>
                            <span class="option-count">{{ $clients->count() }}</span>
                        </label>

                        <label class="recipient-option">
                            <input type="radio" name="recipients" value="residential">
                            <div class="option-info">
                                <span class="option-title">Residential Clients</span>
                                <span class="option-desc">Homeowners and residents</span>
                            </div>
                            <span class="option-count">{{ $clients->where('category', 'residential')->count() }}</span>
                        </label>

                        <label class="recipient-option">
                            <input type="radio" name="recipients" value="commercial">
                            <div class="option-info">
                                <span class="option-title">Commercial Clients</span>
                                <span class="option-desc">Businesses and organizations</span>
                            </div>
                            <span class="option-count">{{ $clients->where('category', 'commercial')->count() }}</span>
                        </label>

                        <label class="recipient-option">
                            <input type="radio" name="recipients" value="contractor">
                            <div class="option-info">
                                <span class="option-title">Specific Contractor's Clients</span>
                                <span class="option-desc">Clients assigned to a contractor</span>
                            </div>
                            <i class="bi bi-chevron-down"></i>
                        </label>

                        <div id="contractor-select-group" class="form-group" style="margin-left: 2rem;">
                            <select name="contractor_id" class="form-control">
                                <option value="">Select Contractor</option>
                                @foreach($contractors as $contractor)
                                    <option value="{{ $contractor->id }}">{{ $contractor->name }} ({{ $contractor->clients_count }} clients)</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="recipient-option">
                            <input type="radio" name="recipients" value="selected">
                            <div class="option-info">
                                <span class="option-title">Selected Clients</span>
                                <span class="option-desc">Choose specific clients</span>
                            </div>
                            <i class="bi bi-chevron-down"></i>
                        </label>

                        <div id="client-list-group" style="margin-left: 2rem; max-height: 200px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 0.5rem;">
                            @foreach($clients as $client)
                                <div class="client-checkbox">
                                    <input type="checkbox" name="selected_clients[]" value="{{ $client->id }}" id="client-{{ $client->id }}">
                                    <label for="client-{{ $client->id }}">{{ $client->name }} - {{ $client->phone }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Message Composition -->
                    <div class="form-section">
                        <h3><i class="bi bi-chat-text me-2"></i>Compose Message</h3>
                        
                        <div class="form-group">
                            <label>Message Content</label>
                            <textarea name="message" id="messageContent" class="form-control" rows="6" maxlength="500" placeholder="Type your sustainability message here..." required></textarea>
                            <div class="char-counter">
                                <span id="charCount">0</span> / 500 characters
                            </div>
                        </div>

                        <div class="message-preview" id="messagePreview" style="display: none;">
                            <div class="preview-label">Preview:</div>
                            <div class="preview-text" id="previewText"></div>
                        </div>
                    </div>

                    <!-- Send Button -->
                    <button type="submit" class="btn-send" id="sendBtn">
                        <i class="bi bi-send me-2"></i>Send SMS Campaign
                    </button>
                </form>
            </div>

            <!-- Message Templates -->
            <div>
                <div class="templates-box">
                    <h3><i class="bi bi-lightning me-2"></i>Quick Templates</h3>
                    <p style="font-size: 0.875rem; color: #666; margin-bottom: 1rem;">Click to use template</p>

                    <div class="template-item" onclick="useTemplate(this)">
                        <div class="template-title">🌍 Recycling Reminder</div>
                        <div class="template-text">Hello! Remember to separate your recyclables. Plastic, paper, glass, and metal can be recycled. Help us create a cleaner environment. Thank you!</div>
                    </div>

                    <div class="template-item" onclick="useTemplate(this)">
                        <div class="template-title">📅 Collection Schedule</div>
                        <div class="template-text">Reminder: Your next waste collection is scheduled for this week. Please ensure bins are placed outside by 7 AM on collection day. Thank you for your cooperation!</div>
                    </div>

                    <div class="template-item" onclick="useTemplate(this)">
                        <div class="template-title">♻️ Sustainability Tips</div>
                        <div class="template-text">Did you know? Composting organic waste reduces landfill volume by up to 30%. Start composting today and make a difference. Together for a greener future!</div>
                    </div>

                    <div class="template-item" onclick="useTemplate(this)">
                        <div class="template-title">🚯 Proper Disposal</div>
                        <div class="template-text">Please avoid littering and use designated bins. Hazardous waste requires special disposal. Contact us for guidance on proper waste management. Let's keep our community clean!</div>
                    </div>

                    <div class="template-item" onclick="useTemplate(this)">
                        <div class="template-title">💡 Reduce & Reuse</div>
                        <div class="template-text">Sustainability starts at home! Reduce single-use plastics, reuse containers, and recycle properly. Every small action counts towards a healthier planet. Thank you!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Character counter
        const messageContent = document.getElementById('messageContent');
        const charCount = document.getElementById('charCount');
        const charCounter = document.querySelector('.char-counter');
        const messagePreview = document.getElementById('messagePreview');
        const previewText = document.getElementById('previewText');

        messageContent.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 450) {
                charCounter.classList.add('danger');
                charCounter.classList.remove('warning');
            } else if (count > 400) {
                charCounter.classList.add('warning');
                charCounter.classList.remove('danger');
            } else {
                charCounter.classList.remove('warning', 'danger');
            }

            if (count > 0) {
                messagePreview.style.display = 'block';
                previewText.textContent = this.value;
            } else {
                messagePreview.style.display = 'none';
            }
        });

        // Recipients selection
        const recipientRadios = document.querySelectorAll('input[name="recipients"]');
        const contractorGroup = document.getElementById('contractor-select-group');
        const clientListGroup = document.getElementById('client-list-group');

        recipientRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                contractorGroup.style.display = this.value === 'contractor' ? 'block' : 'none';
                clientListGroup.style.display = this.value === 'selected' ? 'block' : 'none';
            });
        });

        // Template selection
        function useTemplate(element) {
            const templateText = element.querySelector('.template-text').textContent;
            messageContent.value = templateText;
            messageContent.dispatchEvent(new Event('input'));
            
            // Scroll to message field
            messageContent.scrollIntoView({ behavior: 'smooth', block: 'center' });
            messageContent.focus();
        }

        // Form validation
        document.getElementById('campaignForm').addEventListener('submit', function(e) {
            const recipients = document.querySelector('input[name="recipients"]:checked').value;
            
            if (recipients === 'contractor') {
                const contractorSelect = document.querySelector('select[name="contractor_id"]');
                if (!contractorSelect.value) {
                    e.preventDefault();
                    alert('Please select a contractor');
                    return false;
                }
            }
            
            if (recipients === 'selected') {
                const selectedClients = document.querySelectorAll('input[name="selected_clients[]"]:checked');
                if (selectedClients.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one client');
                    return false;
                }
            }
            
            return confirm('Are you sure you want to send this SMS campaign?');
        });
    </script>
</body>
</html>
