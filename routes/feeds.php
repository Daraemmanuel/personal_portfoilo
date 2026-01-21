<?php

use App\Http\Controllers\FeedController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Sitemap XML
Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap');

// RSS Feed
Route::get('/feed', [FeedController::class, 'index'])
    ->name('feed');


