  <?php

  use App\Http\Controllers\Api\AuthController;
  use App\Http\Controllers\Api\ProblemController;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Route;
  use App\Http\Controllers\Api\UserController;
  use App\Http\Controllers\Api\ApplicationController;
  use App\Http\Controllers\Api\TicketController;


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

  Route::post('login', [AuthController::class,'login'])->name('login');
  Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware('role:admin')->group(function () {
        Route::get('users', [UserController::class,'index'])->name('getData');
        Route::post('users', [UserController::class,'store'])->name('postData');
        Route::get('users/{id_user}', [UserController::class,'show'])->name('getDataById');
        Route::PUT('users/{id_user}', [UserController::class,'update'])->name('putData');
        Route::DELETE('users/{id_user}', [UserController::class,'destroy'])->name('deleteData');

        Route::get('/users', [UserController::class, 'index']);
        Route::get('applications', [ApplicationController::class,'index'])->name('getApp');
        Route::post('applications', [ApplicationController::class,'store'])->name('postApp');
        Route::get('applications/{id_application}', [ApplicationController::class,'show'])->name('getAppById');
        Route::PUT('applications/{id_application}', [ApplicationController::class,'update'])->name('putApp');

        Route::get('problems', [ProblemController::class,'index'])->name('getApp');
        Route::post('problems', [ProblemController::class,'store'])->name('postApp');
        Route::get('problems/{id_problem}', [ProblemController::class,'show'])->name('getAppById');
        Route::PUT('problems/{id_problem}', [ProblemController::class,'update'])->name('putApp');

      });

      Route::middleware('role:user')->group(function () {
        // Route::get('/profile', function (Request $request) {
        //   return $request->user();
        // });
      });

    Route::get('tickets', [TicketController::class,'index'])->name('getTickets');
    Route::post('tickets', [TicketController::class,'store'])->name('postTickets');
    Route::get('tickets/{ticket_code}', [TicketController::class, 'show'])->name('getTicketById');
});


