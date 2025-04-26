<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Problem;
use App\Models\Application;
use App\Models\TicketImages;
use App\Models\TicketLinks;
use App\Models\Discussion;

class TicketWebController extends Controller
{
  public function show($ticket_code)
  {
    $ticket = Ticket::with(['images', 'links', 'user', 'problem', 'application'])
        ->where('ticket_code', $ticket_code)
        ->first();
    $users = User::where('role', 'admin')->where('status', 'active')->get();
    $problems = Problem::all();
    $applications = Application::where('status', 'active')->get();
    $images = TicketImages::all();
    $links = TicketLinks::all();
    $discussions = Discussion::where('ticket_code', $ticket->ticket_code)->get();

    if (!$ticket) {
        return view('content.tickets.index');
    }

    // Render tampilan Blade dengan data tiket
    return view('content.tickets.detail', compact('ticket', 'users', 'problems', 'applications', 'images', 'links', 'discussions'));
  }
}
