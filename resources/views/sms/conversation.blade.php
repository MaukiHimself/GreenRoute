<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat with {{ $client->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #c0392b;
            --white: #ffffff;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }
        
        /* Chat Header */
        .chat-header {
            background: linear-gradient(135deg, var(--primary-teal), #077777);
            color: white;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
            margin-bottom: 0;
        }
        
        .client-info-header {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .client-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .client-details h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .client-details p {
            margin: 0.25rem 0 0 0;
            opacity: 0.9;
        }
        
        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        /* Messages Container */
        .messages-container {
            background: white;
            height: 500px;
            overflow-y: auto;
            padding: 1.5rem;
            border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
        }
        
        .date-separator {
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .date-separator span {
            background: #e2e8f0;
            color: #64748b;
            padding: 0.25rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .message {
            display: flex;
            margin-bottom: 1rem;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .message.contractor {
            justify-content: flex-end;
        }
        
        .message.client {
            justify-content: flex-start;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            position: relative;
        }
        
        .message.contractor .message-bubble {
            background: linear-gradient(135deg, var(--primary-teal), #077777);
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .message.client .message-bubble {
            background: #e2e8f0;
            color: #1e293b;
            border-bottom-left-radius: 4px;
        }
        
        .message-text {
            margin-bottom: 0.25rem;
            word-wrap: break-word;
        }
        
        .message-time {
            font-size: 0.75rem;
            opacity: 0.7;
        }
        
        .read-receipt {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        /* Message Input */
        .message-input-container {
            background: white;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 12px 12px;
        }
        
        .input-group {
            display: flex;
            gap: 1rem;
        }
        
        .message-input {
            flex: 1;
            border: 2px solid #e2e8f0;
            border-radius: 24px;
            padding: 0.75rem 1.25rem;
            resize: none;
            max-height: 120px;
            font-family: inherit;
        }
        
        .message-input:focus {
            outline: none;
            border-color: var(--primary-teal);
        }
        
        .btn-send {
            background: var(--primary-teal);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-send:hover {
            background: #044a4a;
            transform: scale(1.05);
        }
        
        .btn-send:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Chat Header -->
        <div class="chat-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="client-info-header">
                    <div class="client-avatar">{{ strtoupper(substr($client->name, 0, 2)) }}</div>
                    <div class="client-details">
                        <h2>{{ $client->name }}</h2>
                        <p>
                            <i class="bi bi-telephone me-1"></i>{{ $client->phone }}
                            @if($client->category)
                                <span class="ms-2">• {{ ucfirst($client->category) }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard.contractor') }}" class="btn-back" target="_parent">
                        <i class="bi bi-house-door-fill"></i> Home
                    </a>
                    <a href="{{ route('sms.inbox') }}" class="btn-back" target="_parent">
                        <i class="bi bi-arrow-left"></i> Back to Inbox
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container" id="messagesContainer">
            @php
                $currentDate = null;
            @endphp
            
            @forelse($messages as $message)
                @php
                    $messageDate = $message->created_at->format('Y-m-d');
                @endphp
                
                @if($currentDate !== $messageDate)
                    <div class="date-separator">
                        <span>{{ $message->created_at->format('M d, Y') }}</span>
                    </div>
                    @php
                        $currentDate = $messageDate;
                    @endphp
                @endif
                
                <div class="message {{ $message->sender_type }}">
                    <div class="message-bubble">
                        <div class="message-text">{{ $message->message }}</div>
                        <div class="message-time">
                            {{ $message->created_at->format('g:i A') }}
                            @if($message->sender_type === 'contractor' && $message->status === 'read')
                                <span class="read-receipt"><i class="bi bi-check-all"></i></span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-chat-dots"></i>
                    <p>No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input -->
        <div class="message-input-container">
            <form id="messageForm" class="input-group">
                @csrf
                <textarea 
                    class="message-input" 
                    id="messageInput" 
                    placeholder="Type your message..." 
                    rows="1"
                    maxlength="1000"
                    required
                ></textarea>
                <button type="submit" class="btn-send" id="sendButton">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        
        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Scroll to bottom
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        scrollToBottom();
        
        // Send message
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            sendButton.disabled = true;
            messageInput.disabled = true;
            
            try {
                const response = await fetch('{{ route("sms.sendMessage", $client) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: message,
                        message_type: 'custom'
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    location.reload();
                } else {
                    alert('Failed to send message. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to send message. Please try again.');
            } finally {
                sendButton.disabled = false;
                messageInput.disabled = false;
                messageInput.focus();
            }
        });
        
        // Enter to send (Shift+Enter for new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                messageForm.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>
