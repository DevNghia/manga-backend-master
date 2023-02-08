<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FavoriteController;

Route::prefix('favorite')->group(function () {
    Route::get('', [FavoriteController::class, 'index']);

    Route::post('{id}/manga', [FavoriteController::class, 'store']);
});
