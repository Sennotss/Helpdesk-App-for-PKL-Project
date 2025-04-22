<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use App\Models\HelpdeskSchedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
  public function index()
  {
    $role = session('auth_user')['role'] ?? 'user';
    $total = Ticket::count();
    $open = Ticket::where('status', 'open')->count();
    $onProgress = Ticket::where('status', 'onprogress')->count();
    $revisi = Ticket::where('status', 'revisi')->count();
    $resolved = Ticket::where('status', 'resolved')->count();

    $jadwalCount = HelpdeskSchedule::count();
    $jadwalToday = HelpdeskSchedule::with('user') // Eager loading user
      ->whereDate('date', Carbon::today())
      ->first();;
    $shiftPagiUser = $jadwalToday ? User::find($jadwalToday->shift_pagi_user_id) : null;
    $shiftSoreUser = $jadwalToday ? User::find($jadwalToday->shift_sore_user_id) : null;

    if ($role === 'admin') {
        return view('content.dashboard.dashboards', compact('total', 'open', 'onProgress', 'revisi', 'resolved', 'shiftPagiUser', 'shiftSoreUser'));
    }

    $userId = session('auth_user')['id'];

    $tickets = Ticket::where('user_id', $userId)->latest()->take(5)->get();

    return view('content.dashboard.dashboard-user', compact('tickets'));
  }
}
