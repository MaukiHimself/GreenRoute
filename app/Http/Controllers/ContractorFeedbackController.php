<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractorFeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified.contractor']);
    }

    public function index()
    {
        $feedback = Feedback::with(['client'])
            ->where('contractor_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('contractor.feedback.index', compact('feedback'));
    }

    public function show(Feedback $feedback)
    {
        if ((int) $feedback->contractor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $feedback->load('client');
        return view('contractor.feedback.show', compact('feedback'));
    }

    public function respond(Request $request, Feedback $feedback)
    {
        if ((int) $feedback->contractor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'response' => 'required|string|max:5000',
        ]);

        $feedback->update([
            'status' => 'responded',
            'response' => $validated['response'],
            'responded_at' => now(),
        ]);

        // Notify the client (bell) that their feedback received a response
        $client = $feedback->client;
        if ($client && $client->user) {
            $client->user->notify(new GenericNotification(
                title: 'Feedback responded',
                message: 'Your contractor responded to your feedback: "' . \Illuminate\Support\Str::limit($feedback->subject, 50) . '"',
                url: route('client.feedback'),
                icon: 'bi-chat-left-dots',
            ));
        }

        return redirect()->route('contractor.feedback.index')
            ->with('success', 'Response sent to client successfully.');
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        if ((int) $feedback->contractor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:open,responded,resolved,closed',
        ]);

        $feedback->update(['status' => $validated['status']]);

        // Notify the client (bell) when their feedback is resolved or closed
        if (in_array($validated['status'], ['resolved', 'closed'])) {
            $client = $feedback->client;
            if ($client && $client->user) {
                $label = $validated['status'] === 'resolved' ? 'resolved' : 'closed';
                $client->user->notify(new GenericNotification(
                    title: 'Feedback ' . $label,
                    message: 'Your feedback "' . \Illuminate\Support\Str::limit($feedback->subject, 50) . '" has been marked as ' . $label,
                    url: route('client.feedback'),
                    icon: 'bi-check2-all',
                ));
            }
        }

        return back()->with('success', 'Feedback status updated.');
    }
}
