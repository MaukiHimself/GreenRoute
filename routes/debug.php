<?php

use Illuminate\Support\Facades\Route;
use App\Models\Client;

// Temporary debug route - remove after fixing
Route::get('/debug/clients', function () {
    $clients = Client::take(5)->get(['id', 'name', 'address', 'region', 'district', 'ward', 'street']);
    
    $output = '<h1>Client Location Data</h1>';
    $output .= '<p><strong>Total Clients:</strong> ' . Client::count() . '</p>';
    $output .= '<table border="1" cellpadding="10" style="border-collapse: collapse;">';
    $output .= '<tr><th>Name</th><th>Address</th><th>Region</th><th>District</th><th>Ward</th><th>Street</th></tr>';
    
    foreach ($clients as $client) {
        $output .= '<tr>';
        $output .= '<td>' . $client->name . '</td>';
        $output .= '<td>' . ($client->address ?? '<em>null</em>') . '</td>';
        $output .= '<td>' . ($client->region ?? '<em>null</em>') . '</td>';
        $output .= '<td>' . ($client->district ?? '<em>null</em>') . '</td>';
        $output .= '<td>' . ($client->ward ?? '<em>null</em>') . '</td>';
        $output .= '<td>' . ($client->street ?? '<em>null</em>') . '</td>';
        $output .= '</tr>';
    }
    
    $output .= '</table>';
    
    return $output;
});
