<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/usuario', function (Request $request) {
    return 'Opa';
});

Route::resource('users', UserController::class);
