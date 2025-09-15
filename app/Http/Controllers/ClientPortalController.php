<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Schedule;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    protected function resolveClient(): ?Client
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();
        if ($client) {
            return $client;
        }
        $email = strtolower($user->email);
        return Client::whereRaw('LOWER(email) = ?', [$email])->first();
    }

    public function schedules()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $schedules = Schedule::with('contractor')
            ->where('client_id', $client->id)
            ->orderByDesc('pickup_date')
            ->paginate(15);

        return view('client_portal.schedules', compact('client', 'schedules'));
    }

    public function invoices()
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $invoices = Invoice::with(['contractor'])
            ->where('client_id', $client->id)
            ->orderByDesc('invoice_date')
            ->paginate(15);

        return view('client_portal.invoices', compact('client', 'invoices'));
    }

    public function storeFeedback(Request $request)
    {
        $client = $this->resolveClient();
        abort_unless($client, 404);

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        Feedback::create([
            'client_id' => $client->id,
            'contractor_id' => $client->contractor_id,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        return back()->with('success', 'Your message has been sent to your contractor.');
    }
}
