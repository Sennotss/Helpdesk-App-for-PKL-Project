<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\LoginController;

Route::get('/login', [
  LoginController::class, 'index'
]);

// Main Page Route
Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');

// routes/web.php
Route::get('users', function () {
  return view('content.users.index');
})->name('users');

Route::get('applications', function () {
  return view('content.aplications.index');
})->name('applications');

Route::get('problems', function () {
  return view('content.problem.index');
})->name('problems');

