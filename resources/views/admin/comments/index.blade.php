<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Модерация Комментариев') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse($pendingComments as $comment)
                        <div class="mb-4 p-4 border rounded">
                            <p><strong>Автор:</strong> {{ $comment->user->name }}</p>
                            <p><strong>Статья:</strong> {{ $comment->article->title }}</p>
                            <blockquote class="my-2 p-2 bg-gray-100 rounded">
                                {{ $comment->body }}
                            </blockquote>
                            <small class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</small>

                            <div class="mt-2 flex gap-4">
                                <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-primary-button>Одобрить</x-primary-button>
                                </form>

                                <form action="{{ route('admin.comments.reject', $comment) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button onclick="return confirm('Уверены?')">
                                        Отклонить
                                    </x-danger-button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p>Нет комментариев для модерации.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>