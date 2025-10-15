<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\PhoneVerification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ClientAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.client.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'registration_number' => ['required', 'string', 'max:50'],
            'contact_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'verification_method' => ['required', 'in:phone,email'],
        ]);

        // Find client record created by contractor
        $client = Client::where('registration_number', $request->registration_number)
            ->where('name', $request->contact_name)
            ->where('phone', $request->phone)
            ->where('email', $request->email)
            ->first();
            
        // If not found, try case-insensitive search
        if (!$client) {
            $client = Client::whereRaw('LOWER(registration_number) = ?', [strtolower($request->registration_number)])
                ->whereRaw('LOWER(name) = ?', [strtolower($request->contact_name)])
                ->where('phone', $request->phone)
                ->where('email', $request->email)
                ->first();
        }

        if (!$client) {
            return back()->withErrors([
                'registration_number' => 'No matching client record found. Please verify your details with your contractor.',
            ])->withInput();
        }

        // Check if already activated
        if ($client->user_id) {
            return back()->withErrors([
                'registration_number' => 'This account has already been activated. Please use the login page.',
            ]);
        }

        // Determine contact info based on verification method
        $contactInfo = $request->verification_method === 'email' ? $request->email : $request->phone;
        
        // Generate verification code
        $code = PhoneVerification::generateCode($contactInfo);
        
        session([
            'client_activation' => [
                'client_id' => $client->id,
                'contact_info' => $contactInfo,
            ]
        ]);

        // Send verification code based on user choice
        if ($request->verification_method === 'email') {
            \Log::info("Email sent to {$contactInfo}: Your verification code is: {$code}");
            $message = 'Verification code sent to your email.';
        } else {
            $this->sendSMS($contactInfo, "Your verification code is: {$code}");
            $message = 'Verification code sent to your phone.';
        }

        return redirect()->route('client.verify-phone')
            ->with('contact_info', $contactInfo)
            ->with('success', $message);
    }

    public function showVerifyPhone()
    {
        return view('auth.client.verify-phone');
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        // Check for activation flow
        $activationData = session('client_activation');
        if ($activationData) {
            if (!PhoneVerification::verify($activationData['contact_info'], $request->code)) {
                return back()->withErrors(['code' => 'Invalid or expired verification code.']);
            }

            session(['client_password_setup' => ['client_id' => $activationData['client_id']]]);
            session()->forget('client_activation');
            
            return redirect()->route('client.set-password');
        }

        // Original registration flow
        $registrationData = session('client_registration');
        if (!$registrationData) {
            return redirect()->route('client.register');
        }

        if (!PhoneVerification::verify($registrationData['phone'], $request->code)) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        // Create user and client
        $user = User::create([
            'name' => $registrationData['contact_name'],
            'email' => $registrationData['phone'],
            'password' => Hash::make($registrationData['password']),
            'user_type' => 'client',
        ]);

        Client::create([
            'user_id' => $user->id,
            'contractor_id' => 1,
            'name' => $registrationData['contact_name'],
            'email' => $registrationData['phone'],
            'phone' => $registrationData['phone'],
            'registration_number' => $registrationData['registration_number'],
            'address' => '',
            'city' => '',
            'state' => '',
            'zip_code' => '',
            'status' => 'active',
        ]);

        session()->forget('client_registration');
        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('client.dashboard')
            ->with('success', 'Registration completed successfully!');
    }

    public function showLogin()
    {
        return view('auth.client.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Find user by email with client user_type
        $user = User::where('email', $request->email)
            ->where('user_type', 'client')
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No client account found with this email address.',
            ])->withInput($request->only('email'));
        }

        // Check if client record exists and is linked to user
        $client = Client::where('user_id', $user->id)->first();
        
        if (!$client) {
            return back()->withErrors([
                'email' => 'Client account not properly set up. Please contact your contractor.',
            ])->withInput($request->only('email'));
        }

        // Attempt authentication
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'user_type' => 'client'], $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            \Log::info('Client logged in', [
                'user_id' => $user->id,
                'client_id' => $client->id,
                'client_registration_number' => $client->registration_number
            ]);
            
            return redirect()->intended(route('client.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function showVerifySetup()
    {
        if (!session('client_setup')) {
            return redirect()->route('client.login');
        }
        return view('auth.client.verify-phone');
    }

    public function verifySetup(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'size:6']]);
        
        $setupData = session('client_setup');
        if (!$setupData) {
            return redirect()->route('client.login');
        }

        $client = Client::find($setupData['client_id']);
        if (!$client || !PhoneVerification::verify($client->phone, $request->code)) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        session(['client_password_setup' => ['client_id' => $client->id]]);
        session()->forget('client_setup');
        
        return redirect()->route('client.set-password');
    }

    public function showSetPassword()
    {
        if (!session('client_password_setup')) {
            return redirect()->route('client.login');
        }
        return view('auth.client.set-password');
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $setupData = session('client_password_setup');
        if (!$setupData) {
            return redirect()->route('client.login');
        }

        $client = Client::find($setupData['client_id']);
        if (!$client) {
            return redirect()->route('client.login');
        }

        // Create user and link to existing client record
        $user = User::create([
            'name' => $client->name,
            'email' => $client->phone,
            'password' => Hash::make($request->password),
            'user_type' => 'client',
        ]);
        
        $client->update(['user_id' => $user->id]);

        session()->forget('client_password_setup');
        Auth::loginUsingId($user->id);
        
        return redirect()->route('client.dashboard')
            ->with('success', 'Password set successfully! Welcome to your dashboard.');
    }

    private function sendSMS(string $phone, string $message): void
    {
        // Mock SMS implementation
        // In production, integrate with SMS service like Twilio, Africa's Talking, etc.
        \Log::info("SMS sent to {$phone}: {$message}");
    }
}