<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Traits\ClearsHomepageCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TestimonialController extends Controller implements HasMiddleware
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
        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => Testimonial::orderBy('sort_order')->latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Testimonials/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'content' => 'required|string',
            'avatar' => 'nullable|image|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'name' => $validated['name'],
            'role' => $validated['role'],
            'company' => $validated['company'],
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('avatar')) {
            try {
                $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
            } catch (\Exception $e) {
            Log::error('Failed to upload testimonial avatar', [
                'error' => $e->getMessage(),
                'testimonial_name' => $validated['name'],
            ]);
                return back()->withErrors(['avatar' => 'Failed to upload image. Please try again.']);
            }
        }

        Testimonial::create($data);

        $this->clearCache('testimonials');

        return redirect()->route('admin.testimonials.index');
    }

    public function edit(Testimonial $testimonial)
    {
        return Inertia::render('Admin/Testimonials/Edit', [
            'testimonial' => $testimonial,
        ]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'content' => 'required|string',
            'avatar' => 'nullable|image|max:2048',
            'rating' => 'nullable|integer|min:1|max:5',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'name' => $validated['name'],
            'role' => $validated['role'],
            'company' => $validated['company'],
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('avatar')) {
            try {
                if ($testimonial->avatar) {
                    Storage::disk('public')->delete($testimonial->avatar);
                }
                $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
            } catch (\Exception $e) {
            Log::error('Failed to upload testimonial avatar', [
                'error' => $e->getMessage(),
                'testimonial_id' => $testimonial->id,
            ]);
                return back()->withErrors(['avatar' => 'Failed to upload image. Please try again.']);
            }
        }

        $testimonial->update($data);

        $this->clearCache('testimonials');

        // If this is an auto-save (Inertia partial request with only parameter), stay on edit page
        if ($request->header('X-Inertia') && $request->header('X-Inertia-Partial-Data')) {
            return back();
        }

        return redirect()->route('admin.testimonials.index');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
        }
        $testimonial->delete();

        $this->clearCache('testimonials');

        return redirect()->route('admin.testimonials.index');
    }
}
