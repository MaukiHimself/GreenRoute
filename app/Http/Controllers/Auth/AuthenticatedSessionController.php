<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Handle remember me functionality
        if ($request->filled('remember')) {
            Auth::user()->update(['remember_login' => true]);
        }

        $user = Auth::user();

        // Check if user needs to complete subscription
        if ($user->needsSubscription()) {
            return redirect()->route('subscription.profile');
        }

        // Redirect based on user type
        return match($user->user_type) {
            'contractor' => redirect()->intended(route('dashboard.contractor')),
            'client' => redirect()->intended(route('client.dashboard')),
            'admin' => redirect()->intended(route('dashboard.admin')),
            default => redirect()->intended(route('dashboard')),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
