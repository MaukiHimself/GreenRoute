<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractorRegistrationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;

class UserTypeController extends Controller
{
    /**
     * Display the client registration view.
     */
    public function createClient()
    {
        return view('auth.register-client-simple');
    }

    /**
     * Display the client login view.
     */
    public function loginClient()
    {
        return view('auth.login-client');
    }



    /**
     * Display the contractor registration view.
     */
    public function createContractor()
    {
        return view('auth.register-contractor');
    }

    /**
     * Display the admin registration view.
     */
    public function createAdmin()
    {
        return view('auth.register-admin');
    }

    /**
     * Display the contractor login view.
     */
    public function loginContractor()
    {
        return view('auth.login-contractor');
    }



    /**
     * Handle an incoming registration request for clients.
     */
    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Validate that coordinates are not default/placeholder values
        if (empty($request->latitude) || empty($request->longitude)) {
            return back()->withErrors([
                'location' => 'Please click "Get My Precise Location" to capture your GPS coordinates before registering.'
            ])->withInput();
        }

        // Log the received coordinates for debugging
        Log::info('Client registration location data', [
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'user_email' => $request->email,
            'timestamp' => now()
        ]);

        // Additional validation for Tanzania coordinates (rough bounds)
        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        if ($lat < -11.7 || $lat > -0.95 || $lng < 29.3 || $lng > 40.5) {
            Log::warning('Client registration with coordinates outside Tanzania', [
                'latitude' => $lat,
                'longitude' => $lng,
                'address' => $request->address,
                'user_email' => $request->email
            ]);

            return back()->withErrors([
                'location' => 'The detected location does not appear to be in Tanzania. Please ensure location services are enabled and try again.'
            ])->withInput();
        }

        // Check if coordinates are in Moshi area for additional validation
        $inMoshi = ($lat >= -3.5 && $lat <= -3.2 && $lng >= 37.2 && $lng <= 37.4);
        if ($inMoshi) {
            Log::info('Client registered in Moshi area', [
                'latitude' => $lat,
                'longitude' => $lng,
                'user_email' => $request->email
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'client',
        ]);

        // Create client record with precise location data
        \App\Models\Client::create([
            'user_id' => $user->id,
            'contractor_id' => 1, // Default contractor, can be changed later
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city' => '',
            'state' => '',
            'zip_code' => '',
            'status' => 'active',
        ]);

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            Log::error('Registered event failed for client', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            // Continue even if event fails (email notification)
        }

        Auth::login($user);

        return redirect()->route('client.dashboard')
            ->with('success', 'Registration successful! Your precise location has been recorded.');
    }

    /**
     * Handle an incoming registration request for contractors.
     */
    public function storeContractor(ContractorRegistrationRequest $request)
    {
        // Check if email already exists and provide specific error
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            Log::warning('Contractor registration attempt with existing email', [
                'email' => $request->email,
                'existing_user_type' => $existingUser->user_type,
                'existing_status' => $existingUser->status,
                'attempted_company' => $request->company_name,
                'timestamp' => now()
            ]);

            return back()->withErrors([
                'email' => "This email is already registered as a {$existingUser->user_type} account. If this is your account, please try logging in instead. If you need to register as a contractor, please use a different email address."
            ])->withInput();
        }

