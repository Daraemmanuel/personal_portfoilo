<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()
            ->withCount([
                'comments' => fn($q) => $q->where('is_approved', true),
            ])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return Inertia::render('Articles/Index', [
            'articles' => $articles,
        ]);
    }

    public function show(string $slug)
    {
        // Allow viewing unpublished articles if user is authenticated (for preview)
        $query = Article::where('slug', $slug);
        
        if (!auth()->check()) {
            $query->published();
        }
        
        $article = $query->firstOrFail();

        // Increment views only for published articles
        if ($article->published_at && $article->published_at <= now()) {
            $article->incrementViews();
        }

        // Get related articles (only published) - optimized single query with eager loading
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($article) {
                if ($article->category) {
                    $q->where('category', $article->category);
                }
                if ($article->tags && is_array($article->tags) && count($article->tags) > 0) {
                    $q->orWhereJsonContains('tags', $article->tags[0]);
                }
            })
            ->withCount([
                'comments' => fn($q) => $q->where('is_approved', true),
            ])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Get popular articles (only published) - cached for 1 hour with tags
        $popularArticles = $this->getCachedWithTags(
            'articles.popular',
            ['articles', 'homepage'],
            now()->addHour(),
            fn() => Article::published()
                ->withCount([
                    'comments' => fn($q) => $q->where('is_approved', true),
                ])
                ->orderBy('views', 'desc')
                ->limit(5)
                ->get()
        );

        // Get series articles if this article is part of a series - optimized with eager loading
        $seriesArticles = $article->series ? Article::published()
            ->where('series', $article->series)
            ->where('id', '!=', $article->id)
            ->withCount([
                'comments' => fn($q) => $q->where('is_approved', true),
            ])
            ->orderBy('series_order')
            ->get() : collect();

        // Get all comments (approved and pending) with replies and reactions - optimized with eager loading
        $comments = $article->comments()
            ->with([
                'replies' => function ($query) {
                    $query->orderBy('created_at', 'asc')
                        ->withCount([
                            'likes as likes_count',
                            'helpfuls as helpfuls_count',
                        ]);
                },
            ])
            ->withCount([
                'likes as likes_count',
                'helpfuls as helpfuls_count',
            ])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Generate breadcrumbs
        $breadcrumbs = [
            ['label' => 'Articles', 'href' => route('articles.index')],
            ['label' => $article->title],
        ];

        // Generate meta description
        $metaDescription = $article->excerpt ?: substr(strip_tags($article->content), 0, 160);

        return Inertia::render('Articles/Show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'popularArticles' => $popularArticles,
            'seriesArticles' => $seriesArticles,
            'comments' => $comments,
            'breadcrumbs' => $breadcrumbs,
            'metaDescription' => $metaDescription,
            'canonicalUrl' => route('articles.show', $article->slug),
        ]);
    }

    public function category(string $category)
    {
        $articles = Article::published()
            ->byCategory($category)
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return Inertia::render('Articles/Category', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }

    public function tag(string $tag)
    {
        $articles = Article::published()
            ->byTag($tag)
            ->withCount([
                'comments' => fn($q) => $q->where('is_approved', true),
            ])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return Inertia::render('Articles/Tag', [
            'tag' => $tag,
            'articles' => $articles,
        ]);
    }

    public function categories()
    {
        // Optimized: Single query with groupBy, cached for 24 hours with tags
        $categories = $this->getCachedWithTags(
            'articles.categories',
            ['articles'],
            now()->addHours(24),
            function () {
                return Article::published()
                    ->select('category', DB::raw('count(*) as count'))
                    ->whereNotNull('category')
                    ->groupBy('category')
                    ->orderByDesc('count')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'name' => $item->category,
                            'count' => $item->count,
                        ];
                    })
                    ->values();
            }
        );

        return Inertia::render('Articles/Categories', [
            'categories' => $categories,
        ]);
    }

    public function tags()
    {
        // Optimized: Get all tags and count in single query, cached for 24 hours with tags
        // Note: For JSON columns, we need to extract and count in PHP, but we can optimize the counting
        $tags = $this->getCachedWithTags(
            'articles.tags',
            ['articles'],
            now()->addHours(24),
            function () {
                $articles = Article::published()
                    ->whereNotNull('tags')
                    ->select('tags')
                    ->get();

                // Extract all unique tags
                $allTags = $articles->pluck('tags')
                    ->flatten()
                    ->unique()
                    ->values();

                // Count occurrences efficiently using collection methods
                $tagCounts = $articles->pluck('tags')
                    ->flatten()
                    ->countBy();

                // Map to final format
                return $allTags->map(function ($tag) use ($tagCounts) {
                    return [
                        'name' => $tag,
                        'count' => $tagCounts->get($tag, 0),
                    ];
                })
                    ->sortByDesc('count')
                    ->values();
            }
        );

        return Inertia::render('Articles/Tags', [
            'tags' => $tags,
        ]);
    }

    /**
     * Get cached value with tags support, falling back to regular cache if tags not supported
     */
    private function getCachedWithTags(string $key, array $tags, $ttl, callable $callback)
    {
        $cache = \Illuminate\Support\Facades\Cache::getStore();
        
        if ($cache instanceof \Illuminate\Cache\TaggedCache || method_exists($cache, 'tags')) {
            return \Illuminate\Support\Facades\Cache::tags($tags)->remember($key, $ttl, $callback);
        }
        
        // Fallback for cache drivers that don't support tags
        return \Illuminate\Support\Facades\Cache::remember($key, $ttl, $callback);
    }
}
