<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('Shaqlawa Private Institute') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.15/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.1.15/dist/trix.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @livewireStyles
    <style>
        body { font-family: 'Noto Naskh Arabic', serif; }
        trix-editor { direction: rtl; text-align: right; min-height: 140px; background: #fff; }
        trix-toolbar { direction: ltr; }
        .trix-content { line-height: 1.6; }
        .trix-content p { margin: 0.5em 0; }
        .trix-content ul, .trix-content ol { margin: 0.5em 1.5em; }
        .trix-content ul { list-style: disc; }
        .trix-content ol { list-style: decimal; }
        .trix-content blockquote { border-inline-start: 3px solid #e4e4e7; padding-inline-start: 0.75em; margin: 0.5em 0; color: #52525b; }
        .trix-content a { color: #2563eb; text-decoration: underline; }
        .trix-content h1 { font-size: 1.4em; font-weight: 600; margin: 0.6em 0 0.3em; }
        .trix-content h2 { font-size: 1.2em; font-weight: 600; margin: 0.6em 0 0.3em; }
        .trix-content pre { background: #f4f4f5; padding: 0.5em 0.75em; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 0.9em; }
    </style>
</head>
<body class="min-h-screen bg-zinc-50 antialiased">

    {{-- Header --}}
    <header class="sticky top-0 z-40 bg-white border-b border-zinc-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center gap-2 min-w-0">
                    @php $role = auth()->user()?->role?->value; @endphp
                    <a href="{{ $role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard') }}"
                       class="flex items-center gap-2 text-sm font-semibold text-zinc-900 min-w-0">
                        <img src="{{ asset('logo.png') }}" alt="{{ __('Shaqlawa Private Institute') }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                        <span class="truncate hidden sm:block">{{ __('Shaqlawa Private Institute') }}</span>
                    </a>
                    <div class="flex flex-col leading-tight sm:hidden min-w-0">
                        <span class="text-sm text-zinc-700 font-medium truncate max-w-40">{{ auth()->user()?->name }}</span>
                        @if(auth()->user()?->isStudent())
                            <span class="text-xs text-zinc-400 truncate">
                                {{ auth()->user()->department?->name }}
                                @if(auth()->user()->stage)
                                    · {{ __('Stage') }} {{ auth()->user()->stage }}
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <div class="px-2 hidden sm:flex flex-col items-end leading-tight">
                        <span class="text-sm text-zinc-700 font-medium truncate max-w-40">{{ auth()->user()?->name }}</span>
                        @if(auth()->user()?->isStudent())
                            <span class="text-xs text-zinc-400">
                                {{ auth()->user()->department?->name }}
                                @if(auth()->user()->stage)
                                    · {{ __('Stage') }} {{ auth()->user()->stage }}
                                @endif
                            </span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 rounded-md transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span class="hidden sm:inline">{{ __('Logout') }}</span>
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
