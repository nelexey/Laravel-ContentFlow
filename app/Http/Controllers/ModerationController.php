<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ModerationController extends BaseController
{
    public function __construct()
    {
        $this->middleware('can:create,App\Models\Article');
    }

    public function index()
    {
        $pendingComments = Comment::where('is_approved', false)
            ->with('user', 'article')
            ->latest()
            ->get();

        return view('admin.comments.index', compact('pendingComments'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true]);
        return back()->with('success', 'Комментарий одобрен.');
    }

    public function reject(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Комментарий отклонен.');
    }
}
