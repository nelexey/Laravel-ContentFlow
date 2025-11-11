<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold">Все статьи</h1>
                        <div class="flex gap-2">
                            @can('create', App\Models\Article::class)
                                <a href="{{ route('articles.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    + Создать статью
                                </a>
                            @endcan
                            @can('moderate-comments')
                                <a href="{{ route('admin.comments.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    🛡️ Модерация
                                </a>
                            @endcan
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse($articles as $article)
                        <div class="mb-6 p-6 border border-gray-200 rounded-lg hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <a href="{{ route('articles.show', $article) }}" class="text-2xl font-bold text-gray-900 hover:text-gray-600">
                                        {{ $article->title }}
                                    </a>
                                    <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                                        <span class="bg-gray-100 px-3 py-1 rounded">{{ $article->category->name }}</span>
                                        <span>👁 {{ $article->views_count }} просмотров</span>
                                        <span>💬 {{ $article->comments->count() }} комментариев</span>
                                        <span>📅 {{ $article->created_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                    <p class="mt-3 text-gray-700">{{ Str::limit($article->body, 200) }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('articles.show', $article) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Читать далее →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">Статей пока нет</p>
                            @can('create', App\Models\Article::class)
                                <a href="{{ route('articles.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Создать первую статью
                                </a>
                            @endcan
                        </div>
                    @endforelse

                    @if($articles->hasPages())
                        <div class="mt-6">
                            {{ $articles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>