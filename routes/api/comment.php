<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::get('manga/{mangaId}/comments', [CommentController::class, 'index']);

Route::post('manga/{mangaId}/comments', [CommentController::class, 'store_manga_id']); //comment theo manga_id
Route::post('manga/{mangaId}/comments/{chapterId}', [CommentController::class, 'store_chapter_id']); //comment theo chapter_id
Route::put('manga/{mangaId}/comments/{id}', [CommentController::class, 'update']);
Route::delete('manga/{mangaId}/comments/{id}', [CommentController::class, 'destroy']);
