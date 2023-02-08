<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::get('manga/{mangaId}/comments', [CommentController::class, 'index']);

Route::post('manga/{mangaId}/comments', [CommentController::class, 'store']);

Route::put('manga/{mangaId}/comments/{id}', [CommentController::class, 'update']);

Route::delete('manga/{mangaId}/comments/{id}', [CommentController::class, 'destroy']);
