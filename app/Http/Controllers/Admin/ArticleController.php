<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Traits\ClearsHomepageCache;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    use ClearsHomepageCache, LogsActivity;

    public function index()
    {
        $articles = Article::orderBy('published_at', 'desc')->latest()->get();
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'series' => 'nullable|string|max:255',
            'series_order' => 'nullable|integer|min:1',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        Article::create($validated);
        $this->clearCache('articles');

        return redirect()->route('admin.articles.index');
    }

    public function edit(Article $article)
    {
        return Inertia::render('Admin/Articles/Edit', [
            'article' => $article,
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $article->id,
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'series' => 'nullable|string|max:255',
            'series_order' => 'nullable|integer|min:1',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        } else {
            unset($validated['featured_image']);
        }

        $article->update($validated);
        $this->clearCache('articles');

        return redirect()->route('admin.articles.index');
    }

    public function destroy(Article $article)
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
        $article->delete();
        $this->clearCache('articles');

        return redirect()->route('admin.articles.index');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:articles,id',
        ]);

        $articles = Article::whereIn('id', $request->ids)->get();

        foreach ($articles as $article) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
        }

        Article::whereIn('id', $request->ids)->delete();
        $this->clearCache('articles');

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
