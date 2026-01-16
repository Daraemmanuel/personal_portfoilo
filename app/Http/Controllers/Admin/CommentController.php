<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends Controller
{
    public function index()
    {
        $comments = ArticleComment::with(['article', 'parent'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Admin/Comments/Index', [
            'comments' => $comments,
        ]);
    }

    public function approve(ArticleComment $comment)
    {
        $comment->update(['is_approved' => true]);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment approved successfully.');
    }

    public function reject(ArticleComment $comment)
    {
        $comment->update(['is_approved' => false]);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment rejected.');
    }

    public function destroy(ArticleComment $comment)
    {
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment deleted successfully.');
    }
}

