<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class ContractorFeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $feedback = Feedback::with(['client'])
            ->where('contractor_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('contractor/feedback/index', compact('feedback'));
    }
}
