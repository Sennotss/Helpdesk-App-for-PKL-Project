<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussion;

class DiscussionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|exists:tickets,ticket_code',
            'message' => 'required|string',
        ]);


        $discussion = Discussion::create([
            'ticket_code' => $request->ticket_code,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success',
            'discussion' => $discussion->load('user')
        ]);
    }

    public function index($ticket_code)
    {
        return Discussion::with('user')
            ->where('ticket_code', $ticket_code)
            ->orderBy('created_at')
            ->get();
    }
}
