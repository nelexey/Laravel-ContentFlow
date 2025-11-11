<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">Модерация комментариев</h1>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse($pendingComments as $comment)
                        <div class="mb-6 p-6 border border-gray-300 rounded-lg bg-gray-50">
                            <div class="mb-4">
                                <div class="flex items-center gap-4 mb-2">
                                    <span class="font-bold text-lg text-gray-900">👤 {{ $comment->user->name }}</span>
                                    <span class="text-sm text-gray-500">📧 {{ $comment->user->email }}</span>
                                    <span class="text-sm text-gray-500">🕒 {{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    К статье:
                                    <a href="{{ route('articles.show', $comment->article) }}"
                                       class="text-blue-600 hover:text-blue-800 font-semibold"
                                       target="_blank">
                                        "{{ $comment->article->title }}"
                                    </a>
                                </div>
                            </div>

                            <div class="mb-4 p-4 bg-white rounded border border-gray-200">
                                <p class="text-gray-800">{{ $comment->body }}</p>
                            </div>

                            <div class="flex gap-3">
                                <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700">
                                        ✅ Одобрить комментарий
                                    </button>
                                </form>

                                <form action="{{ route('admin.comments.reject', $comment) }}" method="POST"
                                      onsubmit="return confirm('Вы уверены, что хотите отклонить этот комментарий?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700">
                                        ❌ Отклонить комментарий
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">✅</div>
                            <p class="text-xl text-gray-600">Все комментарии проверены!</p>
                            <p class="text-gray-500 mt-2">Нет комментариев, ожидающих модерации</p>
                        </div>
                    @endforelse

                    @if($pendingComments->hasPages())
                        <div class="mt-6">
                            {{ $pendingComments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>