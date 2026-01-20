<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortfolioController;

// API Routes
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/projects', [PortfolioController::class, 'projects'])->name('projects');
    Route::get('/articles', [PortfolioController::class, 'articles'])->name('articles');
    Route::get('/articles/{slug}', [PortfolioController::class, 'article'])->name('article');
    Route::get('/skills', [PortfolioController::class, 'skills'])->name('skills');
    Route::get('/experiences', [PortfolioController::class, 'experiences'])->name('experiences');
});

