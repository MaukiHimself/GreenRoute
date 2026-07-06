@extends('layouts.contractor-sidebar')

@section('title', 'SMS Campaign')

@section('styles')
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
@endsection

@section('content')
<div class="admin-container">
    <div class="back-link">
        <a href="{{ route('dashboard.contractor') }}">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
    
    <h1 class="page-title">SMS Campaign</h1>
    <p class="page-description">Send SMS campaigns to your clients about collection schedules, payments, and updates</p>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="info-box">
        <h4><i class="bi bi-info-circle me-2"></i>About SMS Campaigns</h4>
        <p>Use SMS campaigns to communicate with your clients about collection schedules, payment reminders, and service updates. Messages are limited to 500 characters.</p>
    </div>

    <div class="campaign-grid">
        <div class="form-card">
            <form action="{{ route('contractor.sms.send') }}" method="POST" id="campaignForm">
                @csrf

                <!-- Campaign Name -->
                <div class="form-section">
                    <h3><i class="bi bi-tag me-2"></i>Campaign Details</h3>
                    
                    <div class="form-group">
                        <label>Campaign Name</label>
                        <input type="text" name="campaign_name" class="form-control" placeholder="e.g., Collection Reminder" required>
                    </div>
                </div>

                <!-- Recipients Selection -->
                <div class="form-section">
                    <h3><i class="bi bi-people me-2"></i>Select Recipients</h3>
                    
                    <label class="recipient-option">
                        <input type="radio" name="recipients" value="all" checked>
                        <div class="option-info">
                            <span class="option-title">All Clients</span>
                            <span class="option-desc">Send to all your assigned clients</span>
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
                        <textarea name="message" id="messageContent" class="form-control" rows="6" maxlength="500" placeholder="Type your message here..." required></textarea>
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

                <h6 style="color: var(--primary-teal); margin-bottom: 0.75rem;">English Templates</h6>
                
                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">📅 Collection Reminder</div>
                    <div class="template-text">Reminder: Your waste collection is scheduled for this week. Please ensure bins are placed outside by 7 AM on collection day. Thank you!</div>
                </div>

                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">💳 Payment Reminder</div>
                    <div class="template-text">Payment Reminder: Your invoice is due soon. Please complete payment to avoid service interruption. Contact us if you have questions.</div>
                </div>

                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">♻️ Recycling Tips</div>
                    <div class="template-text">Recycling Tip: Please separate recyclables from regular waste. Plastic, paper, glass, and metal can be recycled. Thank you for helping the environment!</div>
                </div>

                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">🚯 Service Update</div>
                    <div class="template-text">Service Update: Collection schedule has been updated. Please check your dashboard for new pickup dates. Thank you for your cooperation!</div>
                </div>

                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">🙏 Thank You</div>
                    <div class="template-text">Thank you for your continued partnership with GreenRoute. We appreciate your commitment to proper waste management!</div>
                </div>

                <h6 style="color: var(--primary-teal); margin: 1.5rem 0 0.75rem;">Swahili Templates (Vipengele vya Kiswahili)</h6>
                
                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">📅 Kumbusho la Mkusanyiko</div>
                    <div class="template-text">Kumbusho: Mkusanyiko wa taka yako umepangwa kwa wiki hii. Tafadhali hakikisha mabomba yamewekwa nje saa 7 asubuhi siku ya mkusanyiko. Asante!</div>
                </div>

                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">💳 Kumbusho la Malipo</div>
                    <div class="template-text">Kumbusho la Malipo: Ankara yako inakaribia kuwa due. Tafadhali maliza malipo kuepuka kusitisha huduma. Wasiliana nasi ikiwa una maswali.</div>
                </div>

                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">♻️ Mbinu za Upandaji</div>
                    <div class="template-text">Mbinu ya Upandaji: Tafadhali tengeneza vitu vinavyopandikwa na taka za kawaida. Plastiki, karatasi, bilauri, na chuma vinaweza kupandikwa. Asante kwa msaada wako!</div>
                </div>

                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">🚯 Sasisho la Huduma</div>
                    <div class="template-text">Sasisho la Huduma: Ratiba ya mkusanyiko imesasishwa. Tafadhali angalia dashibodi yako kwa tarehe mpya za mkusanyiko. Asante kwa ushirikiano wako!</div>
                </div>

                <div class="template-item" onclick="useTemplate(this)">
                    <div class="template-title">🙏 Shukrani</div>
                    <div class="template-text">Asante kwa ushirikiano wako wa kuendelea na GreenRoute. Tunathamahia ahadi yako katika usimamizi sahihi wa taka!</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Character counter
    const messageContent = document.getElementById('messageContent');
    const charCount = document.getElementById('charCount');
    const charCounter = document.querySelector('.char-counter');
    const messagePreview = document.getElementById('messagePreview');
    const previewText = document.getElementById('previewText');

    if (messageContent) {
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
    }

    // Recipients selection
    const recipientRadios = document.querySelectorAll('input[name="recipients"]');
    const clientListGroup = document.getElementById('client-list-group');

    recipientRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (clientListGroup) {
                clientListGroup.style.display = this.value === 'selected' ? 'block' : 'none';
            }
        });
    });

    // Template selection - Global function for onclick handlers
    window.useTemplate = function(element) {
        const templateText = element.querySelector('.template-text').textContent;
        if (messageContent) {
            messageContent.value = templateText;
            messageContent.dispatchEvent(new Event('input'));
            
            // Scroll to message field
            messageContent.scrollIntoView({ behavior: 'smooth', block: 'center' });
            messageContent.focus();
        }
    };

    // Form validation
    const campaignForm = document.getElementById('campaignForm');
    if (campaignForm) {
        campaignForm.addEventListener('submit', function(e) {
            const recipients = document.querySelector('input[name="recipients"]:checked');
            
            if (recipients && recipients.value === 'selected') {
                const selectedClients = document.querySelectorAll('input[name="selected_clients[]"]:checked');
                if (selectedClients.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one client');
                    return false;
                }
            }
            
            return confirm('Are you sure you want to send this SMS campaign?');
        });
    }
</script>
@endsection
