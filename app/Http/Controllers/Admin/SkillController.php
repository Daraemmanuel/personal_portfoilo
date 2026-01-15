<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Traits\ClearsHomepageCache;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SkillController extends Controller
{
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

        Skill::create($validated);

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

        $skill->update($validated);

        $this->clearCache('skills');

        return redirect()->route('admin.skills.index');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        $this->clearCache('skills');

        return redirect()->route('admin.skills.index');
    }
}
