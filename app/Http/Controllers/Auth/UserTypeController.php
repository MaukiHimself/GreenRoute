<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;

class UserTypeController extends Controller
{
    /**
     * Display the client registration view.
     */
    public function createClient()
    {
        return view('auth.register-client');
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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'client',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard.client');
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

        // Handle file upload if needed
        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store('certificates', 'public');
            // Store path in a related model or user metadata if needed
        }

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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user is a client
            if (Auth::user()->user_type === 'client') {
                return redirect()->intended(route('dashboard.client'));
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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user is a contractor
            if (Auth::user()->user_type === 'contractor') {
                return redirect()->intended(route('dashboard.contractor'));
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

        if (Auth::attempt($credentials)) {
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