<div>
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.teachers.index') }}" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ $teacher->name }}</h2>
                <p class="text-sm text-zinc-500">{{ __('Posts') }}</p>
            </div>
        </div>
    </div>

    {{-- Video player modal --}}
    @if($playingPostId && $playingVideoUrl)
        <div class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-4xl">
                <div class="flex justify-end mb-3">
                    <button wire:click="closePlayer" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-white hover:bg-white/10 px-3 h-9 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <video src="{{ $playingVideoUrl }}" controls autoplay class="w-full rounded-lg shadow-2xl max-h-[70vh]"></video>
            </div>
        </div>
    @endif

    @if($posts->isEmpty())
        <div class="text-center py-16 text-zinc-400 rounded-lg border border-zinc-200 bg-white shadow-sm">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>{{ __('No posts yet') }}</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($posts as $post)
                <div wire:key="post-{{ $post->id }}" class="rounded-lg border border-zinc-200 bg-white shadow-sm p-5">
                    <div class="flex items-start gap-4">
                        @if($post->video_path)
                            <button wire:click="playVideo({{ $post->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="playVideo({{ $post->id }})"
                                    class="ms-auto order-last shrink-0 inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                <span wire:loading.remove wire:target="playVideo({{ $post->id }})">{{ __('Play') }}</span>
                                <span wire:loading wire:target="playVideo({{ $post->id }})">...</span>
                            </button>
                        @endif
                        <div class="w-10 h-10 bg-zinc-100 rounded-md flex items-center justify-center shrink-0">
                            @if($post->video_path)
                                <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-semibold text-zinc-900 truncate">{{ $post->title }}</h3>
                                @if($post->video_path)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 text-zinc-600 text-xs font-medium px-2 py-0.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        {{ __('Has video') }}
                                    </span>
                                @endif
                            </div>
                            <div class="trix-content text-sm text-zinc-500 mt-1">{!! $post->description !!}</div>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2 text-xs text-zinc-500">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                    {{ $post->classroom?->name ?? __('Unknown') }}
                                </span>
                                <span class="text-zinc-300">·</span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    {{ $post->attendances_count }} {{ __('Attendees') }}
                                </span>
                                <span class="text-zinc-300">·</span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ $post->comments_count }} {{ __('Comments') }}
                                </span>
                                <span class="text-zinc-300">·</span>
                                <span dir="ltr">{{ $post->created_at->locale('en')->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>
