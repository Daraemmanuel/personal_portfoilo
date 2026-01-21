<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Traits\ClearsHomepageCache;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExperienceController extends Controller
{
    use ClearsHomepageCache;
    public function index()
    {
        return Inertia::render('Admin/Experiences/Index', [
            'experiences' => Experience::orderBy('sort_order')->latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Experiences/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'period' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        Experience::create([
            'role' => $validated['role'],
            'company' => $validated['company'],
            'period' => $validated['period'],
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->clearCache('experiences');

        return redirect()->route('admin.experiences.index');
    }

    public function edit(Experience $experience)
    {
        return Inertia::render('Admin/Experiences/Edit', [
            'experience' => $experience,
        ]);
    }

    public function update(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'role' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'period' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $experience->update([
            'role' => $validated['role'],
            'company' => $validated['company'],
            'period' => $validated['period'],
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->clearCache('experiences');

        // If this is an auto-save (Inertia partial request with only parameter), stay on edit page
        if ($request->header('X-Inertia') && $request->header('X-Inertia-Partial-Data')) {
            return back();
        }

        return redirect()->route('admin.experiences.index');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        $this->clearCache('experiences');

        return redirect()->route('admin.experiences.index');
    }
}
