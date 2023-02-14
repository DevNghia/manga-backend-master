<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewFeedController;

Route::prefix('newfeed')->group(function () {
    Route::get('', [NewFeedController::class, 'index']);
    Route::get('{mangaId}/chapter/{id}', [NewFeedController::class, 'show']);
    Route::get('{id}', [NewFeedController::class, 'detail']);
});
