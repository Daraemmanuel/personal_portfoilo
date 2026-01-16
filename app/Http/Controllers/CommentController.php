<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $key = 'comment:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'content' => "Too many comments. Please try again in {$seconds} seconds.",
            ]);
        }

        RateLimiter::hit($key, 3600);

        // Honeypot
        if (!empty($request->input('website'))) {
            return response()->noContent();
        }

        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:article_comments,id',
        ]);

        $comment = $article->comments()->create([
            ...$validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_approved' => false, // Require moderation
        ]);

        // Return back to the article page - Inertia will handle the response
        return back();
    }
}

