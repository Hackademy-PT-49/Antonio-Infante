<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RevisorController;
use App\Http\Controllers\WriterController;
use Illuminate\Support\Facades\Route;

// Rotte pubbliche
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');
Route::get('/careers', [PublicController::class, 'careers'])->name('careers');
Route::post('/careers/submit', [PublicController::class, 'careersSubmit'])->name('careers.submit');

// Rotte per gli articoli
Route::get('/articles', [ArticleController::class, 'index'])->name('article.index');
Route::get('/articles/show/{article:slug}', [ArticleController::class, 'show'])->name('article.show');
Route::get('/articles/category/{category}', [ArticleController::class, 'byCategory'])->name('article.by-category');
Route::get('/articles/user/{user}', [ArticleController::class, 'byUser'])->name('article.by-user');
Route::get('/articles/search', [ArticleController::class, 'search'])->name('article.search');

// Rotte per writer
Route::middleware(['auth', 'writer'])->group(function () {
    Route::get('/writer/dashboard', [WriterController::class, 'dashboard'])->name('writer.dashboard');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('article.create');
    Route::post('/articles/store', [ArticleController::class, 'store'])->name('article.store');
    Route::get('/articles/edit/{article}', [ArticleController::class, 'edit'])->name('article.edit');
    Route::put('/articles/update/{article}', [ArticleController::class, 'update'])->name('article.update');
    Route::delete('/articles/destroy/{article}', [ArticleController::class, 'destroy'])->name('article.destroy');
});

// Rotte per revisori
Route::middleware(['auth', 'revisor'])->group(function () {
    Route::get('/revisor/dashboard', [RevisorController::class, 'dashboard'])->name('revisor.dashboard');
    Route::patch('/revisor/article/{article}/accept', [RevisorController::class, 'acceptArticle'])->name('revisor.accept');
    Route::patch('/revisor/article/{article}/reject', [RevisorController::class, 'rejectArticle'])->name('revisor.reject');
    Route::patch('/revisor/article/{article}/return', [RevisorController::class, 'returnArticle'])->name('revisor.return');
});

// Rotte per admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Gestione richieste ruoli
    Route::patch('/admin/users/{user}/make-admin', [AdminController::class, 'makeUserAdmin'])->name('admin.make-admin');
    Route::patch('/admin/users/{user}/make-revisor', [AdminController::class, 'makeUserRevisor'])->name('admin.make-revisor');
    Route::patch('/admin/users/{user}/make-writer', [AdminController::class, 'makeUserWriter'])->name('admin.make-writer');
    
    // Gestione tags
    Route::put('/admin/tags/update/{tag}', [AdminController::class, 'updateTag'])->name('admin.update-tag');
    Route::delete('/admin/tags/delete/{tag}', [AdminController::class, 'deleteTag'])->name('admin.delete-tag');
    
    // Gestione categorie
    Route::post('/admin/categories/store', [AdminController::class, 'storeCategory'])->name('admin.category.store');
    Route::put('/admin/categories/update/{category}', [AdminController::class, 'updateCategory'])->name('admin.update-category');
    Route::delete('/admin/categories/delete/{category}', [AdminController::class, 'deleteCategory'])->name('admin.delete-category');
});