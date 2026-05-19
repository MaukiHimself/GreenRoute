<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class Portal
{
    public static function context(): ?string
    {
        return session('portal_context');
    }

    public static function setContext(string $context): void
    {
        session(['portal_context' => $context]);
    }

    public static function homeUrl(): string
    {
        return match (self::context()) {
            'client' => route('client.dashboard'),
            'contractor' => route('dashboard.contractor'),
            'admin' => route('dashboard.admin'),
            default => match (Auth::user()?->user_type) {
                'client' => route('client.dashboard'),
                'contractor' => route('dashboard.contractor'),
                'admin' => route('dashboard.admin'),
                default => route('dashboard'),
            },
        };
    }
}
