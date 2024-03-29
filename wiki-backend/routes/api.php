<?php

use App\Http\Controllers\ArticleController;
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
// Route::get('/manuals', [ManualController::class, 'index']);
// Route::get('/manuals/{id}', [ManualController::class, 'manualsBySpace']);

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     $user = $request->user();
//     $user->makeVisible('password');
//     return response()->json(['user' => $user]);
// });





// Route::get('/getUser', [UserController::class, 'getUser']);


// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// ----------------------------------PUBLIC ROUTES
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------

// GET USER STATUS
Route::get('/auth-status', [UserController::class, 'getAuthStatus']);
// Route::get('/getEnv', function () {
// return 3;
// });




// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
// -------------------------------------ANY AUTH USER
// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------
Route::group(['middleware' => ['auth:sanctum']], function () {

    // USER ROUTES
    // GET AUTH USER DATA
    Route::get('/users/getauth', [UserController::class, 'getAuth']);

    // -- UPDATE AUTH USER PROFILE
    Route::put('/update', [UserController::class, 'update']);

    // --LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);





    // SPACE ROUTES 
    // -- GET AUTH USER SPACES
    Route::get('/spaces', [SpaceController::class, 'index']);

    // -- GET SPACE BY ID 
    Route::get('/spaces/{id}', [SpaceController::class, 'show']);

    // -- SEARCH
    Route::get('/spaces/search/{title}', [SpaceController::class, 'search']);


    // MANUAL ROUTES
    // -- Consultation
    Route::get('/manuals', [ManualController::class, 'index']);

    // -- get manuals by space id
    Route::get('/manuals/{id}', [ManualController::class, 'getManualsBySpaceId']);
    // -- Show 
    Route::get('/manuals/{id}/show', [ManualController::class, 'show']);
    // -- Search
    Route::get('/manuals/search/{title}', [ManualController::class, 'search']);
    // --Add 
    Route::post('/manuals', [ManualController::class, 'store']);
    // --Update
    Route::put('/manuals/{id}', [ManualController::class, 'update']);
    // --Delete
    Route::delete('/manuals/{id}', [ManualController::class, 'destroy']);


    // ARTICLE ROUTES
    // -- Consultation
    Route::get('/articles', [ArticleController::class, 'index']);

    // -- get articles by space id
    Route::get('/articles/{id}/space', [ArticleController::class, 'getArticlesBySpaceId']);
    // -- get articles by manual id
    Route::get('/articles/{id}/manual', [ArticleController::class, 'getArticleByManualId']);
    // -- Show 
    Route::get('/articles/{id}/show', [ArticleController::class, 'show']);
    // -- Show by Article
    Route::get('/articles/{id}/{version}/showByVersion', [ArticleController::class, 'showByVersion']);
    // -- Search
    Route::get('/articles/search/{title}', [ArticleController::class, 'search']);
    // --Add 
    Route::post('/articles', [ArticleController::class, 'store']);
    // --Update
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    // --Delete
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
});



// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
// -----------------------------------   ADMIN   ---------------------------
// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
// Admin Routes
Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {

    //USER MANAGEMENT

    // -- GET ALL USERS
    Route::get('/users', [UserController::class, 'index']);

    // GET USER BY ID
    Route::get('/users/{id}', [UserController::class, 'show'])
        ->where('id', '[0-9]+');

    // already approved => to 1 or -1 or -2
    Route::post('/updateUserStatus', [UserController::class, 'updateStatus']);
    // hasn't approved yet => to 1 and send an email
    Route::post('/approve', [UserController::class, 'approve']);

    // -- DELETE => Soft delete [status = 0]
    Route::post('/delete', [UserController::class, 'updateStatus']);

    // DELETE PERMENATLLY
    Route::delete('/users/{id}', [UserController::class, 'destroy']);


    // UPDATE USER DATA
    Route::put('/update/{id}', [UserController::class, 'update']);

    // assignspace
    Route::post('/assignspace', [UserController::class, 'assignSpace']);
    // assignmanual
    Route::post('/assignmanual', [UserController::class, 'assignManual']);

    // CREATE USER
    Route::post('/createUser', [UserController::class, 'store']);





    // SPACE ROUTES 
    // --Create
    Route::post('/spaces', [SpaceController::class, 'store']);
    // --Update
    Route::put('/spaces/{id}', [SpaceController::class, 'update']);
    // --Delete
    Route::delete('/spaces/{id}', [SpaceController::class, 'destroy']);

    // assignUserToSpace
    Route::post('/assignUserToSpace', [SpaceController::class, 'assignUserToSpace']);

    // MANUAL ROUTES
    // assignUserToManual
    Route::post('/assignUserToManual', [ManualController::class, 'assignUserToManual']);
    
    
    // Article
    Route::get('/exportArticle/{id}', [ArticleController::class, 'exportArticle']);
    // assignUserToArticle
    Route::post('/assignUsersToArticle', [ArticleController::class, 'assignUsersToArticle']);

});