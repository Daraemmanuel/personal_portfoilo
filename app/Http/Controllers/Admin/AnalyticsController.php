<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Article views over time
        $articleViews = Article::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(views) as total_views')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Popular articles
        $popularArticles = Article::orderBy('views', 'desc')
            ->limit(10)
            ->get(['id', 'title', 'views', 'published_at']);

        // Recent activity
        $recentArticles = Article::latest()
            ->limit(5)
            ->get(['id', 'title', 'views', 'created_at']);

        // Stats
        $stats = [
            'total_articles' => Article::count(),
            'published_articles' => Article::published()->count(),
            'draft_articles' => Article::whereNull('published_at')->orWhere('published_at', '>', now())->count(),
            'total_views' => Article::sum('views'),
            'avg_views_per_article' => Article::published()->avg('views') ?? 0,
            'total_projects' => Project::count(),
            'contact_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::whereNull('read_at')->count(),
            'newsletter_subscribers' => NewsletterSubscriber::where('is_active', true)->count(),
            'total_comments' => \App\Models\ArticleComment::count(),
            'pending_comments' => \App\Models\ArticleComment::where('is_approved', false)->count(),
            'featured_articles' => Article::featured()->count(),
        ];

        // Views by category
        $viewsByCategory = Article::select('category', DB::raw('SUM(views) as total_views'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total_views')
            ->get();

        // Articles created this month
        $articlesThisMonth = Article::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Scheduled articles
        $scheduledArticles = Article::whereNotNull('published_at')
            ->where('published_at', '>', now())
            ->count();

        // Series statistics
        $seriesStats = Article::select('series', DB::raw('COUNT(*) as article_count'))
            ->whereNotNull('series')
            ->groupBy('series')
            ->orderByDesc('article_count')
            ->get();

        $totalSeries = $seriesStats->count();
        $totalArticlesInSeries = $seriesStats->sum('article_count');

        return Inertia::render('Admin/Analytics/Index', [
            'stats' => $stats,
            'articleViews' => $articleViews,
            'popularArticles' => $popularArticles,
            'recentArticles' => $recentArticles,
            'viewsByCategory' => $viewsByCategory,
            'articlesThisMonth' => $articlesThisMonth,
            'scheduledArticles' => $scheduledArticles,
            'seriesStats' => $seriesStats,
            'totalSeries' => $totalSeries,
            'totalArticlesInSeries' => $totalArticlesInSeries,
        ]);
    }
}



