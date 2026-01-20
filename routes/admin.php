<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\CvController;

// Dashboard
Route::get('dashboard', [AnalyticsController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Analytics route for detailed analytics
Route::get('admin/analytics', [AnalyticsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.analytics.index');

// Admin routes
Route::middleware(['auth', 'verified', 'throttle:60,1'])->prefix('admin')->name('admin.')->group(function () {
    // Resources
    Route::resource('projects', ProjectController::class);
    Route::resource('skills', SkillController::class);
    Route::resource('experiences', ExperienceController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('articles', AdminArticleController::class);
    Route::resource('comments', CommentController::class)->only(['index', 'destroy']);
    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    
    // Article actions
    Route::post('articles/bulk-delete', [AdminArticleController::class, 'bulkDelete'])->name('articles.bulk-delete');
    Route::get('articles/export', [AdminArticleController::class, 'export'])->name('articles.export');
    
    // Comment actions
    Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::post('comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
    
    // Newsletter
    Route::get('newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
    Route::get('newsletter/export', [NewsletterController::class, 'export'])->name('newsletter.export');
    Route::delete('newsletter/{subscriber}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');
    
    // CV Management
    Route::get('cv', [CvController::class, 'index'])->name('cv.index');
    Route::post('cv', [CvController::class, 'store'])->name('cv.store');
    Route::delete('cv/{cv}', [CvController::class, 'destroy'])->name('cv.destroy');
});

