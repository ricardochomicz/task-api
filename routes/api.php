<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::group(['middleware' => 'auth:api'], function () {
    Route::get('logout', [AuthController::class, 'logout']);

    Route::resource('users', UserController::class)->except(['create', 'edit']);

    Route::resource('tasks', TaskController::class)->except(['create', 'edit']);
    Route::put('tasks/{task}/favorite', [TaskController::class, 'updateFavorite']);
    Route::put('tasks/{task}/color', [TaskController::class, 'updateColor']);
});
