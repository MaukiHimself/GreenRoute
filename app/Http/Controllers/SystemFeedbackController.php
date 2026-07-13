<?php

namespace App\Http\Controllers;

use App\Models\SystemFeedback;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * System feedback channel: clients and contractors report problems or
 * suggestions about the GreenRoute platform; admins read and reply.
 */
class SystemFeedbackController extends Controller
{
    /** Show the support/feedback form + the current user's own history. */
    public function create()
    {
        $user = Auth::user();
        $role = $this->roleFor($user);

        $tickets = SystemFeedback::where('user_id', $user->id)
            ->latest()
            ->get();

        $view = $role === 'contractor' ? 'contractor.support' : 'client_portal.support';

        return view($view, compact('tickets', 'role'));
    }

    /** Store a submission from either a client or a contractor. */
    public function store(Request $request)
    {
        $user = Auth::user();
        $role = $this->roleFor($user);

        $data = $request->validate([
            'category' => 'nullable|string|max:100',
            'subject'  => 'required|string|max:255',
            'message'  => 'required|string|max:5000',
        ]);

        $feedback = SystemFeedback::create([
            'user_id'  => $user->id,
            'role'     => $role,
            'category' => $data['category'] ?? null,
            'subject'  => $data['subject'],
            'message'  => $data['message'],
            'status'   => 'open',
        ]);

        // Notify all admins via the bell.
        $admins = User::where('user_type', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new GenericNotification(
                title: 'New system feedback',
                message: ucfirst($role) . ' ' . ($user->name ?? '') . ': "' . Str::limit($feedback->subject, 50) . '"',
                url: route('admin.feedback.show', $feedback),
                icon: 'bi-life-preserver',
            ));
        }

        $route = $role === 'contractor' ? 'contractor.support' : 'client.support';

        return redirect()->route($route)
            ->with('success', 'Your feedback has been submitted. Our team will get back to you shortly.');
    }

    /** Admin: list all system feedback. */
    public function adminIndex()
    {
        $feedback = SystemFeedback::with('user')
            ->latest()
            ->paginate(20);

        $counts = [
            'open'      => SystemFeedback::where('status', 'open')->count(),
            'responded' => SystemFeedback::where('status', 'responded')->count(),
            'resolved'  => SystemFeedback::where('status', 'resolved')->count(),
        ];

        return view('admin.feedback.index', compact('feedback', 'counts'));
    }

    /** Admin: view a single feedback item. */
    public function adminShow(SystemFeedback $feedback)
    {
        $feedback->load(['user', 'responder']);

        return view('admin.feedback.show', compact('feedback'));
    }

    /** Admin: reply to a feedback item and notify the submitter. */
    public function adminRespond(Request $request, SystemFeedback $feedback)
    {
        $data = $request->validate([
            'admin_response' => 'required|string|max:5000',
            'status'         => 'nullable|in:open,responded,resolved',
        ]);

        $feedback->update([
            'admin_response' => $data['admin_response'],
            'status'         => $data['status'] ?? 'responded',
            'responded_at'   => now(),
            'responded_by'   => Auth::id(),
        ]);

        // Notify the original submitter via the bell.
        if ($feedback->user) {
            $supportRoute = $feedback->role === 'contractor' ? 'contractor.support' : 'client.support';
            $feedback->user->notify(new GenericNotification(
                title: 'Support replied',
                message: 'The GreenRoute team replied to: "' . Str::limit($feedback->subject, 50) . '"',
                url: route($supportRoute),
                icon: 'bi-chat-left-dots',
            ));
        }

        return redirect()->route('admin.feedback.show', $feedback)
            ->with('success', 'Response sent to the submitter.');
    }

    /** Map a user to a system-feedback role. */
    private function roleFor(User $user): string
    {
        return $user->user_type === 'contractor' ? 'contractor' : 'client';
    }
}
