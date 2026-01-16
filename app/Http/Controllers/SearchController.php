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

        if ($type === 'all' || $type === 'articles') {
            $articles = Article::published()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%")
                        ->orWhere('category', 'like', "%{$query}%")
                        ->orWhereJsonContains('tags', $query);
                })
                ->orderBy('published_at', 'desc')
                ->limit(20)
                ->get();

            $results['articles'] = $articles;
            $results['total'] += $articles->count();
        }

        if ($type === 'all' || $type === 'projects') {
            $projects = Project::where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhereJsonContains('tags', $query);
                })
                ->orderBy('sort_order')
                ->latest()
                ->limit(20)
                ->get();

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

