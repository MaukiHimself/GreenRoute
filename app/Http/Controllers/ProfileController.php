<?php

namespace App\Http\Controllers;

use App\Support\Portal;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        Portal::syncContextFromUser();

        if ($user->user_type === 'contractor') {
            return view('profile.edit-contractor', compact('user'));
        }

        if ($user->user_type === 'client') {
            return redirect()->route('client.profile');
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $paymentFields = [
            'vodacom_mpesa_lipa_no',
            'airtel_money_lipa_no',
            'halopesa_lipa_no',
            'mixx_by_yas_lipa_no',
            'crdb_bank_lipa_no',
            'nmb_bank_lipa_no',
            'nbc_bank_lipa_no',
        ];

        $request->user()->fill(collect($validated)->except($paymentFields)->all());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        if ($request->user()->user_type === 'contractor' && $request->user()->contractor) {
            $request->user()->contractor->update(collect($validated)->only($paymentFields)->all());
        }

        Portal::syncContextFromUser();

        return match ($request->user()->user_type) {
            'client' => Redirect::route('client.profile')->with('status', 'profile-updated'),
            default => Redirect::route('profile.edit')->with('status', 'profile-updated'),
        };
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
