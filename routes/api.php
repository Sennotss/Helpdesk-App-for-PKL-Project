  <?php

  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;
  use App\Http\Controllers\Api\UserController;

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
