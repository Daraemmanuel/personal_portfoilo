<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AnalyticsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin'),
        ];
    }
    public function dashboard()
    {
        $articleAnalytics = $this->getArticleAnalytics();
        
        return Inertia::render('Dashboard', [
            'stats' => $this->getDashboardStats(),
            'articleViews' => $articleAnalytics['articleViews'],
            'popularArticles' => $articleAnalytics['popularArticles'],
            'recentArticles' => $articleAnalytics['recentArticles'],
            'viewsByCategory' => $articleAnalytics['viewsByCategory'],
            'articlesThisMonth' => $articleAnalytics['articlesThisMonth'],
            'scheduledArticles' => $articleAnalytics['scheduledArticles'],
            'seriesStats' => $articleAnalytics['seriesStats'],
            'totalSeries' => $articleAnalytics['totalSeries'],
            'totalArticlesInSeries' => $articleAnalytics['totalArticlesInSeries'],
            'recentActivity' => $this->getRecentActivity(),
        ]);
    }

    /**
     * Get basic statistics for the dashboard.
     */
    private function getDashboardStats(): array
    {
        return [
            // Dashboard counts
            'projects' => Project::count(),
            'skills' => Skill::count(),
            'experiences' => Experience::count(),
            'testimonials' => Testimonial::count(),
            'articles' => Article::count(),
            'contact_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::whereNull('read_at')->count(),
            
            // Analytics stats
            'total_articles' => Article::count(),
            'published_articles' => Article::published()->count(),
            'draft_articles' => Article::whereNull('published_at')->orWhere('published_at', '>', now())->count(),
            'total_views' => Article::sum('views'),
            'avg_views_per_article' => Article::published()->avg('views') ?? 0,
            'total_projects' => Project::count(),
            'newsletter_subscribers' => NewsletterSubscriber::where('is_active', true)->count(),
            'total_comments' => \App\Models\ArticleComment::count(),
            'pending_comments' => \App\Models\ArticleComment::where('is_approved', false)->count(),
            'featured_articles' => Article::featured()->count(),
        ];
    }

    /**
     * Get detailed article analytics.
     */
    private function getArticleAnalytics(): array
    {
        $articleViews = Article::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(views) as total_views')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $seriesStats = Article::select('series', DB::raw('COUNT(*) as article_count'))
            ->whereNotNull('series')
            ->groupBy('series')
            ->orderByDesc('article_count')
            ->get();

        return [
            'articleViews' => $articleViews,
            'popularArticles' => Article::orderBy('views', 'desc')->limit(10)->get(['id', 'title', 'views', 'published_at']),
            'recentArticles' => Article::latest()->limit(5)->get(['id', 'title', 'views', 'created_at']),
            'viewsByCategory' => Article::select('category', DB::raw('SUM(views) as total_views'))
                ->whereNotNull('category')
                ->groupBy('category')
                ->orderByDesc('total_views')
                ->get(),
            'articlesThisMonth' => Article::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'scheduledArticles' => Article::whereNotNull('published_at')->where('published_at', '>', now())->count(),
            'seriesStats' => $seriesStats,
            'totalSeries' => $seriesStats->count(),
            'totalArticlesInSeries' => $seriesStats->sum('article_count'),
        ];
    }

    /**
     * Get the unified recent activity feed.
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getRecentActivity(): \Illuminate\Support\Collection
    {
        $activity = [];

        // 1. Articles
        foreach (Article::latest('created_at')->limit(3)->get(['id', 'title', 'created_at']) as $article) {
            $activity[] = [
                'type' => 'article',
                'title' => $article->title,
                'date' => $article->created_at,
                'url' => route('admin.articles.edit', $article->id),
                'author' => null,
                'is_unread' => false,
                'is_pending' => false,
            ];
        }

        // 2. Messages
        foreach (ContactMessage::latest('created_at')->limit(3)->get(['id', 'name', 'subject', 'created_at', 'read_at']) as $message) {
            $activity[] = [
                'type' => 'message',
                'title' => $message->subject ?: 'New message from ' . $message->name,
                'date' => $message->created_at,
                'url' => route('admin.contact-messages.show', $message->id),
                'author' => $message->name,
                'is_unread' => is_null($message->read_at),
                'is_pending' => false,
            ];
        }

        // 3. Comments
        $comments = \App\Models\ArticleComment::latest('created_at')
            ->with('article:id,title')
            ->limit(3)
            ->get(['id', 'author_name', 'content', 'article_id', 'created_at', 'is_approved']);

        foreach ($comments as $comment) {
            $activity[] = [
                'type' => 'comment',
                'title' => 'Comment on: ' . ($comment->article->title ?? 'Deleted Article'),
                'date' => $comment->created_at,
                'url' => route('admin.comments.index'),
                'author' => $comment->author_name,
                'is_unread' => false,
                'is_pending' => !$comment->is_approved,
            ];
        }

        return collect($activity)
            ->sortByDesc('date')
            ->take(10)
            ->values();
    }


    public function index()
    {
        // Detailed analytics with charts and trends
        // Article views over time (last 90 days for more detail)
        $articleViews = Article::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(views) as total_views'),
            DB::raw('COUNT(*) as article_count')
        )
            ->where('created_at', '>=', now()->subDays(90))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Views by month (for trend analysis)
        $viewsByMonth = Article::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(views) as total_views'),
            DB::raw('COUNT(*) as article_count')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Popular articles (top 20 for detailed view)
        $popularArticles = Article::orderBy('views', 'desc')
            ->limit(20)
            ->get(['id', 'title', 'views', 'published_at', 'category', 'created_at']);

        // Views by category (detailed)
        $viewsByCategory = Article::select('category', DB::raw('SUM(views) as total_views'), DB::raw('COUNT(*) as article_count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total_views')
            ->get();

        // Article performance metrics
        $articlePerformance = [
            'total_articles' => Article::count(),
            'published_articles' => Article::published()->count(),
            'draft_articles' => Article::whereNull('published_at')->orWhere('published_at', '>', now())->count(),
            'total_views' => Article::sum('views'),
            'avg_views_per_article' => Article::published()->avg('views') ?? 0,
            'max_views' => Article::max('views') ?? 0,
            'min_views' => Article::published()->min('views') ?? 0,
            'articles_this_month' => Article::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'articles_last_month' => Article::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            'views_this_month' => Article::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('views'),
            'views_last_month' => Article::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->sum('views'),
        ];

        // Series statistics
        $seriesStats = Article::select('series', DB::raw('COUNT(*) as article_count'), DB::raw('SUM(views) as total_views'))
            ->whereNotNull('series')
            ->groupBy('series')
            ->orderByDesc('total_views')
            ->get();

        // Top performing articles by engagement (views + comments)
        $topPerformingArticles = Article::withCount('comments')
            ->orderByRaw('(views + (comments_count * 10)) DESC')
            ->limit(10)
            ->get(['id', 'title', 'views', 'published_at', 'category']);

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

        return Inertia::render('Admin/Analytics/Index', [
            'stats' => $stats,
            'articleViews' => $articleViews,
            'viewsByMonth' => $viewsByMonth,
            'popularArticles' => $popularArticles,
            'viewsByCategory' => $viewsByCategory,
            'articlePerformance' => $articlePerformance,
            'seriesStats' => $seriesStats,
            'topPerformingArticles' => $topPerformingArticles,
        ]);
    }
}



