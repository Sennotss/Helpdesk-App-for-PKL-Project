<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Problem;
use App\Models\Application;
use App\Models\TicketImages;
use App\Models\TicketLinks;

class TicketController extends Controller
{
  public function show($ticket_code)
  {
    $ticket = Ticket::with(['images', 'links', 'user', 'problem', 'application'])
        ->where('ticket_code', $ticket_code)
        ->first();
    $users = User::all();
    $problems = Problem::all();
    $applications = Application::all();
    $images = TicketImages::all();
    $links = TicketLinks::all();
    if (!$ticket) {
        return view('content.tickets.index');
    }

    // Render tampilan Blade dengan data tiket
    return view('content.tickets.detail', compact('ticket', 'users', 'problems', 'applications', 'images', 'links'));
  }
}
