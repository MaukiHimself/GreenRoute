<?php

namespace App\Http\Middleware;

use App\Support\Portal;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetPortalContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        if ($request->is('dashboard/client*')) {
            Portal::setContext('client');
        } elseif ($request->is('dashboard/contractor*', 'contractor/*')) {
            Portal::setContext('contractor');
        } elseif ($request->is('admin/*', 'dashboard/admin*')) {
            Portal::setContext('admin');
        } else {
            Portal::syncContextFromUser();
        }

        return $next($request);
    }
}
