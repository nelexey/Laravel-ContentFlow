<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ContentFlow') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="mb-3">
                        @can('create', App\Models\Article::class)
                            <a href="{{ route('articles.create') }}" class="btn btn-primary">Создать статью</a>
                        @endcan
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
            </div>
        </div>
    </div>
</x-app-layout>