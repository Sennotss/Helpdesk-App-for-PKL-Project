<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Problem;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $problems = Problem::all();

      return response()->json($problems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(),[
        'name'=>'required|string|max:25',
      ]);

      if ($validator->fails()) {
        return ApiResponse::error('Validasi gagal', 422, $validator->errors()->toArray());
      }

      try {
        $problem = Problem::create([
            'name' => $request->name,
        ]);

        return ApiResponse::success($problem, 'Masalah berhasil ditambahkan', 201);
      } catch (\Exception $e) {
          return ApiResponse::error('Gagal menambahkan masalah', 500, $e->getMessage());
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      $problem = Problem::findOrFail($id);
      return ApiResponse::success($problem, "data " . $id . " berhasil diambil", 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
      ]);

      if ($validator->fails()) {
          return ApiResponse::error('Validasi gagal', 422, $validator->errors()->toArray());
      }

      $problem = Problem::findOrFail($id);

      try {
          $problem->update([
              'name' => $request->name,
          ]);

          return ApiResponse::success($problem, 'Masalah berhasil diupdate', 200);
      } catch (\Exception $e) {
          return ApiResponse::error('Gagal mengupdate masalah', 500, $e->getMessage());
      }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
