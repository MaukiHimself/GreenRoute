<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(Request $request): View
    {
        // Remember which login page the user came from so the "Back to Login"
        // link returns there (client / contractor / admin), surviving the POST.
        $back = url()->previous();
        if (is_string($back) && str_contains($back, 'login')) {
            $request->session()->put('password_back', $back);
        }

        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Attempt to send the password reset link. Sending is synchronous, so a
        // mail transport failure (no internet / SMTP blocked) would otherwise
        // bubble up as a 500. We catch it and fall back gracefully.
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (\Throwable $e) {
            report($e);

            return $this->fallback($request);
        }

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }

    /**
     * When email delivery is unavailable, generate a reset link the user can
     * use directly. The clickable link is only exposed in local/debug so it
     * never leaks in production (that would allow account takeover).
     */
    protected function fallback(Request $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();

        if ($user && (app()->environment('local') || config('app.debug'))) {
            $token = Password::createToken($user);
            $url = route('password.reset', ['token' => $token]) . '?email=' . urlencode($user->email);

            return back()
                ->with('reset_link', $url)
                ->with('status', 'Email service is currently unavailable, so we could not send the link. You can reset your password directly using the button below.');
        }

        // In production, don't reveal whether the account exists or that mail failed.
        return back()->with('status', __(Password::RESET_LINK_SENT));
    }
}
