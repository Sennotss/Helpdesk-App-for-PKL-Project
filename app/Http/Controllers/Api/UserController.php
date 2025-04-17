<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;
use App\Enums\UserRole;
use Illuminate\Validation\Rules\Enum;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $users = User::all();

      return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(),[
        'name'=>'required|string|max:255',
        'email'=> 'required|email|unique:users,email',
        'password'=> 'required|string|min:8',
        'role' => ['required', new Enum(UserRole::class)],
      ]);

      if ($validator->fails()) {
        return ApiResponse::error('Validasi gagal', 422, $validator->errors()->toArray());
      }

      try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active'
        ]);

        return ApiResponse::success($user, 'User berhasil ditambahkan', 201);
      } catch (\Exception $e) {
          return ApiResponse::error('Gagal menambahkan user', 500, $e->getMessage());
      }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return ApiResponse::success($user, "data " . $id . " berhasil diambil", 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => ['required', new Enum(UserRole::class)],
            'status' => ['required', new Enum(UserStatus::class)],
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validasi gagal', 422, $validator->errors()->toArray());
        }

        $user = User::findOrFail($id);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
            ]);

            return ApiResponse::success($user, 'User berhasil diupdate', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Gagal mengupdate user', 500, $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      try {
        $user = User::findOrFail($id);
        $user->delete();
        return ApiResponse::success(null, 'User berhasil dihapus', 200);
      } catch (\Exception $e) {
          return ApiResponse::error('Gagal menghapus user', 500, $e->getMessage());
      }
    }
}
