<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ChapterController;

Route::prefix('manga')->group(function () {
    Route::get('', [MangaController::class, 'index']);
    Route::get('{id}', [MangaController::class, 'show']);

    Route::get('{mangaId}/chapter', [ChapterController::class, 'index']);
    Route::get('{mangaId}/chapter/{id}', [ChapterController::class, 'show']);
});
