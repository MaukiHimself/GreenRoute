<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ContractorController extends Controller
{
    public function dashboard()
    {
        return view('contractor.dashboard');
    }

    public function getAssignedClients()
    {
        $query = Client::where('contractor_id', auth()->id())
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');
            
        // Apply search filters
        if (request('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }
        
        if (request('category')) {
            $query->where('category', request('category'));
        }
        
        if (request('location')) {
            $query->where('address', 'like', '%' . request('location') . '%');
        }
        
        if (request('registration_number')) {
            $query->where('registration_number', 'like', '%' . request('registration_number') . '%');
        }
        
        $clients = $query->select('id', 'name', 'address', 'latitude', 'longitude', 'phone', 'email')
            ->get();

        return response()->json($clients);
    }
}