<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ModerationController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:approve-comment');
    }

    public function index()
    {
        $pendingComments = Comment::where('is_approved', false)
            ->with('user', 'article')
            ->latest()
            ->paginate(10);

        return view('admin.comments.index', compact('pendingComments'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true]);
        Cache::forget("article.{$comment->article_id}.with.comments");
        return back()->with('success', 'Комментарий одобрен.');
    }

    public function reject(Comment $comment)
    {
        $articleId = $comment->article_id;
        $comment->delete();
        Cache::forget("article.{$articleId}.with.comments");
        return back()->with('success', 'Комментарий отклонен.');
    }
}