<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(StoreCommentRequest $request, Article $article): JsonResponse
    {
        $comment = $article->comments()->create([
            'body' => $request->body,
            'user_id' => $request->user()->id,
            'is_approved' => false,
        ]);

        CommentCreated::dispatch($comment->load(['article', 'user']));

        return response()->json(new CommentResource($comment), 201);
    }

    /**
     * Approve the specified comment.
     */
    public function approve(Comment $comment): JsonResponse
    {
        // Check if user can approve comments (moderator or admin)
        if (!Gate::allows('approve-comment')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        
        $comment->update(['is_approved' => true]);
        
        return response()->json(new CommentResource($comment));
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        // Check if user can delete comment (author, moderator, or admin)
        if (!Gate::allows('delete-comment', $comment)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        
        $comment->delete();
        
        return response()->json(null, 204);
    }
}