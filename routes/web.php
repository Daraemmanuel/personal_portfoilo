<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ArticleController;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\Article;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Features::enabled(Features::registration()),
        'projects' => Cache::remember('home.projects', 3600, fn() => 
            Project::orderBy('sort_order')->latest()->get()
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
    ]);
})->name('home');

// Project detail page
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

// Articles
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Contact form with rate limiting
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:' . (app()->environment('local') ? '20,1' : '5,60'))
    ->name('contact.store');

// Sitemap
Route::get('/sitemap.xml', function () {
    $projects = Project::select('id', 'updated_at')->get();
    $articles = Article::published()->select('slug', 'updated_at')->get();
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    // Homepage
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . route('home') . '</loc>' . "\n";
    $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
    $xml .= '    <changefreq>weekly</changefreq>' . "\n";
    $xml .= '    <priority>1.0</priority>' . "\n";
    $xml .= '  </url>' . "\n";
    
    // Projects
    foreach ($projects as $project) {
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . route('projects.show', $project->id) . '</loc>' . "\n";
        $xml .= '    <lastmod>' . $project->updated_at->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    <changefreq>monthly</changefreq>' . "\n";
        $xml .= '    <priority>0.8</priority>' . "\n";
        $xml .= '  </url>' . "\n";
    }
    
    // Articles
    foreach ($articles as $article) {
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . route('articles.show', $article->slug) . '</loc>' . "\n";
        $xml .= '    <lastmod>' . $article->updated_at->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    <changefreq>weekly</changefreq>' . "\n";
        $xml .= '    <priority>0.7</priority>' . "\n";
        $xml .= '  </url>' . "\n";
    }
    
    $xml .= '</urlset>';
    
    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

// RSS Feed
Route::get('/feed', function () {
    $articles = Article::published()
        ->orderBy('published_at', 'desc')
        ->limit(20)
        ->get();
    
    $feed = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $feed .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
    $feed .= '  <channel>' . "\n";
    $feed .= '    <title>DaraEmmanuel Akinyode - Blog</title>' . "\n";
    $feed .= '    <link>' . route('home') . '</link>' . "\n";
    $feed .= '    <description>Latest articles from DaraEmmanuel Akinyode</description>' . "\n";
    $feed .= '    <language>en-us</language>' . "\n";
    $feed .= '    <atom:link href="' . route('feed') . '" rel="self" type="application/rss+xml" />' . "\n";
    
    foreach ($articles as $article) {
        $feed .= '    <item>' . "\n";
        $feed .= '      <title>' . htmlspecialchars($article->title) . '</title>' . "\n";
        $feed .= '      <link>' . route('articles.show', $article->slug) . '</link>' . "\n";
        $feed .= '      <description>' . htmlspecialchars($article->excerpt) . '</description>' . "\n";
        $feed .= '      <pubDate>' . $article->published_at->toRssString() . '</pubDate>' . "\n";
        $feed .= '      <guid>' . route('articles.show', $article->slug) . '</guid>' . "\n";
        $feed .= '    </item>' . "\n";
    }
    
    $feed .= '  </channel>' . "\n";
    $feed .= '</rss>';
    
    return response($feed, 200)->header('Content-Type', 'application/rss+xml');
})->name('feed');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard', [
        'stats' => [
            'projects' => Project::count(),
            'skills' => Skill::count(),
            'experiences' => Experience::count(),
            'testimonials' => \App\Models\Testimonial::count(),
            'articles' => \App\Models\Article::count(),
            'contact_messages' => \App\Models\ContactMessage::count(),
            'unread_messages' => \App\Models\ContactMessage::whereNull('read_at')->count(),
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'throttle:60,1'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::resource('skills', SkillController::class);
    Route::resource('experiences', ExperienceController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('articles', AdminArticleController::class);
    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
});

require __DIR__.'/settings.php';
