<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('API Tokens') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Manage API tokens for programmatic access to the application.') }}
        </p>
    </header>

    <!-- Generate API Token -->
    <form method="post" action="{{ route('profile.tokens.create') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="token_name" :value="__('Token Name')" />
            <x-text-input id="token_name" name="token_name" type="text" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('token_name')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Create API Token') }}</x-primary-button>
        </div>
    </form>

    <!-- Display newly created token -->
    @if (session('token'))
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <h3 class="text-lg font-medium text-green-800">{{ __('New API Token') }}</h3>
            <p class="mt-2 text-sm text-green-700">
                {{ __('Make sure to copy your new API token now. You won\'t be able to see it again!') }}
            </p>
            <div class="mt-3 p-3 bg-white border border-green-200 rounded font-mono text-sm break-all">
                {{ session('token') }}
            </div>
        </div>
    @endif

    <!-- List existing tokens -->
    <div class="mt-6">
        <h3 class="text-lg font-medium text-gray-900">{{ __('Existing Tokens') }}</h3>
        
        @if (isset($tokens) && $tokens->count() > 0)
            <div class="mt-4 space-y-4">
                @foreach ($tokens as $token)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ $token->name }}</div>
                            <div class="text-sm text-gray-500">
                                Created: {{ $token->created_at->format('M d, Y H:i') }}
                                @if ($token->expires_at)
                                    | Expires: {{ $token->expires_at->format('M d, Y H:i') }}
                                @endif
                            </div>
                        </div>
                        <div>
                            <form method="post" action="{{ route('profile.tokens.delete', $token->id) }}">
                                @csrf
                                @method('delete')
                                <x-danger-button onclick="return confirm('Are you sure you want to delete this token?')">
                                    {{ __('Delete') }}
                                </x-danger-button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="mt-4 text-sm text-gray-600">{{ __('You have not created any API tokens yet.') }}</p>
        @endif
    </div>
</section>