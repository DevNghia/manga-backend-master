<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/info', [UserController::class, 'info']);

Route::group(['prefix' => 'user'], function () {
    Route::put('update-profile', [UserController::class, 'updateProfile']);

    Route::put('change-password', [UserController::class, 'changePassword']);
});
