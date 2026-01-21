<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SkillResource;
use App\Models\Article;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function projects()
    {
        $projects = Project::where('is_archived', false)
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return ProjectResource::collection($projects);
    }

    public function articles(Request $request)
    {
        $articles = Article::published()
            ->orderBy('published_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return ArticleResource::collection($articles);
    }

    public function skills()
    {
        $skills = Skill::orderBy('sort_order')->get();

        return SkillResource::collection($skills);
    }

    public function experiences()
    {
        $experiences = Experience::orderBy('sort_order')->latest()->get();

        return ExperienceResource::collection($experiences);
    }

    public function article(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->published()
            ->firstOrFail();

        return new ArticleResource($article);
    }
}
