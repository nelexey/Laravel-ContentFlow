<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(): JsonResponse
    {
        $articles = Article::with('author')
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'data' => ArticleResource::collection($articles),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
                'last_page' => $articles->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created article.
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        // Get or create a default category
        $category = Category::firstOrCreate(['name' => 'General']);
        
        $article = Article::create([
            'title' => $request->title,
            'body' => $request->content,
            'author_id' => $request->user()->id,
            'category_id' => $category->id,
        ]);
        
        return response()->json(new ArticleResource($article), 201);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): JsonResponse
    {
        $article->load(['author', 'comments' => function ($query) {
            $query->where('is_approved', true)->with('user');
        }]);
        
        return response()->json(new ArticleResource($article));
    }

    /**
     * Update the specified article.
     */
    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $this->authorize('update', $article);
        
        // Prepare data for update
        $data = [];
        if ($request->has('title')) {
            $data['title'] = $request->title;
        }
        if ($request->has('content')) {
            $data['body'] = $request->content;
        }
        if ($request->has('category_id')) {
            $data['category_id'] = $request->category_id;
        }
        
        $article->update($data);
        
        return response()->json(new ArticleResource($article));
    }

    /**
     * Remove the specified article.
     */
    public function destroy(Article $article): JsonResponse
    {
        $this->authorize('delete', $article);
        
        $article->delete();
        
        return response()->json(null, 204);
    }
}