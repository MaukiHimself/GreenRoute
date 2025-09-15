<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Register middleware through the middleware method of the BaseController class
        $this->middleware('auth');
    }

    /**
     * Show the appropriate dashboard based on user type.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->user_type === 'client') {
            return redirect()->route('dashboard.client');
        } elseif (Auth::user()->user_type === 'contractor') {
            return redirect()->route('dashboard.contractor');
        } elseif (Auth::user()->user_type === 'admin') {
            return redirect()->route('dashboard.admin');
        } else {
            // Default dashboard if user type is not recognized
            return view('dashboard');
        }
    }

    /**
     * Show the client dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function clientDashboard()
    {
        if (Auth::user()->user_type !== 'client') {
            return redirect()->route('dashboard');
        }
        
        return view('dashboards.client');
    }

    /**
     * Show the contractor dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contractorDashboard()
    {
        if (Auth::user()->user_type !== 'contractor') {
            return redirect()->route('dashboard');
        }
        
        return view('dashboards.contractor');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminDashboard()
    {
        if (Auth::user()->user_type !== 'admin') {
            return redirect()->route('dashboard');
        }
        
        return view('dashboards.admin');
    }
}