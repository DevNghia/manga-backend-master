<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\StaticController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('', [StaticController::class, 'index']);

Route::get('signup/confirm', [AuthController::class, 'verifyAccount']);
Route::get('signup/success', [AuthController::class, 'signupSuccess'])->name('signup.success');
Route::get('signup/fails', [AuthController::class, 'signupFails'])->name('signup.fails');

