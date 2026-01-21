<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortfolioController;

// API Routes - Versioned with rate limiting
Route::middleware(['throttle:api'])->prefix('api/v1')->name('api.v1.')->group(function () {
    Route::get('/projects', [PortfolioController::class, 'projects'])->name('projects');
    Route::get('/articles', [PortfolioController::class, 'articles'])->name('articles');
    Route::get('/articles/{slug}', [PortfolioController::class, 'article'])->name('article');
    Route::get('/skills', [PortfolioController::class, 'skills'])->name('skills');
    Route::get('/experiences', [PortfolioController::class, 'experiences'])->name('experiences');
});

// Legacy API routes (redirect to v1 for backward compatibility) - also rate limited
Route::middleware(['throttle:api'])->prefix('api')->name('api.')->group(function () {
    Route::get('/projects', fn() => redirect()->route('api.v1.projects'));
    Route::get('/articles', fn() => redirect()->route('api.v1.articles'));
    Route::get('/articles/{slug}', fn($slug) => redirect()->route('api.v1.article', $slug));
    Route::get('/skills', fn() => redirect()->route('api.v1.skills'));
    Route::get('/experiences', fn() => redirect()->route('api.v1.experiences'));
});

