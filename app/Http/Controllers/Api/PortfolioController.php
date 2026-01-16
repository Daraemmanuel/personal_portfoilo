<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Experience;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function projects()
    {
        return response()->json([
            'data' => Project::orderBy('sort_order')->latest()->get(),
        ]);
    }

    public function articles()
    {
        return response()->json([
            'data' => Article::published()
                ->orderBy('published_at', 'desc')
                ->paginate(20),
        ]);
    }

    public function skills()
    {
        return response()->json([
            'data' => Skill::orderBy('sort_order')->get(),
        ]);
    }

    public function experiences()
    {
        return response()->json([
            'data' => Experience::orderBy('sort_order')->latest()->get(),
        ]);
    }

    public function article(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->published()
            ->firstOrFail();

        return response()->json([
            'data' => $article,
        ]);
    }
}
