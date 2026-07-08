@php
    /**
     * Shared notification bell for the client & contractor portals.
     * Reads the authenticated user's database notifications directly so it can
     * be dropped into any layout without a controller change.
     */
    $user = Auth::user();
    $unread = $user ? $user->unreadNotifications()->count() : 0;
    $recent = $user
        ? $user->notifications()->latest()->limit(8)->get()
        : collect();
@endphp

@once
    <style>
        .nbell-badge {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            background: #dc3545;
            color: #fff;
            border-radius: 9px;
            font-size: 0.7rem;
            line-height: 18px;
            text-align: center;
            font-weight: 600;
        }
    </style>
@endonce

<div class="dropdown">
    <button class="btn btn-link text-muted p-2 position-relative" type="button"
            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
            aria-label="Notifications">
        <i class="bi bi-bell fs-5"></i>
        @if($unread > 0)
            <span class="nbell-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
        @endif
    </button>

    <div class="dropdown-menu dropdown-menu-end shadow"
         style="width: 340px; max-width: 90vw;">
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <span class="fw-semibold">Notifications</span>
            @if($unread > 0)
                <span class="badge bg-danger rounded-pill">{{ $unread }} new</span>
            @endif
        </div>

        <div style="max-height: 360px; overflow-y: auto;">
            @forelse($recent as $note)
                @php $isUnread = is_null($note->read_at); @endphp
                <a href="{{ route('notifications.open', $note->id) }}"
                   class="dropdown-item d-flex align-items-start gap-2 py-2 px-3 {{ $isUnread ? 'bg-light' : '' }}"
                   style="white-space: normal;">
                    <i class="bi {{ $note->data['icon'] ?? 'bi-bell' }} fs-5 text-success mt-1"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">{{ $note->data['title'] ?? 'Notification' }}</div>
                        <div class="text-muted small">{{ $note->data['message'] ?? '' }}</div>
                        <div class="text-muted" style="font-size: 0.72rem;">{{ $note->created_at->diffForHumans() }}</div>
                    </div>
                    @if($isUnread)
                        <span class="badge bg-success rounded-circle p-1 mt-1" style="width: 8px; height: 8px;"></span>
                    @endif
                </a>
            @empty
                <div class="text-center text-muted py-4 px-3">
                    <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
                    <span class="small">No notifications yet</span>
                </div>
            @endforelse
        </div>

        @if($recent->isNotEmpty())
            <div class="border-top">
                <form method="POST" action="{{ route('notifications.readAll') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item text-center text-success small py-2">
                        Mark all read
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
