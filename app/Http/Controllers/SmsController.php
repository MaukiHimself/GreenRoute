<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Schedule;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    public function index()
    {
        $clients = Client::where('contractor_id', Auth::id())->get();
        $templates = $this->getMessageTemplates();
        
        return view('sms.index', compact('clients', 'templates'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:clients,id',
            'message_type' => 'required|string',
            'message' => 'required|string|max:1000',
            'schedule_now' => 'boolean'
        ]);

        $clients = Client::whereIn('id', $validated['recipients'])
            ->where('contractor_id', Auth::id())
            ->get();

        foreach ($clients as $client) {
            // Here you would integrate with SMS service (Twilio, etc.)
            // For now, we'll just log the message
            \Log::info("SMS to {$client->phone}: {$validated['message']}");
        }

        return redirect()->back()->with('success', 'Messages sent successfully to ' . count($clients) . ' clients');
    }

    public function getTemplate(Request $request)
    {
        $type = $request->get('type');
        $templates = $this->getMessageTemplates();
        
        return response()->json([
            'template' => $templates[$type] ?? ''
        ]);
    }

    private function getMessageTemplates()
    {
        return [
            'pickup_schedule' => 'Hello {client_name}, your waste collection is scheduled for {date} at {time}. Please have your bins ready. - AFIA ORBIT',
            'trash_reminder' => 'Reminder: Please put out your trash bins for collection tomorrow at {time}. Thank you! - AFIA ORBIT',
            'invoice_notification' => 'New invoice #{invoice_number} for ${amount} has been generated. Due date: {due_date}. - AFIA ORBIT',
            'receipt_notification' => 'Payment received! Receipt #{receipt_number} for ${amount} has been sent to your email. Thank you! - AFIA ORBIT',
            'payment_reminder' => 'Payment reminder: Invoice #{invoice_number} for ${amount} is due on {due_date}. Please make payment to avoid late fees. - AFIA ORBIT',
            'sustainability_tip' => 'Sustainability Tip: {tip}. Together we can make a difference for our environment! - AFIA ORBIT',
            'custom' => ''
        ];
    }
}