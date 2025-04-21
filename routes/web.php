<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TicketWebController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;

Route::get('/login', [
  LoginController::class, 'index'
]);

Route::post('/store-token', function (Request $request) {
  session(['auth_token' => $request->token]);
  session(['auth_user' => $request->user]);
  return response()->json(['message' => 'Token disimpan di session']);
});

Route::middleware(['check.token'])->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  Route::get('users', function () {
    return view('content.users.index');
  })->name('users');

  Route::get('applications', function () {
    return view('content.aplications.index');
  })->name('applications');

  Route::get('problems', function () {
    return view('content.problem.index');
  })->name('problems');

  Route::get('tickets', function () {
    return view('content.tickets.index');
  })->name('tickets');

  Route::get('tickets/detail/{ticket_code}', [TicketWebController::class, 'show'])->name('ticket');

  Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules');
});


// routes/web.php

