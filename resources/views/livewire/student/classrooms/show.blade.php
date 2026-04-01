<div>
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-900">{{ $classroom->name }}</h2>
    </div>

    {{-- Video player modal --}}
    @if($playingVideoId && $playingVideoUrl)
        <div class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-4xl">
                <div class="flex justify-end mb-2">
                    <button wire:click="closePlayer" class="text-white hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <video
                    src="{{ $playingVideoUrl }}"
                    controls
                    autoplay
                    class="w-full rounded-xl shadow-2xl max-h-[70vh]"
                >
                </video>
            </div>
        </div>
    @endif

    {{-- Videos list --}}
    @if($videos->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <p>{{ __('No videos yet') }}</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($videos as $video)
                <div class="bg-white rounded-xl shadow-sm p-5 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-900">{{ $video->title }}</h3>
                            <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">{{ $video->description }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $video->teacher->name }} · {{ $video->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <button
                        wire:click="playVideo({{ $video->id }})"
                        wire:loading.attr="disabled"
                        wire:target="playVideo({{ $video->id }})"
                        class="shrink-0 flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <span wire:loading.remove wire:target="playVideo({{ $video->id }})">{{ __('Play') }}</span>
                        <span wire:loading wire:target="playVideo({{ $video->id }})">...</span>
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
