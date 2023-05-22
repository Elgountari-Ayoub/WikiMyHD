<?php

use App\Http\Controllers\SpaceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::resource('products', ProductController::class);

// Public routes
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
Route::get('/spaces', [SpaceController::class, 'index']);
Route::get('/spaces/{id}', [SpaceController::class, 'show']);
Route::get('/spaces/search/{title}', [SpaceController::class, 'search']);

Route::post('spaces', [SpaceController::class, 'store']);

// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::put('/spaces/{id}', [SpaceController::class, 'update']);
    Route::delete('/spaces/{id}', [SpaceController::class, 'destroy']);
    Route::post('/approve', [UserController::class, 'setStatus']);
    // Route::post('/spaces', [SpaceController::class, 'store'])->middleware('admin');
    // Route::put('/spaces/{id}', [SpaceController::class, 'update'])->middleware('admin');
    // Route::delete('/spaces/{id}', [SpaceController::class, 'destroy'])->middleware('admin');
    // Route::post('/approve', [UserController::class, 'setStatus'])->middleware('admin');
    
    Route::post('/logout', [AuthController::class, 'logout']);
});



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/auth-status', function (Request $request) {
    return response()->json([
        'authenticated' => $request->user() !== null
    ]);
})->middleware('auth:sanctum');