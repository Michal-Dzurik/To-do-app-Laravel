<?php

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

Route::post('register',[\App\Http\Controllers\AuthController::class,'register'])->name('register');
Route::post('login',[\App\Http\Controllers\AuthController::class,'login'])->name('login');

// user
Route::get('users',[\App\Http\Controllers\UserController::class,'index']);
Route::get('user/{id}',[\App\Http\Controllers\UserController::class,'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
