<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Upload an image for use in rich text editors
     */
    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:5120', // Max 5MB
        ]);

        try {
            $file = $request->file('image');
            $path = $file->store('projects', 'public');

            // Return the public URL
            return response()->json([
                'url' => asset('storage/' . $path),
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to upload media image', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Failed to upload image. Please try again.',
                'error' => 'upload_failed',
            ], 500);
        }
    }
}

