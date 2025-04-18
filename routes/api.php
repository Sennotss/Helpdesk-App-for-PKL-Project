  <?php

  use App\Http\Controllers\Api\ProblemController;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;
  use App\Http\Controllers\Api\UserController;
  use App\Http\Controllers\Api\ApplicationController;


  /*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider and all of them will
  | be assigned to the "api" middleware group. Make something great!
  |
  */

  Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
      return $request->user();
  });

  Route::get('users', [UserController::class,'index'])->name('getData');
  Route::post('users', [UserController::class,'store'])->name('postData');
  Route::get('users/{id_user}', [UserController::class,'show'])->name('getDataById');
  Route::PUT('users/{id_user}', [UserController::class,'update'])->name('putData');
  Route::DELETE('users/{id_user}', [UserController::class,'destroy'])->name('deleteData');

  Route::get('applications', [ApplicationController::class,'index'])->name('getApp');
  Route::post('applications', [ApplicationController::class,'store'])->name('postApp');
  Route::get('applications/{id_application}', [ApplicationController::class,'show'])->name('getAppById');
  Route::PUT('applications/{id_application}', [ApplicationController::class,'update'])->name('putApp');

  Route::get('problems', [ProblemController::class,'index'])->name('getApp');
  Route::post('problems', [ProblemController::class,'store'])->name('postApp');
  Route::get('problems/{id_problem}', [ProblemController::class,'show'])->name('getAppById');
  Route::PUT('problems/{id_problem}', [ProblemController::class,'update'])->name('putApp');
