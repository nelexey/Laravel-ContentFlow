@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <h1>{{ $article->title }}</h1>
        <div class="text-muted mb-2">
            Категория: {{ $article->category->name }} | 
            Опубликовано: {{ $article->created_at->format('d.m.Y') }}
        </div>
        
        <div class="mb-3">
            <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning">Редактировать</a>
            <form action="{{ route('articles.destroy', $article) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Уверены?')">Удалить</button>
            </form>
        </div>
        
        <div class="article-body">
            {!! nl2br(e($article->body)) !!} </div>
        
        <hr>

        <h3>Комментарии ({{ $article->comments->count() }})</h3>
        
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('comments.store', $article) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="body" class="form-label">Ваш комментарий</label>
                        <textarea name="body" id="body" rows="3" class="form-control @error('body') is-invalid @enderror">{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>

        @forelse($article->comments as $comment)
            <div class="card mb-2">
                <div class="card-body">
                    <h6 class="card-title">{{ $comment->user->name }}</h6> <p class="card-text">{{ $comment->body }}</p>
                    <div class="text-muted" style="font-size: 0.8rem;">
                        {{ $comment->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        @empty
            <p>Комментариев пока нет. Будьте первым!</p>
        @endforelse

    </div>
@endsection