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

    /**
     * Resolve portal from the authenticated user (source of truth).
     */
    public static function forUser(?string $userType = null): string
    {
        $userType ??= Auth::user()?->user_type;

        return match ($userType) {
            'admin' => 'admin',
            'contractor' => 'contractor',
            'client' => 'client',
            default => 'client',
        };
    }

    public static function syncContextFromUser(): void
    {
        if (Auth::check()) {
            self::setContext(self::forUser());
        }
    }

    public static function homeUrl(): string
    {
        return match (self::forUser()) {
            'client' => route('client.dashboard'),
            'contractor' => route('dashboard.contractor'),
            'admin' => route('dashboard.admin'),
            default => route('dashboard'),
        };
    }

    public static function profileUrl(): string
    {
        return match (self::forUser()) {
            'client' => route('client.profile'),
            'contractor', 'admin' => route('profile.edit'),
            default => route('profile.edit'),
        };
    }

    public static function profileRouteIsActive(): bool
    {
        return match (self::forUser()) {
            'client' => request()->routeIs('client.profile*'),
            default => request()->routeIs('profile.*'),
        };
    }
}
