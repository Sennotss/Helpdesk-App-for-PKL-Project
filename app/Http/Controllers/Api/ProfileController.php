<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Helpers\ApiResponse;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        return ApiResponse::success($request->user(), 'Data profile berhasil diambil');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:100'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validasi gagal', 422, $validator->errors()->toArray());
        }

        $user = $request->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return ApiResponse::success($user, 'Profile berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validasi gagal', 422, $validator->errors()->toArray());
        }

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return ApiResponse::error('Password lama salah', 422, ['old_password' => ['Password lama tidak sesuai']]);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return ApiResponse::success(null, 'Password berhasil diperbarui');
    }
}
