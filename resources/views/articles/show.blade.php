<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $article->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="text-muted mb-2">
                        Категория: {{ $article->category->name }} |
                        Опубликовано: {{ $article->created_at->format('d.m.Y') }}
                    </div>

                    <div class="mb-3">
                        @can('update', $article)
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning">
                                Редактировать
                            </a>
                        @endcan

                        @can('delete', $article)
                            <form action="{{ route('articles.destroy', $article) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Уверены?')">
                                    Удалить
                                </button>
                            </form>
                        @endcan
                    </div>

                    <div class="article-body">
                        {!! nl2br(e($article->body)) !!}
                    </div>

                    <hr>

                    <h3>Комментарии ({{ $article->comments->count() }})</h3>

                    @can('create-comment')
                        <div class="card mb-3">
                            <div class="card-body">
                                <form action="{{ route('comments.store', $article) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="body" class="form-label">Ваш комментарий</label>
                                        <textarea name="body" id="body" rows="3"
                                            class="form-control @error('body') is-invalid @enderror">{{ old('body') }}</textarea>
                                        @error('body')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Отправить</button>
                                </form>
                            </div>
                        </div>

                        @if (session('status') === 'comment-pending')
                            <div class="alert alert-success">
                                Ваш комментарий отправлен на модерацию.
                            </div>
                        @endif
                    @elseguest
                        <p>Войдите, чтобы комментировать.</p>
                    @endcan

                    @forelse ($article->comments as $comment)
                        <div class="card mb-2">
                            <div class="card-body">
                                <h6 class="card-title">{{ $comment->user->name }}</h6>
                                <p class="card-text">{{ $comment->body }}</p>

                                <div class="text-muted" style="font-size: 0.8rem;">
                                    {{ $comment->created_at->diffForHumans() }}
                                </div>

                                <div class="comment-actions mt-2">
                                    @can('update-comment', $comment)
                                        <a href="{{ route('comments.edit', $comment) }}" class="btn btn-sm btn-outline-primary">
                                            Редактировать
                                        </a>
                                    @endcan

                                    @can('delete-comment', $comment)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Удалить комментарий?')">
                                                Удалить
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Комментариев пока нет</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>