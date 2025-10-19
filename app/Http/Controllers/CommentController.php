<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:5',
        ]);
        
        $article->comments()->create([
            'body' => $validated['body'],
            'user_id' => 1 // TODO
        ]);

        return redirect()->route('articles.show', $article)->with('success', 'Комментарий отправлен');
    }
}