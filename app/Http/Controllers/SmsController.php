<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Message;
use App\Models\Schedule;
use App\Models\Invoice;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    public function index()
    {
        $contractorId = Auth::id();

        // Get all clients
        $clients = Client::where('contractor_id', $contractorId)
            ->orderBy('name')
            ->get();

        // Group clients by route
        $clientsByRoute = $clients->groupBy('route')->sortKeys();

        // Get all unique routes
        $routes = $clients->pluck('route')->unique()->filter()->sort()->values();

        $templates = $this->getMessageTemplates();

        return view('sms.index', compact('clients', 'clientsByRoute', 'routes', 'templates'));
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

        $sentCount = 0;
        foreach ($clients as $client) {
            // Save message to database
            Message::create([
                'contractor_id' => Auth::id(),
                'client_id' => $client->id,
                'sender_type' => 'contractor',
                'message' => $validated['message'],
                'message_type' => $validated['message_type'],
                'status' => 'sent'
            ]);

            // Here you would integrate with SMS service (Twilio, etc.)
            // For now, we'll just log the message
            \Log::info("SMS to {$client->phone}: {$validated['message']}");
            $sentCount++;
        }

        return redirect()->back()->with('success', 'Messages sent successfully to ' . $sentCount . ' clients');
    }

    /**
     * Show inbox with all conversations
     */
    public function inbox()
    {
        $contractorId = Auth::id();

        // Get all clients with their latest message
        $conversations = Client::where('contractor_id', $contractorId)
            ->with(['messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->withCount(['messages as unread_count' => function($query) use ($contractorId) {
                $query->where('sender_type', 'client')
                      ->where('status', '!=', 'read');
            }])
            ->get();

        return view('sms.inbox', compact('conversations'));
    }

    /**
     * Show conversation with specific client
     */
    public function conversation(Client $client)
    {
        // Verify client belongs to this contractor
        if ($client->contractor_id !== Auth::id()) {
            abort(403);
        }

        $messages = Message::conversation(Auth::id(), $client->id)->get();

        // Mark contractor's unread messages as read
        Message::where('contractor_id', Auth::id())
            ->where('client_id', $client->id)
            ->where('sender_type', 'client')
            ->where('status', '!=', 'read')
            ->update(['status' => 'read', 'read_at' => now()]);

        return view('sms.conversation', compact('client', 'messages'));
    }

    /**
     * Send message in conversation
     */
    public function sendMessage(Request $request, Client $client)
    {
        // Verify client belongs to this contractor
        if ($client->contractor_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'contractor_id' => Auth::id(),
            'client_id' => $client->id,
            'sender_type' => 'contractor',
            'message' => $validated['message'],
            'message_type' => 'custom',
            'status' => 'sent'
        ]);

        // Here you would send actual SMS
        \Log::info("SMS to {$client->phone}: {$validated['message']}");

        // Notify the client (bell) that their contractor messaged them.
        if ($client->user) {
            $contractorName = Auth::user()->name;
            $client->user->notify(new GenericNotification(
                title: 'New message',
                message: $contractorName . ' sent you a message',
                url: route('client.chats'),
                icon: 'bi-chat-dots',
            ));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', 'Message sent successfully');
    }

    /**
     * Client sends message to contractor (API endpoint)
     */
    public function clientSend(Request $request)
    {
        $validated = $request->validate([
            'contractor_id' => 'required|exists:users,id',
            'client_id' => 'required|exists:clients,id',
            'message' => 'required|string|max:1000'
        ]);

        // Verify client-contractor relationship
        $client = Client::where('id', $validated['client_id'])
            ->where('contractor_id', $validated['contractor_id'])
            ->firstOrFail();

        $message = Message::create([
            'contractor_id' => $validated['contractor_id'],
            'client_id' => $validated['client_id'],
            'sender_type' => 'client',
            'message' => $validated['message'],
            'message_type' => 'custom',
            'status' => 'sent'
        ]);

        // Notify the contractor (bell) that their client messaged them.
        $contractor = User::find($validated['contractor_id']);
        if ($contractor) {
            $contractor->notify(new GenericNotification(
                title: 'New message',
                message: ($client->name ?? 'A client') . ' sent you a message',
                url: route('sms.conversation', $client),
                icon: 'bi-chat-dots',
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ]);
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
            'pickup_schedule' => 'Hello {client_name}, your waste collection is scheduled for {date} at {time}. Please have your bins ready. - GreenRoute ORBIT',
            'trash_reminder' => 'Reminder: Please put out your trash bins for collection tomorrow at {time}. Thank you! - GreenRoute ORBIT',
            'invoice_notification' => 'New invoice #{invoice_number} for ${amount} has been generated. Due date: {due_date}. - GreenRoute ORBIT',
            'receipt_notification' => 'Payment received! Receipt #{receipt_number} for ${amount} has been sent to your email. Thank you! - GreenRoute ORBIT',
            'payment_reminder' => 'Payment reminder: Invoice #{invoice_number} for ${amount} is due on {due_date}. Please make payment to avoid late fees. - GreenRoute ORBIT',
            'sustainability_tip' => 'Sustainability Tip: {tip}. Together we can make a difference for our environment! - GreenRoute ORBIT',
            'custom' => ''
        ];
    }
}
