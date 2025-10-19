<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ArticleController::class, 'index'])->name('home');
Route::resource('articles', ArticleController::class);  // Набор CRUD маршрутов (index, create, store, show, edit, update, destroy)
Route::post('articles/{article}/comments', [CommentController::class, 'store'])
        ->name('comments.store');