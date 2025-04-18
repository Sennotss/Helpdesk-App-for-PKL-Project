<?php

namespace App\Http\Controllers\Api;

use App\Enums\AppStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;
use Illuminate\Validation\Rules\Enum;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $applications = Application::all();

      return response()->json($applications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(),[
        'name'=>'required|string|max:25',
        'description'=> 'required|string|max:255',
      ]);

      if ($validator->fails()) {
        return ApiResponse::error('Validasi gagal', 422, $validator->errors()->toArray());
      }

      try {
        $application = Application::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'active'
        ]);

        return ApiResponse::success($application, 'Aplikasi berhasil ditambahkan', 201);
      } catch (\Exception $e) {
          return ApiResponse::error('Gagal menambahkan aplikasi', 500, $e->getMessage());
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      $application = Application::findOrFail($id);
      return ApiResponse::success($application, "data " . $id . " berhasil diambil", 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description'=> 'required|string|max:255',
        'status' => ['required', new Enum(AppStatus::class)],
      ]);

      if ($validator->fails()) {
          return ApiResponse::error('Validasi gagal', 422, $validator->errors()->toArray());
      }

      $application = Application::findOrFail($id);

      try {
          $application->update([
              'name' => $request->name,
              'description' => $request->description,
              'status'=> $request->status
          ]);

          return ApiResponse::success($application, 'Aplikasi berhasil diupdate', 200);
      } catch (\Exception $e) {
          return ApiResponse::error('Gagal mengupdate aplikasi', 500, $e->getMessage());
      }
    }
}
