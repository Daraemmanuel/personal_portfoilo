<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentReactionController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\CvController;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\Cv;

// Homepage
Route::get('/', function () {
    $cv = Cv::where('is_active', true)->latest()->first();
    
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Features::enabled(Features::registration()),
        'projects' => Cache::remember('home.projects', 3600, fn() => 
            Project::where('is_archived', false)->orderBy('sort_order')->latest()->get()
        ),
        'skills' => Cache::remember('home.skills', 3600, fn() => 
            Skill::orderBy('sort_order')->get()
        ),
        'experiences' => Cache::remember('home.experiences', 3600, fn() => 
            Experience::orderBy('sort_order')->latest()->get()
        ),
        'testimonials' => Cache::remember('home.testimonials', 3600, fn() => 
            Testimonial::orderBy('sort_order')->limit(6)->get()
        ),
        'cvUrl' => $cv ? route('cv.download') : null,
    ]);
})->name('home');

// Projects
Route::get('/archive', [\App\Http\Controllers\ProjectController::class, 'archive'])->name('archive');
Route::get('/projects/{project}', [\App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');

// CV Download
Route::get('/cv/download', [CvController::class, 'download'])->name('cv.download');

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Articles
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/categories', [ArticleController::class, 'categories'])->name('articles.categories');
Route::get('/articles/tags', [ArticleController::class, 'tags'])->name('articles.tags');
Route::get('/articles/category/{category}', [ArticleController::class, 'category'])->name('articles.category');
Route::get('/articles/tag/{tag}', [ArticleController::class, 'tag'])->name('articles.tag');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Contact form with rate limiting
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:' . (app()->environment('local') ? '20,1' : '5,60'))
    ->name('contact.store');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:10,1')
    ->name('newsletter.subscribe');
Route::post('/newsletter/unsubscribe/{email}', [NewsletterController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe');

// Comments
Route::post('/articles/{article}/comments', [CommentController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('comments.store');
Route::post('/comments/{comment}/reactions', [CommentReactionController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('comments.reactions.store');

