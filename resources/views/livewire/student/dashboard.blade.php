<div>
    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900 mb-6">{{ __('My Classrooms') }}</h2>

    @if($classrooms->isEmpty())
        <div class="text-center py-16 text-zinc-400">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p>{{ __('No classrooms yet') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($classrooms as $classroom)
                <a href="{{ route('student.classrooms.show', $classroom) }}"
                   class="rounded-lg border border-zinc-200 bg-white shadow-sm p-6 hover:bg-zinc-50 transition-colors group">
                    <div class="w-10 h-10 bg-zinc-100 rounded-md flex items-center justify-center mb-4 group-hover:bg-zinc-200 transition-colors">
                        <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 mb-1">{{ $classroom->name }}</h3>
                    <p class="text-sm text-zinc-400">{{ $classroom->posts_count }} {{ __('Posts') }}</p>
                </a>
            @endforeach
        </div>
    @endif
</div>
