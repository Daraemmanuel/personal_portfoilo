<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Project;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function archive()
    {
        return Inertia::render('Archive', [
            'projects' => Project::where('is_archived', true)
                ->orderBy('sort_order')
                ->latest()
                ->get(),
            'canLogin' => Route::has('login'),
        ]);
    }

    public function show(Project $project)
    {
        return Inertia::render('Projects/Show', [
            'project' => $project,
        ]);
    }
}
