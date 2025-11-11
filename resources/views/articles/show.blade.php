<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('status') === 'comment-pending')
                <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                    Ваш комментарий отправлен на модерацию
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8">
                    <div class="mb-4">
                        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                            ← Назад к статьям
                        </a>
                    </div>

                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>

                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 pb-6 border-b">
                        <span class="bg-gray-100 px-3 py-1 rounded">{{ $article->category->name }}</span>
                        <span>👁 {{ $article->views_count }} просмотров</span>
                        <span>💬 {{ $article->comments->count() }} комментариев</span>
                        <span>📅 {{ $article->created_at->format('d.m.Y H:i') }}</span>
                        <span>✍️ {{ $article->author->name }}</span>
                    </div>

                    @can('update', $article)
                        <div class="mb-6 flex gap-2">
                            <a href="{{ route('articles.edit', $article) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                ✏️ Редактировать
                            </a>
                            @can('delete', $article)
                                <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту статью?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                        🗑️ Удалить
                                    </button>
                                </form>
                            @endcan
                        </div>
                    @endcan

                    <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
                        {!! nl2br(e($article->body)) !!}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Комментарии ({{ $article->comments->count() }})
                    </h2>

                    @can('create-comment')
                        <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">Оставить комментарий</h3>
                            <form action="{{ route('comments.store', $article) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <textarea
                                        name="body"
                                        rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('body') border-red-500 @enderror"
                                        placeholder="Напишите ваш комментарий...">{{ old('body') }}</textarea>
                                    @error('body')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    💬 Отправить комментарий
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200 text-center">
                            <p class="text-gray-600">
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Войдите</a>
                                или
                                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">зарегистрируйтесь</a>
                                , чтобы оставить комментарий
                            </p>
                        </div>
                    @endcan

                    <div class="space-y-4">
                        @forelse($article->comments as $comment)
                            <div class="p-6 border border-gray-200 rounded-lg bg-white">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $comment->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <p class="text-gray-800">{{ $comment->body }}</p>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                Комментариев пока нет. Будьте первым!
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
