<?php

namespace App\Http\Controllers;

use App\Models\ArticleComment;
use App\Models\CommentReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class CommentReactionController extends Controller
{
    public function store(Request $request, $comment)
    {
        // Find comment by ID if route model binding doesn't work
        $comment = ArticleComment::findOrFail($comment);
        
        $validated = $request->validate([
            'reaction_type' => 'required|in:like,helpful',
        ]);

        $key = 'reaction:' . $request->ip() . ':' . $comment->id;
        
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Too many reactions. Please try again in {$seconds} seconds.",
                'error' => 'rate_limit_exceeded',
            ], 429);
        }

        RateLimiter::hit($key, 3600);

        // Check if user already reacted with this type
        $existingReaction = CommentReaction::where('comment_id', $comment->id)
            ->where('reaction_type', $validated['reaction_type'])
            ->where('ip_address', $request->ip())
            ->first();

        if ($existingReaction) {
            // Remove reaction (toggle off)
            $existingReaction->delete();
            $action = 'removed';
        } else {
            // Remove opposite reaction if exists
            $oppositeType = $validated['reaction_type'] === 'like' ? 'helpful' : 'like';
            CommentReaction::where('comment_id', $comment->id)
                ->where('reaction_type', $oppositeType)
                ->where('ip_address', $request->ip())
                ->delete();

            // Add new reaction
            CommentReaction::create([
                'comment_id' => $comment->id,
                'reaction_type' => $validated['reaction_type'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            $action = 'added';
        }

        // Refresh comment to get updated counts
        $comment->refresh();

        // Get updated counts
        $likesCount = CommentReaction::where('comment_id', $comment->id)
            ->where('reaction_type', 'like')
            ->count();
        $helpfulsCount = CommentReaction::where('comment_id', $comment->id)
            ->where('reaction_type', 'helpful')
            ->count();
        $userLiked = CommentReaction::where('comment_id', $comment->id)
            ->where('reaction_type', 'like')
            ->where('ip_address', $request->ip())
            ->exists();
        $userHelpful = CommentReaction::where('comment_id', $comment->id)
            ->where('reaction_type', 'helpful')
            ->where('ip_address', $request->ip())
            ->exists();

        return response()->json([
            'action' => $action,
            'likes_count' => $likesCount,
            'helpfuls_count' => $helpfulsCount,
            'user_liked' => $userLiked,
            'user_helpful' => $userHelpful,
        ]);
    }
}
