<div>
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-zinc-400 hover:text-zinc-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ $classroom->name }}</h2>
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

    {{-- Posts list --}}
    @if($posts->isEmpty())
        <div class="text-center py-16 text-zinc-400">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>{{ __('No posts yet') }}</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($posts as $post)
                @php
                    $attended = in_array($post->id, $attendedPostIds);
                    $isOpen = $post->isAttendanceOpen();
                @endphp
                <div wire:key="post-{{ $post->id }}" class="rounded-lg border border-zinc-200 bg-white shadow-sm p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0 flex-1">
                            <div class="w-10 h-10 bg-zinc-100 rounded-md flex items-center justify-center shrink-0">
                                @if($post->hasVideo())
                                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-zinc-900">{{ $post->title }}</h3>
                                <div class="trix-content text-sm text-zinc-600 mt-1">{!! $post->description !!}</div>
                                <p class="text-xs text-zinc-400 mt-2">{{ $post->teacher->name }} · <span dir="ltr">{{ $post->created_at->locale('en')->diffForHumans() }}</span></p>
                            </div>
                        </div>
                        @if($post->hasVideo())
                            <button
                                wire:click="playVideo({{ $post->id }})"
                                wire:loading.attr="disabled"
                                wire:target="playVideo({{ $post->id }})"
                                class="shrink-0 inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                <span wire:loading.remove wire:target="playVideo({{ $post->id }})">{{ __('Play') }}</span>
                                <span wire:loading wire:target="playVideo({{ $post->id }})">...</span>
                            </button>
                        @endif
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
                                wire:click="markAttendance({{ $post->id }})"
                                wire:loading.attr="disabled"
                                wire:target="markAttendance({{ $post->id }})"
                                class="inline-flex items-center gap-1.5 rounded-md bg-blue-600 text-white hover:bg-blue-700 px-4 h-9 text-sm font-medium transition-colors disabled:opacity-50 disabled:pointer-events-none"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Attendance') }}
                            </button>
                            <span class="text-xs text-amber-600 font-medium">
                                <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span dir="ltr" class="inline-block">{{ $post->attendanceRemainingForHumans() }}</span> {{ __('time remaining') }}
                            </span>
                        @else
                            <span class="text-xs text-zinc-400">{{ __('Attendance window closed') }}</span>
                        @endif
                    </div>

                    {{-- Comments toggle --}}
                    <div class="mt-3 pt-3 border-t border-zinc-100">
                        <button wire:click="toggleComments({{ $post->id }})"
                                class="inline-flex items-center gap-1.5 text-sm font-medium transition-colors {{ $showingCommentsForPostId === $post->id ? 'text-zinc-900' : 'text-zinc-500 hover:text-zinc-900' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            {{ __('Comments') }}
                            @if($post->comments_count > 0)
                                <span class="inline-flex items-center justify-center rounded-full bg-zinc-100 text-zinc-600 text-xs font-medium w-5 h-5">{{ $post->comments_count }}</span>
                            @endif
                        </button>
                    </div>

                    {{-- Comments panel --}}
                    @if($showingCommentsForPostId === $post->id)
                        <div class="mt-3 space-y-3">
                            @forelse($post->comments as $comment)
                                <div wire:key="comment-{{ $comment->id }}" class="flex gap-3 items-start">
                                    <div class="w-7 h-7 rounded-full bg-zinc-200 flex items-center justify-center text-xs font-semibold text-zinc-600 shrink-0">
                                        {{ $comment->author->initials() }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-baseline justify-between gap-2">
                                            <span class="text-xs font-semibold text-zinc-700">{{ $comment->author->name }}</span>
                                            <span class="text-xs text-zinc-400 shrink-0" dir="ltr">{{ $comment->created_at->locale('en')->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-zinc-700 mt-0.5 break-words">{{ $comment->body }}</p>
                                    </div>
                                    @if($comment->user_id === auth()->id())
                                        <button wire:click="deleteComment({{ $comment->id }})"
                                                class="shrink-0 text-zinc-300 hover:text-red-500 transition-colors mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-zinc-400">{{ __('No comments yet') }}</p>
                            @endforelse

                            {{-- Add comment --}}
                            <div class="flex gap-2 pt-1">
                                <textarea wire:model="commentText"
                                          placeholder="{{ __('Write a comment...') }}"
                                          rows="1"
                                          maxlength="1000"
                                          class="flex-1 rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 resize-none @error('commentText') border-red-500 focus:ring-red-500 @enderror"></textarea>
                                <button wire:click="addComment({{ $post->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="addComment({{ $post->id }})"
                                        class="shrink-0 inline-flex items-center justify-center rounded-md bg-zinc-900 text-white hover:bg-zinc-800 px-3 h-9 text-sm font-medium transition-colors disabled:opacity-50 disabled:pointer-events-none self-start">
                                    {{ __('Send') }}
                                </button>
                            </div>
                            @error('commentText') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
