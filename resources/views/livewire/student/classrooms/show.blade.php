<div>
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-zinc-400 hover:text-zinc-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ $classroom->name }}</h2>
    </div>

    {{-- Video player modal --}}
    @if($playingVideoId && $playingVideoUrl)
        <div class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-4xl">
                <div class="flex justify-end mb-3">
                    <button wire:click="closePlayer" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-white hover:bg-white/10 px-3 h-9 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <video
                    src="{{ $playingVideoUrl }}"
                    controls
                    autoplay
                    class="w-full rounded-lg shadow-2xl max-h-[70vh]"
                >
                </video>
            </div>
        </div>
    @endif

    {{-- Videos list --}}
    @if($videos->isEmpty())
        <div class="text-center py-16 text-zinc-400">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <p>{{ __('No videos yet') }}</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($videos as $video)
                @php
                    $attended = in_array($video->id, $attendedVideoIds);
                    $isOpen = $video->isAttendanceOpen();
                    $deadline = $video->created_at->addDays(3);
                @endphp
                <div wire:key="video-{{ $video->id }}" class="rounded-lg border border-zinc-200 bg-white shadow-sm p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-10 h-10 bg-zinc-100 rounded-md flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-zinc-900">{{ $video->title }}</h3>
                                <p class="text-sm text-zinc-500 mt-0.5 line-clamp-2">{{ $video->description }}</p>
                                <p class="text-xs text-zinc-400 mt-1">{{ $video->teacher->name }} · {{ $video->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <button
                            wire:click="playVideo({{ $video->id }})"
                            wire:loading.attr="disabled"
                            wire:target="playVideo({{ $video->id }})"
                            class="shrink-0 inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <span wire:loading.remove wire:target="playVideo({{ $video->id }})">{{ __('Play') }}</span>
                            <span wire:loading wire:target="playVideo({{ $video->id }})">...</span>
                        </button>
                    </div>

                    {{-- Attendance --}}
                    <div class="mt-4 pt-4 border-t border-zinc-100 flex flex-wrap items-center gap-3">
                        @if($attended)
                            <span class="inline-flex items-center gap-1.5 rounded-md bg-emerald-50 border border-emerald-200 px-3 h-9 text-sm font-medium text-emerald-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Attendance recorded') }}
                            </span>
                        @elseif($isOpen)
                            <button
                                wire:click="markAttendance({{ $video->id }})"
                                wire:loading.attr="disabled"
                                wire:target="markAttendance({{ $video->id }})"
                                class="inline-flex items-center gap-1.5 rounded-md bg-blue-600 text-white hover:bg-blue-700 px-4 h-9 text-sm font-medium transition-colors disabled:opacity-50 disabled:pointer-events-none"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Attendance') }}
                            </button>
                            <span class="text-xs text-amber-600 font-medium">
                                <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $deadline->diffForHumans() }} {{ __('time remaining') }}
                            </span>
                        @else
                            <span class="text-xs text-zinc-400">{{ __('Attendance window closed') }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
