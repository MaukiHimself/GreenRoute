@extends('layouts.contractor-sidebar')

@section('title', 'SMS Inbox')

@section('styles')
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
        padding: 2rem;
    }

    /* Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary-teal) 0%, #077777 100%);
        color: var(--white);
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    /* Conversations List */
    .conversations-section {
        background: var(--white);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .conversation-item {
        padding: 1.25rem;
        border-left: 4px solid transparent;
        border-bottom: 1px solid #f1f3f4;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: start;
        gap: 1rem;
    }

    .conversation-item:hover {
        background: #f8f9fa;
        border-left-color: var(--primary-teal);
    }

    .conversation-item.has-unread {
        background: #f0f9f9;
        border-left-color: var(--primary-red);
    }

    .client-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-teal), #077777);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .conversation-content {
        flex: 1;
    }

    .client-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .client-info {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .last-message {
        color: #64748b;
        font-size: 0.9rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .conversation-meta {
        text-align: right;
        flex-shrink: 0;
    }

    .message-time {
        color: #94a3b8;
        font-size: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .unread-badge {
        background: var(--primary-red);
        color: var(--white);
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Action Buttons */
    .btn-new-message {
        background: var(--primary-red);
        color: var(--white);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-new-message:hover {
        background: #4a0303;
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(100, 4, 4, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #64748b;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-chat-dots me-2"></i>SMS Inbox
                </h1>
                <p class="mb-0 opacity-75">All your conversations with clients</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard.contractor') }}" class="btn btn-light text-teal d-flex align-items-center gap-2" style="color: var(--primary-teal); font-weight: 600;">
                    <i class="bi bi-house-door-fill"></i> Home
                </a>
                <a href="{{ route('sms.index') }}" class="btn-new-message">
                    <i class="bi bi-plus-circle"></i> New Message
                </a>
            </div>
        </div>
    </div>

    <!-- Conversations List -->
    <div class="conversations-section">
        @forelse($conversations as $client)
            @php
                $lastMessage = $client->messages->first();
                $initials = strtoupper(substr($client->name, 0, 2));
            @endphp
            <div class="conversation-item {{ $client->unread_count > 0 ? 'has-unread' : '' }}"
                 onclick="window.location='{{ route('sms.conversation', $client) }}'">
                <div class="client-avatar">{{ $initials }}</div>
                <div class="conversation-content">
                    <div class="client-name">{{ $client->name }}</div>
                    <div class="client-info">
                        <i class="bi bi-telephone me-1"></i>{{ $client->phone }}
                        @if($client->category)
                            <span class="ms-2">
                                <i class="bi bi-tag me-1"></i>{{ ucfirst($client->category) }}
                            </span>
                        @endif
                    </div>
                    @if($lastMessage)
                        <div class="last-message">
                            <strong>{{ $lastMessage->sender_type === 'contractor' ? 'You' : $client->name }}:</strong>
                            {{ Str::limit($lastMessage->message, 100) }}
                        </div>
                    @else
                        <div class="last-message text-muted">No messages yet</div>
                    @endif
                </div>
                <div class="conversation-meta">
                    @if($lastMessage)
                        <div class="message-time">{{ $lastMessage->created_at->diffForHumans() }}</div>
                    @endif
                    @if($client->unread_count > 0)
                        <span class="unread-badge">{{ $client->unread_count }} new</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h3>No Conversations Yet</h3>
                <p>Start sending messages to your clients to see conversations here.</p>
                <a href="{{ route('sms.index') }}" class="btn-new-message mt-3">
                    <i class="bi bi-plus-circle"></i> Send Your First Message
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
