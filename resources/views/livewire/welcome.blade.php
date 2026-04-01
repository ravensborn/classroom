<div class="min-h-screen flex flex-col">
    {{-- Hero --}}
    <div class="flex-1 flex flex-col items-center justify-center text-center px-4 py-16">
        <div class="mb-8">
            <img src="{{ asset('logo.png') }}" alt="{{ __('Shaqlawa Private Institute') }}" class="w-28 h-28 rounded-full object-cover mx-auto mb-6 shadow-lg border-4 border-indigo-100">
            <h1 class="text-4xl font-bold text-indigo-900 mb-3">{{ __('Shaqlawa Private Institute') }}</h1>
            <p class="text-xl text-gray-600 mb-2">{{ __('A modern learning platform') }}</p>
            <p class="text-base text-gray-500">{{ __('For Students and Teachers') }}</p>
        </div>

        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 text-white text-lg font-semibold rounded-xl shadow-md hover:bg-indigo-700 transition-colors">
            {{ __('Enter the platform') }}
            <svg class="w-5 h-5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- Footer --}}
    <footer class="py-6 text-center text-sm text-gray-400">
        &copy; {{ date('Y') }} {{ __('Shaqlawa Private Institute') }}
    </footer>
</div>
