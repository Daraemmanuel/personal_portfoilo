<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()
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

        // Get related articles (only published)
        $relatedArticles = Article::related($article, 3)->get();

        // Get popular articles (only published)
        $popularArticles = Article::popular(5)->get();

        // Get series articles if this article is part of a series
        $seriesArticles = $article->series ? Article::published()
            ->inSeries($article->series)
            ->orderBy('series_order')
            ->get() : collect();

        // Get approved comments with replies
        $comments = $article->approvedComments()
            ->with('replies')
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
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return Inertia::render('Articles/Tag', [
            'tag' => $tag,
            'articles' => $articles,
        ]);
    }

    public function categories()
    {
        $categories = Article::published()
            ->select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->map(function ($category) {
                return [
                    'name' => $category,
                    'count' => Article::published()->byCategory($category)->count(),
                ];
            })
            ->sortByDesc('count')
            ->values();

        return Inertia::render('Articles/Categories', [
            'categories' => $categories,
        ]);
    }

    public function tags()
    {
        $allTags = Article::published()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->map(function ($tag) {
                return [
                    'name' => $tag,
                    'count' => Article::published()->byTag($tag)->count(),
                ];
            })
            ->sortByDesc('count')
            ->values();

        return Inertia::render('Articles/Tags', [
            'tags' => $allTags,
        ]);
    }
}
