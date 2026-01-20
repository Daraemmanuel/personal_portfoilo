<?php

use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Models\Article;

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

