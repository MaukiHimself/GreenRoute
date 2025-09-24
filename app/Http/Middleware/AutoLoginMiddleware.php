<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class AutoLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If user is already authenticated, continue
        if (Auth::check()) {
            return $next($request);
        }

        // Check for remember token in cookies
        $rememberToken = $request->cookie('remember_web_' . sha1(config('app.name')));
        
        if ($rememberToken) {
            // Find user with this remember token
            $user = User::where('remember_token', $rememberToken)
                       ->where('remember_login', true)
                       ->first();
            
            if ($user) {
                Auth::login($user, true);
            }
        }

        return $next($request);
    }
}