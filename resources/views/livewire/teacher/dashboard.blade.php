<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('My Classrooms') }}</h2>

    @if($classrooms->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p>{{ __('No classrooms yet') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($classrooms as $classroom)
                <a href="{{ route('teacher.classrooms.show', $classroom) }}"
                   class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-transparent hover:border-indigo-100 group">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-colors">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $classroom->name }}</h3>
                    <p class="text-sm text-gray-400">{{ $classroom->videos_count }} {{ __('Videos') }}</p>
                </a>
            @endforeach
        </div>
    @endif
</div>
