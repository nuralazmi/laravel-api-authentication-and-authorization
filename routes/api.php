<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/error', [AuthController::class, 'error'])->name('auth.error');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/auth/logout', [AuthController::class, 'logout']);
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/test', function () {
            echo 'Buraya sadece adminler görebilir. Evet adminsiniz.';
        });
    });

});
