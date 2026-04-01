<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('Admin Panel') }} — {{ __('Shaqlawa Private Institute') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-zinc-50 antialiased" x-data="{ open: false }">

    {{-- ============================================================
         MOBILE: backdrop + sliding drawer (hidden on lg+)
         ============================================================ --}}
    <div class="lg:hidden" x-cloak>
        {{-- Backdrop --}}
        <div
            x-show="open"
            x-transition:enter="transition-opacity duration-300 ease-in-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300 ease-in-out"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="fixed inset-0 bg-black/60 z-30"
        ></div>

        {{-- Mobile sidebar drawer --}}
        <aside
            x-show="open"
            x-transition:enter="transition-transform duration-300 ease-in-out"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform duration-300 ease-in-out"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 end-0 z-40 w-64 flex flex-col bg-zinc-900 text-zinc-50 shadow-xl"
        >
            <div class="p-5 border-b border-zinc-800 flex flex-col items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="{{ __('Shaqlawa Private Institute') }}" class="w-14 h-14 rounded-full object-cover ring-2 ring-zinc-700">
                <div class="text-center">
                    <h1 class="text-sm font-semibold leading-tight">{{ __('Shaqlawa Private Institute') }}</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">{{ __('Admin Panel') }}</p>
                </div>
            </div>
            <nav class="flex-1 p-3 space-y-0.5 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('admin.students.index') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    {{ __('Students') }}
                </a>
                <a href="{{ route('admin.teachers.index') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.teachers.*') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ __('Teachers') }}
                </a>
                <a href="{{ route('admin.classrooms.index') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.classrooms.*') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ __('Classrooms') }}
                </a>
                <a href="{{ route('admin.departments.index') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.departments.*') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    {{ __('Departments') }}
                </a>
            </nav>
            <div class="p-3 border-t border-zinc-800">
                <div class="flex items-center gap-2.5 px-3 py-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-zinc-700 flex items-center justify-center text-xs font-semibold text-zinc-300 shrink-0">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
                    <span class="text-sm text-zinc-300 truncate">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50 transition-colors">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </aside>
    </div>

    {{-- ============================================================
         PAGE LAYOUT
         ============================================================ --}}
    <div class="flex min-h-screen">

        {{-- DESKTOP sidebar (always visible, no Alpine) --}}
        <aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-zinc-900 text-zinc-50">
            <div class="p-5 border-b border-zinc-800 flex flex-col items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="{{ __('Shaqlawa Private Institute') }}" class="w-14 h-14 rounded-full object-cover ring-2 ring-zinc-700">
                <div class="text-center">
                    <h1 class="text-sm font-semibold leading-tight">{{ __('Shaqlawa Private Institute') }}</h1>
                    <p class="text-xs text-zinc-400 mt-0.5">{{ __('Admin Panel') }}</p>
                </div>
            </div>
            <nav class="flex-1 p-3 space-y-0.5 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('admin.students.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    {{ __('Students') }}
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.teachers.*') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ __('Teachers') }}
                </a>
                <a href="{{ route('admin.classrooms.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.classrooms.*') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ __('Classrooms') }}
                </a>
                <a href="{{ route('admin.departments.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.departments.*') ? 'bg-zinc-800 text-zinc-50' : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    {{ __('Departments') }}
                </a>
            </nav>
            <div class="p-3 border-t border-zinc-800">
                <div class="flex items-center gap-2.5 px-3 py-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-zinc-700 flex items-center justify-center text-xs font-semibold text-zinc-300 shrink-0">{{ mb_substr(auth()->user()->name, 0, 1) }}</div>
                    <span class="text-sm text-zinc-300 truncate">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm text-zinc-400 hover:bg-zinc-800 hover:text-zinc-50 transition-colors">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main area --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Mobile top bar --}}
            <header class="lg:hidden sticky top-0 z-20 flex items-center gap-3 px-4 h-14 bg-white border-b border-zinc-200 shrink-0">
                <button @click="open = true" class="p-1.5 rounded-md text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <img src="{{ asset('logo.png') }}" alt="" class="w-7 h-7 rounded-full object-cover shrink-0">
                <span class="font-semibold text-sm text-zinc-900 truncate">{{ __('Shaqlawa Private Institute') }}</span>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-4 sm:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
