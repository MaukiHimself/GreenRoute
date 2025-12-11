<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureContractorVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || $user->user_type !== 'contractor') {
            return $next($request);
        }

        // Allow access to the pending page and logout to prevent redirect loops
        if ($request->routeIs('contractor.pending') || 
            $request->routeIs('login.contractor') || 
            $request->routeIs('logout')) {
            return $next($request);
        }

        if ($user->status === 'rejected') {
            Auth::logout();
            return redirect()->route('login.contractor')->withErrors([
                'email' => 'Your contractor account has been rejected. Please contact support.',
            ]);
        }

        if ($user->status === 'pending' || !$user->status) {
            return redirect()->route('contractor.pending');
        }

        if ($user->status !== 'approved') {
            // Unknown status, treat as pending/review
            return redirect()->route('contractor.pending');
        }

        return $next($request);
    }
}
