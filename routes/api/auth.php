<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;

Route::post('sign-up', [AuthController::class, 'signUp']);

Route::post('login', [AuthController::class, 'login']);

Route::post('verify-account', [AuthController::class, 'verifyAccount']);

Route::post('send-verify', [AuthController::class, 'sendVerify']);
