<x-dashboard-layout title="Chat with Contractor">
    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Client</a></li>
        <li class="breadcrumb-item active">Chats</li>
    </x-slot>

    <style>
        :root {
            --primary-teal: #055c5c;
            --primary-red: #c0392b;
        }
        
        .chat-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            height: calc(100vh - 12rem); /* Adjusted height to fit within dashboard-layout */
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .chat-header {
            background: linear-gradient(135deg, var(--primary-teal), #077777);
            color: white;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
        }
        
        .contractor-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .contractor-avatar {
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
        
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f8f9fa;
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
        
        .message.client {
            justify-content: flex-end;
        }
        
        .message.contractor {
            justify-content: flex-start;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 1rem 1.25rem;
            border-radius: 16px;
        }
        
        .message.client .message-bubble {
            background: linear-gradient(135deg, var(--primary-teal), #077777);
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .message.contractor .message-bubble {
            background: white;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 4px;
        }
        
        .message-text {
            margin-bottom: 0.25rem;
            line-height: 1.5;
            word-wrap: break-word;
        }
        
        .message-time {
            font-size: 0.75rem;
            opacity: 0.7;
        }
        
        .message-input-area {
            padding: 1.5rem;
            background: white;
            border-top: 1px solid #e2e8f0;
        }
        
        .input-group {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }
        
        .message-input {
            flex: 1;
            border: 2px solid #e2e8f0;
            border-radius: 24px;
            padding: 0.75rem 1.25rem;
            resize: none;
            max-height: 120px;
            transition: all 0.3s;
        }
        
        .message-input:focus {
            outline: none;
            border-color: var(--primary-teal);
            box-shadow: 0 0 0 3px rgba(5,92,92,0.1);
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
            transform: scale(1.1);
        }
        
        .btn-send:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }
        
        .date-separator {
            text-align: center;
            margin: 2rem 0 1rem;
        }
        
        .date-separator span {
            background: white;
            color: #64748b;
            padding: 0.5rem 1rem;
            border-radius: 16px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }
    </style>

    <!-- Chat Container -->
    <div class="chat-container">
        <!-- Chat Header -->
        <div class="chat-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="contractor-info">
                    @if($contractor)
                        <div class="contractor-avatar">{{ strtoupper(substr($contractor->name, 0, 2)) }}</div>
                        <div>
                            <h3 class="mb-0" style="font-size: 1.25rem;">{{ $contractor->name }}</h3>
                            <p class="mb-0 opacity-75" style="font-size: 0.9rem;">
                                <i class="bi bi-telephone me-1"></i>{{ $contractor->phone ?? 'N/A' }}
                            </p>
                        </div>
                    @else
                        <div>
                            <h3 class="mb-0" style="font-size: 1.25rem;">No Contractor Assigned</h3>
                            <p class="mb-0 opacity-75" style="font-size: 0.9rem;">Please contact support</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container" id="messagesContainer">
            @if($contractor)
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
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-chat-dots"></i>
                        <p>No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            @else
                <div class="empty-state">
                    <i class="bi bi-exclamation-circle"></i>
                    <p>You don't have a contractor assigned yet.<br>Please contact support to get started.</p>
                </div>
            @endif
        </div>

        <!-- Message Input -->
        @if($contractor)
        <div class="message-input-area">
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
                    <i class="bi bi-send-fill" style="font-size: 1.25rem;"></i>
                </button>
            </form>
        </div>
        @endif
    </div>

    <script>
        @if($contractor)
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
                const response = await fetch('/api/sms/client-send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        contractor_id: {{ $contractor->id }},
                        client_id: {{ $clientRecord->id }},
                        message: message
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
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
        
        // Enter to send
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                messageForm.dispatchEvent(new Event('submit'));
            }
        });
        @endif
    </script>
</x-dashboard-layout>
