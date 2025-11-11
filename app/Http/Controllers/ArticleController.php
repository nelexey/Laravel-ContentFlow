<?php

namespace App\Http\Controllers;

use App\Events\ArticleCreated;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ArticleController extends BaseController
{
    public function index()
    {
        $page = request('page', 1);
        $cacheKey = "articles.index.page.{$page}";

        $articles = Cache::remember($cacheKey, 3600, function () {
            return Article::with('category')->latest()->paginate(10);
        });

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

        $validated['author_id'] = auth()->id();
        $article = Article::create($validated);

        Cache::tags(['articles'])->flush();

        ArticleCreated::dispatch($article->load('author'));

        return redirect()->route('home')->with('success', 'Статься опубликована');
    }

    public function show(Article $article)
    {
        $cacheKey = "article.{$article->id}.with.comments";

        $article = Cache::remember($cacheKey, 1800, function () use ($article) {
            $article->load([
                'category',
                'comments' => function ($query) {
                    $query->where('is_approved', true)->latest();
                },
                'comments.user',
            ]);
            return $article;
        });

        DB::table('article_views')->insert([
            'article_id' => $article->id,
            'ip_address' => request()->ip(),
            'viewed_at' => now(),
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

        Cache::tags(['articles'])->flush();
        Cache::forget("article.{$article->id}.with.comments");

        return redirect()->route('articles.show', $article)->with('success', 'Статья отредактирована');
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);
        $articleId = $article->id;
        $article->delete();

        Cache::tags(['articles'])->flush();
        Cache::forget("article.{$articleId}.with.comments");

        return redirect()->route('home')->with('success', 'Статья удалена');
    }
}
