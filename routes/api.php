<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

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

require __DIR__ . '/api/auth.php';

Route::get('categories', [CategoryController::class, 'index']);

require __DIR__ . '/api/manga.php';
Route::get('home', [HomeController::class, 'index']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);

    Route::post('logout', [AuthController::class, 'logout']);

    require __DIR__ . '/api/user.php';
    require __DIR__ . '/api/comment.php';
    require __DIR__ . '/api/favorite.php';
    require __DIR__ . '/api/newfeed.php';
    require __DIR__ . '/api/download.php';
});
