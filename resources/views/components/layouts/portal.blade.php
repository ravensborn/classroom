<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('Shaqlawa Private Institute') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @livewireStyles
    <style>
        body { font-family: 'Noto Naskh Arabic', serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 antialiased">

    {{-- Header --}}
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    @php $role = auth()->user()?->role?->value; @endphp
                    <a href="{{ $role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard') }}"
                       class="flex items-center gap-2 text-base font-bold text-indigo-900">
                        <img src="{{ asset('logo.png') }}" alt="{{ __('Shaqlawa Private Institute') }}" class="w-9 h-9 rounded-full object-cover">
                        {{ __('Shaqlawa Private Institute') }}
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">{{ auth()->user()?->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
