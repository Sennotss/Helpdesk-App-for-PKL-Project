<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $credentials = $request->only("email","password");
    if (!Auth::attempt($credentials)) {
        return ApiResponse::unauthorized("Email atau Password Salah");
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
    ]);
  }

}
