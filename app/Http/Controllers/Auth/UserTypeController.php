<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
     * Display the admin login view.
     */
    public function loginAdmin()
    {
        return view('auth.login-admin');
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

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('client.dashboard')
            ->with('success', 'Registration successful! Your precise location has been recorded.');
    }

    /**
     * Handle an incoming registration request for contractors.
     */
    public function storeContractor(Request $request)
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'site_locations' => ['required', 'string', 'max:1000'],
            'license_number' => ['required', 'string', 'max:50'],
            'certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'contractor',
        ]);

        // Handle file upload
        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('certificates', 'public');
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
            'license_number' => $request->license_number,
            'certificate_path' => $certificatePath,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard.contractor');
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

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard.admin');
    }



    /**
     * Handle an incoming authentication request for clients.
     */
    public function authenticateClient(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');
        
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
        
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is a contractor
            if (Auth::user()->user_type === 'contractor') {
                return redirect()->route('dashboard.contractor');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'These credentials do not match our records for a contractor account.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
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