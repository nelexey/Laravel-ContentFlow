@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>ContentFlow</h1>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('articles.create') }}" class="btn btn-primary">Создать статью</a>
        </div>

        <div class="row">
            @forelse($articles as $article)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Категория: {{ $article->category->name }}
                            </h6>
                            <p class="card-text">
                                {{ Str::limit($article->body, 150) }}
                            </p>
                            <a href="{{ route('articles.show', $article) }}" class="card-link">Читать далее</a>
                        </div>
                    </div>
                </div>
            @empty
                <p>Новостей пока нет.</p>
            @endforelse
        </div>

        {{ $articles->links() }}
    </div>
@endsection