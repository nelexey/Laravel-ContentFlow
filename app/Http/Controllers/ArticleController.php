<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with('category')->latest()->paginate(10);

        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
        ]);

        Article::create($validated);

        return redirect()->route('home')->with('success', 'Статься опубликована');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load('category', 'comments.user'); 
        
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:20',
            'category_id' => 'required|exists:categories,id',
        ]);

        $article->update($validated);

        return redirect()->route('articles.show', $article)->with('success', 'Статья отредактирована');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('home')->with('success', 'Статья удалена');
    }
}
