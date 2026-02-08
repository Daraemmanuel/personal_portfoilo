<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Traits\ClearsHomepageCache;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SkillController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin'),
        ];
    }
    use ClearsHomepageCache;
    public function index()
    {
        return Inertia::render('Admin/Skills/Index', [
            'skills' => Skill::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Skills/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*' => 'string',
            'sort_order' => 'nullable|integer',
        ]);

        Skill::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'items' => $validated['items'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->clearCache('skills');

        return redirect()->route('admin.skills.index');
    }

    public function edit(Skill $skill)
    {
        return Inertia::render('Admin/Skills/Edit', [
            'skill' => $skill,
        ]);
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*' => 'string',
            'sort_order' => 'nullable|integer',
        ]);

        $skill->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'items' => $validated['items'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->clearCache('skills');

        // If this is an auto-save (Inertia partial request with only parameter), stay on edit page
        if ($request->header('X-Inertia') && $request->header('X-Inertia-Partial-Data')) {
            return back();
        }

        return redirect()->route('admin.skills.index');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        $this->clearCache('skills');

        return redirect()->route('admin.skills.index');
    }
}
