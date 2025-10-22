<?php

namespace App\Http\Controllers;

use App\Mail\NewCommentNotification;
use App\Models\Article;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        Gate::authorize('create-comment');

        $validated = $request->validate([
            'body' => 'required|string|min:5',
        ]);

        $comment = $article->comments()->create([
            'body'        => $validated['body'],
            'user_id'     => Auth::id(),
            'is_approved' => false,
        ]);

        // Send email notification to moderators
        $moderators = Role::where('name', 'moderator')->first()->users ?? collect();
        foreach ($moderators as $moderator) {
            Mail::to($moderator->email)->send(new NewCommentNotification($comment));
        }

        return redirect()
            ->route('articles.show', $article)
            ->with('status', 'comment-pending');
    }
}