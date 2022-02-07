<?php

use App\Http\Controllers\Api\AuthControler;
use App\Http\Controllers\Api\AuthController2;
use App\Http\Controllers\Api\SekolahController;
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

// Route::post('register', [AuthController2::class, 'Register']);
// Route::post('login', [AuthController2::class, 'Login']);



















Route::post('register', [AuthControler::class, 'register']);
Route::post('login', [AuthControler::class, 'Login']);

Route::middleware('auth:sanctum')->group(function () {

    // sekolah
    Route::get('/sekolah', [SekolahController::class, 'index']);
    Route::post('/sekolah', [SekolahController::class, 'store']);
    Route::put('/sekolah/{id}', [SekolahController::class, 'update']);
    Route::delete('/sekolah/{id}', [SekolahController::class, 'destroy']);


    // update user
    Route::get('user', [AuthControler::class, 'index']);
    Route::put('user', [AuthControler::class, 'update']);

    // logout
    Route::post('logout', [AuthControler::class, 'destroy']);
});
