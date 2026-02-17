<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MediaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin'),
        ];
    }
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

            // Return the relative URL (Bulletproof for shared hosting)
            return response()->json([
                'url' => '/uploads/' . $path,
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to upload media image', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Failed to upload image. Please try again.',
                'error' => 'upload_failed',
            ], 500);
        }
    }
}

