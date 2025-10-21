<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        Gate::authorize('create-comment');

        $validated = $request->validate([
            'body' => 'required|string|min:5',
        ]);

        $article->comments()->create([
            'body'        => $validated['body'],
            'user_id'     => Auth::id(),
            'is_approved' => false,
        ]);

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'comment-pending');
    }
}
