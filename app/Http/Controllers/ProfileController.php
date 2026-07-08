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
            'vodacom_mpesa_lipa_name',
            'airtel_money_lipa_no',
            'airtel_money_lipa_name',
            'halopesa_lipa_no',
            'halopesa_lipa_name',
            'mixx_by_yas_lipa_no',
            'mixx_by_yas_lipa_name',
            'crdb_bank_lipa_no',
            'crdb_bank_lipa_name',
            'nmb_bank_lipa_no',
            'nmb_bank_lipa_name',
            'nbc_bank_lipa_no',
            'nbc_bank_lipa_name',
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

    public function uploadPicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
            \Storage::disk('public')->delete($user->profile_picture);
        }

        $path = $request->file('profile_picture')->store('profile-pictures', 'public');
        $user->update(['profile_picture' => $path]);

        return back()->with('status', 'profile-picture-updated');
    }

    public function toggleDarkMode(Request $request)
    {
        $user = $request->user();
        $user->update(['dark_mode' => ! $user->dark_mode]);

        return back();
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('status', 'password-updated');
    }
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
