<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussions;

class DiscussionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'message' => 'required|string',
        ]);

        $discussion = Discussions::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success',
            'discussion' => $discussion->load('user')
        ]);
    }

    public function index($ticket_id)
    {
        return Discussions::with('user')
            ->where('ticket_id', $ticket_id)
            ->orderBy('created_at')
            ->get();
    }
}
