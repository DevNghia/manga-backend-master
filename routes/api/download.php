<?php

use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Route;

Route::prefix('download')->group(function () {
    Route::get('', [DownloadController::class, 'index']);

    Route::get('{mangaId}/chapter/{chapterId}', [DownloadController::class, 'download']);
});
