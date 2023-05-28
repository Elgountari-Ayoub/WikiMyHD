<?php

use App\Http\Controllers\ManualController;
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

// Public routes for testing
Route::get('/manuals', [ManualController::class, 'index']);

// ANY AUTH USER
Route::group(['middleware' => ['auth:sanctum']], function () {

    // SPACE ROUTES 
    // -- Consultation
    Route::get('/spaces', [SpaceController::class, 'index']);
    // -- Show 
    Route::get('/spaces/{id}', [SpaceController::class, 'show']);
    // -- Search
    Route::get('/spaces/search/{title}', [SpaceController::class, 'search']);



    // SPACE ROUTES 
    // -- Consultation
    // Route::get('/manuals', [ManualController::class, 'index']);
    // -- Show 
    Route::get('/manuals/{id}', [ManualController::class, 'show']);
    // -- Search
    Route::get('/manuals/search/{title}', [ManualController::class, 'search']);
    // --Add 
    Route::post('/manuals', [ManualController::class, 'store']);
    // --Update
    Route::put('/manuals/{id}', [ManualController::class, 'update']);
    // --Delete
    Route::delete('/manuals/{id}', [ManualController::class, 'destroy']);


    // USER
    // -- update profile 
    Route::put('/user', [UserController::class, 'update']);

    // --logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Admin Routes
Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    // SPACE ROUTES 
    // --Add 
    Route::post('/spaces', [SpaceController::class, 'store'])->middleware('admin');
    // --Update
    Route::put('/spaces/{id}', [SpaceController::class, 'update'])->middleware('admin');
    // --Delete
    Route::delete('/spaces/{id}', [SpaceController::class, 'destroy'])->middleware('admin');



    //USER MANAGEMENT
    // -- Get users
    Route::get('/users', [UserController::class, 'index']);
    // -- Approvement
    Route::post('/approve', [UserController::class, 'setStatus']);
    // -- Delete => Soft delete [status = 0]
    Route::post('/delete', [UserController::class, 'setStatus']);
    // update his data
    Route::post('/delete', [UserController::class, 'setStatus']);
});



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = $request->user();
    $user->makeVisible('password');
    return response()->json(['user' => $user]);
});


Route::get('/auth-status', function (Request $request) {
    return response()->json([
        'authenticated' => $request->user() !== null
    ]);
});
