<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all'); // all, articles, projects

        if (empty($query)) {
            return Inertia::render('Search/Results', [
                'query' => $query,
                'type' => $type,
                'articles' => [],
                'projects' => [],
                'total' => 0,
            ]);
        }

        $results = [
            'articles' => [],
            'projects' => [],
            'total' => 0,
        ];

        // Sanitize query - Laravel's query builder handles SQL injection, but escape LIKE wildcards
        $searchQuery = trim($query);
        $searchQuery = str_replace(['%', '_'], ['\%', '\_'], $searchQuery);

        if ($type === 'all' || $type === 'articles') {
            // Optimized search: prioritize indexed columns (title, category) first
            // This allows database to use indexes more efficiently
            $articles = Article::published()
                ->where(function ($q) use ($searchQuery) {
                    // Indexed columns first (faster)
                    $q->where('title', 'like', "%{$searchQuery}%")
                        ->orWhere('category', 'like', "%{$searchQuery}%")
                        // JSON search for tags
                        ->orWhereJsonContains('tags', $searchQuery)
                        // Non-indexed columns last (slower but necessary)
                        ->orWhere('excerpt', 'like', "%{$searchQuery}%")
                        ->orWhere('content', 'like', "%{$searchQuery}%");
                })
                ->orderBy('published_at', 'desc')
                ->limit(20)
                ->get();

            // Sort results in PHP to prioritize title matches (better UX)
            $articles = $articles->sortBy(function ($article) use ($searchQuery) {
                $titleMatch = stripos($article->title, $searchQuery) !== false ? 1 : 4;
                $categoryMatch = stripos($article->category ?? '', $searchQuery) !== false ? 2 : 5;
                $excerptMatch = stripos($article->excerpt, $searchQuery) !== false ? 3 : 6;
                return min($titleMatch, $categoryMatch, $excerptMatch);
            })->values();

            $results['articles'] = $articles;
            $results['total'] += $articles->count();
        }

        if ($type === 'all' || $type === 'projects') {
            // Optimized search: prioritize indexed title column
            $projects = Project::where('is_archived', false)
                ->where(function ($q) use ($searchQuery) {
                    // Indexed title first (faster)
                    $q->where('title', 'like', "%{$searchQuery}%")
                        // JSON search for tags
                        ->orWhereJsonContains('tags', $searchQuery)
                        // Non-indexed description last (slower)
                        ->orWhere('description', 'like', "%{$searchQuery}%");
                })
                ->orderBy('sort_order')
                ->latest()
                ->limit(20)
                ->get();

            // Sort results in PHP to prioritize title matches
            $projects = $projects->sortBy(function ($project) use ($searchQuery) {
                return stripos($project->title, $searchQuery) !== false ? 1 : 2;
            })->values();

            $results['projects'] = $projects;
            $results['total'] += $projects->count();
        }

        return Inertia::render('Search/Results', [
            'query' => $query,
            'type' => $type,
            'articles' => $results['articles'],
            'projects' => $results['projects'],
            'total' => $results['total'],
        ]);
    }
}