        // Check if license number already exists
        $existingLicense = \App\Models\Contractor::where('license_number', $request->license_number)->first();
        if ($existingLicense) {
            Log::warning('Contractor registration attempt with existing license number', [
                'license_number' => $request->license_number,
                'existing_contractor_id' => $existingLicense->id,
                'existing_user_email' => $existingLicense->email,
                'attempted_company' => $request->company_name,
                'timestamp' => now()
            ]);

            return back()->withErrors([
                'license_number' => 'This license number is already registered with another contractor. Please verify your license number or contact support.'
            ])->withInput();
        }

        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'], // Removed unique here since we check manually
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'site_locations' => ['required', 'string', 'max:2000'], // Required - at least one location must be selected
            'region' => ['nullable', 'string', 'max:255'], // Made nullable as site_locations contains full info
            'district' => ['nullable', 'string', 'max:255'],
            'ward' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:50'], // Removed unique here since we check manually
            'certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'password' => $request->password,
            'user_type' => 'contractor',
            'status' => 'pending', // Contractor must be approved by admin
        ]);

        // Handle file upload
        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            try {
                $certificatePath = $request->file('certificate')->store('certificates', 'public');
            } catch (\Exception $e) {
                Log::error('Certificate upload failed', [
                    'error' => $e->getMessage(),
                    'user_email' => $request->email
                ]);
                // Continue registration even if file upload fails
                $certificatePath = null;
            }
        }

        // Create contractor record
        \App\Models\Contractor::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'site_locations' => $request->site_locations,
            'region' => $request->region,
            'district' => $request->district,
            'ward' => $request->ward,
            'street' => $request->street,
            'license_number' => $request->license_number,
            'certificate_path' => $certificatePath,
        ]);

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            Log::error('Registered event failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            // Continue even if event fails (email notification)
        }

        // Don't auto-login, redirect to pending page instead
        return redirect()->route('contractor.pending')
            ->with('email', $user->email)
            ->with('name', $user->name)
            ->with('success', 'Registration successful! Your account is pending approval.');
    }

    /**
     * Handle an incoming registration request for admins.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'employee_id' => ['required', 'string', 'max:50'],
            'department' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'admin',
        ]);

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            Log::error('Registered event failed for admin', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            // Continue even if event fails (email notification)
        }

        Auth::login($user);

        return redirect()->route('dashboard.admin');
    }



    /**
     * Handle an incoming authentication request for clients.
     */
    public function authenticateClient(Request $request)
    {
        $identity = $request->input('email');

        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        $user = User::clientIdentity($identity)->first();
        if ($user) {
            $credentials['email'] = $user->email;
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is a client
            if (Auth::user()->user_type === 'client') {
                return redirect()->intended(route('client.dashboard'));
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'These credentials do not match our records for a client account.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle an incoming authentication request for contractors.
     */
    public function authenticateContractor(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');
        $email = strtolower(trim($credentials['email']));

<<<<<<< HEAD
        $user = User::where('email', $credentials['email'])->first();

        Log::info('Contractor login attempt', [
            'email' => $credentials['email'],
            'user_exists' => $user ? 'yes' : 'no',
            'user_type' => $user ? $user->user_type : 'null',
            'user_status' => $user ? $user->status : 'null',
            'timestamp' => now()
        ]);

        if ($user && $user->user_type === 'contractor') {
            if ($user->status === 'rejected') {
                Log::info('Contractor login: rejected account', ['email' => $credentials['email']]);
                return back()->withErrors([
                    'email' => 'Your contractor account has been rejected. Please contact support at support@greenrouteorbit.com for more information.',
                ])->withInput();
            }

            if ($user->status === 'pending' || ! $user->status) {
                Log::info('Contractor login: pending account, redirecting', ['email' => $credentials['email']]);
                return redirect()->route('contractor.pending')
                    ->with('email', $user->email)
                    ->with('name', $user->name);
            }
        }

        Log::info('Contractor login: attempting Auth::attempt', ['email' => $credentials['email']]);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            Log::info('Contractor login: Auth::attempt successful', [
                'email' => $user->email,
                'user_type' => $user->user_type,
                'status' => $user->status
            ]);

            if ($user->user_type !== 'contractor') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'These credentials do not match our records for a contractor account.',
                ]);
            }

            if ($user->status !== 'approved') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your contractor account is under review. Please wait for approval before logging in.',
                ]);
            }

            return redirect()->route('dashboard.contractor');
        }

        Log::info('Contractor login: Auth::attempt failed', ['email' => $credentials['email']]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
=======
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('email'));
        }

        if ($user->user_type !== 'contractor') {
            return back()->withErrors([
                'email' => 'These credentials do not match our records for a contractor account. Please use the contractor login page.',
            ])->withInput($request->only('email'));
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'The password you entered is incorrect. If your account was approved before March 2026, check your approval email for a temporary password or contact support.',
            ])->withInput($request->only('email'));
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        if ($user->status === 'rejected') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your contractor account has been rejected. Please contact support at support@greenrouteorbit.com for more information.',
            ])->withInput($request->only('email'));
        }

        if ($user->status === 'pending' || ! $user->status) {
            Auth::logout();
            return redirect()->route('contractor.pending')
                ->with('email', $user->email)
                ->with('name', $user->name);
        }

        if ($user->status !== 'approved') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account status is under review. Please contact support for more information.',
            ])->withInput($request->only('email'));
        }

        return redirect()->route('dashboard.contractor');
>>>>>>> 880844a (Update contractor email, reports, and map setup)
    }

    /**
     * Handle an incoming authentication request for admins.
     */
    public function authenticateAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is an admin
            if (Auth::user()->user_type === 'admin') {
                return redirect()->intended(route('dashboard.admin'));
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'These credentials do not match our records for an admin account.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
