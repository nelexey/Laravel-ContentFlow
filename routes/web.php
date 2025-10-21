<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\ArticleController;    
use App\Http\Controllers\CommentController;     
use Illuminate\Support\Facades\Route;

Route::get('/', [ArticleController::class, 'index'])->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('articles', ArticleController::class)->only([
    'index', 
    'show'   
]);


Route::resource('articles', ArticleController::class)->except([
    'index', 
    'show'   
])->middleware(['auth', 'verified']);


Route::post('articles/{article}/comments', [CommentController::class, 'store'])
        ->name('comments.store')
        ->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->group(function () { 
    Route::get('/admin/comments', [ModerationController::class, 'index'])
        ->name('admin.comments.index');
    
    Route::patch('/admin/comments/{comment}/approve', [ModerationController::class, 'approve'])
        ->name('admin.comments.approve');
        
    Route::delete('/admin/comments/{comment}/reject', [ModerationController::class, 'reject'])
        ->name('admin.comments.reject');
});

require __DIR__.'/auth.php';