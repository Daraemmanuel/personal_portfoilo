<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Article $article)
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

        $validated = $request->validated();

        $comment = $article->comments()->create([
            'author_name' => $validated['author_name'],
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'author_email' => null, // Email no longer required
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_approved' => false, // Require moderation
        ]);

        // Return back to the article page - Inertia will handle the response
        return back();
    }
}

