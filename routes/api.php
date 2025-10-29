<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('/articles', [ArticleController::class, 'index'])->name('api.articles.index');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('api.articles.show');
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/articles', [ArticleController::class, 'store'])->name('api.articles.store');
        Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('api.articles.update');
        Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('api.articles.destroy');
        
        Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('api.comments.store');
        Route::put('/comments/{comment}/approve', [CommentController::class, 'approve'])->name('api.comments.approve');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('api.comments.destroy');
    });
});