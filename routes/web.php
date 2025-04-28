<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'homepage'])->name('homepage');

Route::get('/articles', [ArticleController::class, 'index'])->name('article.index');
Route::get('/articles/create', [ArticleController::class, 'create'])->name('article.create')
    ->middleware('auth');
Route::post('/articles/store', [ArticleController::class, 'store'])->name('article.store')
    ->middleware('auth');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('article.show');
