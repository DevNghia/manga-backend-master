<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewFeedController;

Route::prefix('newfeed')->group(function () {
    Route::get('', [NewFeedController::class, 'index']);

    Route::post('{id}/manga', [NewFeedController::class, 'store']);
});
