<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\LoginController;
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
  Route::get('/dashboard', function () {
      return view('content.dashboard.dashboards-analytics');
  });
  Route::get('users', function () {
    return view('content.users.index');
  })->name('users');

  Route::get('applications', function () {
    return view('content.aplications.index');
  })->name('applications');

  Route::get('problems', function () {
    return view('content.problem.index');
  })->name('problems');
});


// routes/web.php

