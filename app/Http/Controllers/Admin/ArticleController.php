<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Services\ArticleService;
use App\Models\Article;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $articles = Article::withCount([
            'comments' => fn($q) => $q->where('is_approved', false),
        ])
            ->orderBy('published_at', 'desc')
            ->latest()
            ->get();
        $scheduledArticles = Article::whereNotNull('published_at')
            ->where('published_at', '>', now())
            ->orderBy('published_at', 'asc')
            ->get();

        return Inertia::render('Admin/Articles/Index', [
            'articles' => $articles,
            'scheduledArticles' => $scheduledArticles,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Articles/Create');
    }

    public function store(StoreArticleRequest $request, ArticleService $service)
    {
        $validated = $request->validated();

        $data = [
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category' => $validated['category'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'is_featured' => $validated['is_featured'] ?? false,
            'series' => $validated['series'] ?? null,
            'series_order' => $validated['series_order'] ?? null,
        ];

        try {
            $image = $request->hasFile('featured_image') ? $request->file('featured_image') : null;
            $service->create($data, $image);
        } catch (\Exception $e) {
            \Log::error('Failed to create article', [
                'error' => $e->getMessage(),
                'article_title' => $validated['title'],
            ]);
            return back()->withErrors(['featured_image' => 'Failed to create article. Please try again.']);
        }

        return redirect()->route('admin.articles.index');
    }

    public function edit(Article $article)
    {
        return Inertia::render('Admin/Articles/Edit', [
            'article' => $article,
        ]);
    }

    public function update(UpdateArticleRequest $request, Article $article, ArticleService $service)
    {
        $validated = $request->validated();

        $data = [
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? null,
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category' => $validated['category'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'is_featured' => $validated['is_featured'] ?? false,
            'series' => $validated['series'] ?? null,
            'series_order' => $validated['series_order'] ?? null,
        ];

        try {
            $image = $request->hasFile('featured_image') ? $request->file('featured_image') : null;
            $service->update($article, $data, $image);
        } catch (\Exception $e) {
            \Log::error('Failed to update article', [
                'error' => $e->getMessage(),
                'article_id' => $article->id,
            ]);
            return back()->withErrors(['featured_image' => 'Failed to update article. Please try again.']);
        }

        // If this is an auto-save (Inertia partial request with only parameter), stay on edit page
        if ($request->header('X-Inertia') && $request->header('X-Inertia-Partial-Data')) {
            return back();
        }

        return redirect()->route('admin.articles.index');
    }

    public function destroy(Article $article, ArticleService $service)
    {
        try {
            $service->delete($article);
        } catch (\Exception $e) {
            \Log::error('Failed to delete article', [
                'error' => $e->getMessage(),
                'article_id' => $article->id,
            ]);
            return back()->withErrors(['error' => 'Failed to delete article. Please try again.']);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }

    public function bulkDelete(Request $request, ArticleService $service)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:articles,id',
        ]);

        $articles = Article::whereIn('id', $request->ids)->get();

        try {
            foreach ($articles as $article) {
                $service->delete($article);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to bulk delete articles', [
                'error' => $e->getMessage(),
                'article_ids' => $request->ids,
            ]);
            return back()->withErrors(['error' => 'Failed to delete articles. Please try again.']);
        }

        return redirect()->route('admin.articles.index')
            ->with('success', count($request->ids) . ' article(s) deleted successfully.');
    }

    public function export()
    {
        $articles = Article::all();

        $csv = "ID,Title,Slug,Category,Published At,Views,Created At\n";
        foreach ($articles as $article) {
            $csv .= sprintf(
                "%d,%s,%s,%s,%s,%d,%s\n",
                $article->id,
                '"' . str_replace('"', '""', $article->title) . '"',
                $article->slug,
                $article->category ?? '',
                $article->published_at?->toDateString() ?? '',
                $article->views,
                $article->created_at->toDateString()
            );
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="articles-' . date('Y-m-d') . '.csv"');
    }
}
