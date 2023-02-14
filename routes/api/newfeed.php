<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewFeedController;

Route::prefix('newfeed')->group(function () {
    Route::get('', [NewFeedController::class, 'index']); //lấy ra list newfeed
    Route::get('{mangaId}/chapter/{id}', [NewFeedController::class, 'show']); //lấy ra thông tin người dùng khi đọc chapter
    Route::get('{id}', [NewFeedController::class, 'detail']); //lấy ra newfeed_detail
});
