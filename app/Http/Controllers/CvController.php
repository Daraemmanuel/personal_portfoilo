<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CvController extends Controller
{
    public function download()
    {
        $cv = Cv::where('is_active', true)->latest()->first();

        if (!$cv || !Storage::disk('public')->exists($cv->file_path)) {
            abort(404, 'CV not found');
        }

        $filePath = Storage::disk('public')->path($cv->file_path);
        $fileName = $cv->file_name ?: 'cv.pdf';

        return response()->download($filePath, $fileName, [
            'Content-Type' => $cv->mime_type,
        ]);
    }
}

