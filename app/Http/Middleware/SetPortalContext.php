<?php

namespace App\Http\Middleware;

use App\Support\Portal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPortalContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('dashboard/client*')) {
            Portal::setContext('client');
        } elseif ($request->is('dashboard/contractor*') || $request->is('contractor/*')) {
            Portal::setContext('contractor');
        } elseif ($request->is('admin/*') || $request->is('dashboard/admin*')) {
            Portal::setContext('admin');
        }

        return $next($request);
    }
}
