<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Services\ProjectService;
use App\Models\Project;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view projects', only: ['index', 'show']),
            new Middleware('permission:create projects', only: ['create', 'store']),
            new Middleware('permission:edit projects', only: ['edit', 'update']),
            new Middleware('permission:delete projects', only: ['destroy']),
        ];
    }
    public function index()
    {
        return Inertia::render('Admin/Projects/Index', [
            'projects' => Project::orderBy('sort_order', 'asc')
                ->latest()
                ->get(),
        ]);
    }

    public function show(Project $project)
    {
        return Inertia::render('Projects/Show', [
            'project' => $project,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Projects/Create');
    }

    public function store(StoreProjectRequest $request, ProjectService $service)
    {
        $validated = $request->validated();

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'link' => $validated['link'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_archived' => $validated['is_archived'] ?? false,
        ];

        try {
            $image = $request->hasFile('image') ? $request->file('image') : null;
            $service->create($data, $image);
        } catch (\Exception $e) {
            Log::error('Failed to create project', [
                'error' => $e->getMessage(),
                'project_title' => $validated['title'],
            ]);
            return back()->withErrors(['image' => 'Failed to create project. Please try again.']);
        }

        return redirect()->route('admin.projects.index');
    }

    public function edit(Project $project)
    {
        return Inertia::render('Admin/Projects/Edit', [
            'project' => $project,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project, ProjectService $service)
    {
        $validated = $request->validated();

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'link' => $validated['link'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_archived' => $validated['is_archived'] ?? false,
        ];

        try {
            $image = $request->hasFile('image') ? $request->file('image') : null;
            $service->update($project, $data, $image);
        } catch (\Exception $e) {
            Log::error('Failed to update project', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
            ]);
            return back()->withErrors(['image' => 'Failed to update project. Please try again.']);
        }

        // If this is an auto-save (Inertia partial request with only parameter), stay on edit page
        if ($request->header('X-Inertia') && $request->header('X-Inertia-Partial-Data')) {
            return back();
        }

        return redirect()->route('admin.projects.index');
    }

    public function destroy(Project $project, ProjectService $service)
    {
        try {
            $service->delete($project);
        } catch (\Exception $e) {
            Log::error('Failed to delete project', [
                'error' => $e->getMessage(),
                'project_id' => $project->id,
            ]);
            return back()->withErrors(['error' => 'Failed to delete project. Please try again.']);
        }

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
