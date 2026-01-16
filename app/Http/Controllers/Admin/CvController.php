<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CvController extends Controller
{
    public function index()
    {
        $cv = Cv::where('is_active', true)->latest()->first();

        return Inertia::render('Admin/Cv/Index', [
            'cv' => $cv ? [
                'id' => $cv->id,
                'file_name' => $cv->file_name,
                'file_size' => $cv->file_size,
                'file_size_human' => $cv->file_size_human,
                'created_at' => $cv->created_at->toISOString(),
                'file_url' => $cv->file_url,
            ] : null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cv_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        // Deactivate all existing CVs
        Cv::where('is_active', true)->update(['is_active' => false]);

        // Store the new CV
        $file = $request->file('cv_file');
        $path = $file->store('cvs', 'public');

        $cv = Cv::create([
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.cv.index')
            ->with('success', 'CV uploaded successfully.');
    }

    public function destroy(Cv $cv)
    {
        if ($cv->file_path) {
            Storage::disk('public')->delete($cv->file_path);
        }
        $cv->delete();

        return redirect()->route('admin.cv.index')
            ->with('success', 'CV deleted successfully.');
    }
}
