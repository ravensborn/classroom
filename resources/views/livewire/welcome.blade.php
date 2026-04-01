<div class="min-h-screen flex flex-col bg-zinc-50">
    {{-- Hero --}}
    <div class="flex-1 flex flex-col items-center justify-center text-center px-4 py-16">
        <div class="mb-8">
            <img src="{{ asset('logo.png') }}" alt="{{ __('Shaqlawa Private Institute') }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-6 shadow-sm border border-zinc-200">
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 mb-3">{{ __('Shaqlawa Private Institute') }}</h1>
            <p class="text-base text-zinc-500 mb-1">{{ __('A modern learning platform') }}</p>
            <p class="text-sm text-zinc-400">{{ __('For Students and Teachers') }}</p>
        </div>

        <a href="{{ route('login') }}"
           class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-6 h-9 transition-colors">
            {{ __('Enter the platform') }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
    </div>

    {{-- Footer --}}
    <footer class="py-5 text-center text-xs text-zinc-400 border-t border-zinc-200">
        &copy; {{ date('Y') }} {{ __('Shaqlawa Private Institute') }}
    </footer>
</div>
