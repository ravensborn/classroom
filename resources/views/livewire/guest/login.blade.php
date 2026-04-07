<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-zinc-50">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-zinc-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <img src="{{ asset('logo.png') }}" alt="{{ __('Shaqlawa Private Institute') }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-6 shadow-sm border border-zinc-200">
            </div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ __('Shaqlawa Private Institute') }}</h1>
            <p class="text-zinc-500 mt-1 text-sm">{{ __('Welcome back') }}</p>
        </div>

        {{-- Card --}}
        <div class="rounded-lg border border-zinc-200 bg-white shadow-sm p-8">
            <form wire:submit="login" class="space-y-5">
                {{-- Username --}}
                <div>
                    <label for="username" class="text-sm font-medium text-zinc-700 block mb-1">
                        {{ __('Username') }}
                    </label>
                    <input
                        wire:model="username"
                        id="username"
                        type="text"
                        autocomplete="username"
                        class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('username') border-red-500 focus:ring-red-500 @enderror"
                    >
                    @error('username')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="text-sm font-medium text-zinc-700 block mb-1">
                        {{ __('Password') }}
                    </label>
                    <input
                        wire:model="password"
                        id="password"
                        type="password"
                        autocomplete="current-password"
                        class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('password') border-red-500 focus:ring-red-500 @enderror"
                    >
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none w-full"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75"
                >
                    <span wire:loading.remove>{{ __('Login') }}</span>
                    <span wire:loading>{{ __('Uploading...') }}</span>
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-sm text-zinc-400 hover:text-zinc-600 transition-colors">
                {{ __('Back') }}
            </a>
        </div>
    </div>
</div>
