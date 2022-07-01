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

// Auth
Route::post('register',[\App\Http\Controllers\AuthController::class,'register'])->name('register');
Route::post('login',[\App\Http\Controllers\AuthController::class,'login'])->name('login');

// Users
Route::get('users',[\App\Http\Controllers\UserController::class,'index'])->name('user.index');
Route::get('user/{id}',[\App\Http\Controllers\UserController::class,'show'])->name('user.show');

// Tasks
Route::get('tasks',[\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');



Route::middleware('auth:sanctum')->group( function () {
    // Users
    Route::get('user/{id}/tasks',[\App\Http\Controllers\UserController::class,'showTasks']);

    // Tasks
    Route::put('task/{id}/done',[\App\Http\Controllers\TaskController::class,'done'])->name('task.done');
    Route::put('task/{id}/undone',[\App\Http\Controllers\TaskController::class,'undone'])->name('task.undone');

    Route::put('task/{id}/undestroy',[\App\Http\Controllers\TaskController::class,'undestroy'])->name('task.done');

    Route::put('task/{id}/share/{user_id}',[\App\Http\Controllers\TaskController::class,'share'])->name('task.share');
    Route::put('task/{id}/unshare/{user_id}',[\App\Http\Controllers\TaskController::class,'unshare'])->name('task.unshare');

    Route::apiResource('task',\App\Http\Controllers\TaskController::class)->except(['index']);
    Route::get('task/{id}/users',[\App\Http\Controllers\TaskController::class,'showUsers']);
});
