<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    public function profile()
    {
        return view('subscription.profile');
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_license' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'certificate_incorporation' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'contract_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'initial_payment' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        // Store uploaded files
        $businessLicense = $request->file('business_license')->store('documents/business_licenses', 'public');
        $certificateIncorporation = $request->file('certificate_incorporation')->store('documents/certificates', 'public');
        $contractDocument = $request->file('contract_document')->store('documents/contracts', 'public');

        // Update user subscription data
        $user->update([
            'business_license' => $businessLicense,
            'certificate_incorporation' => $certificateIncorporation,
            'contract_document' => $contractDocument,
            'initial_payment' => $request->initial_payment,
            'subscription_completed' => true,
            'subscription_status' => 'active',
            'subscription_date' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Subscription completed successfully!');
    }
}