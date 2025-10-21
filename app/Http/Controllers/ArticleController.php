<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ArticleController extends BaseController
{
    public function index()
    {
        $articles = Article::with('category')->latest()->paginate(10);
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $this->authorize('create', Article::class);
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Article::class);
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
        ]);
        Article::create($validated);
        return redirect()->route('home')->with('success', 'Статься опубликована');
    }

    public function show(Article $article)
    {
        $article->load([
            'category',
            'comments' => function ($query) {
                $query->where('is_approved', true)->latest();
            },
            'comments.user',
        ]);
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $this->authorize('update', $article);
        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
        ]);
        $article->update($validated);
        return redirect()->route('articles.show', $article)->with('success', 'Статья отредактирована');
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();
        return redirect()->route('home')->with('success', 'Статья удалена');
    }
}
