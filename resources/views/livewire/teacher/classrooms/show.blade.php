<div>
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('teacher.dashboard') }}" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ $classroom->name }}</h2>
        </div>
        <button wire:click="openUploadModal"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors shrink-0">
            + {{ __('New Post') }}
        </button>
    </div>

    {{-- Create / Edit modal (shared markup) --}}
    @if($showUploadModal || $showEditModal)
        @php $isEdit = $showEditModal; @endphp
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg border border-zinc-200 shadow-xl w-full max-w-xl p-6 max-h-[90vh] overflow-y-auto"
                 x-data="{
                     uploading: false,
                     uploaded: false,
                     progress: 0,
                     uploadError: null,
                     async handleFile(event) {
                         const file = event.target.files[0];
                         if (!file) return;
                         const ext = file.name.split('.').pop().toLowerCase();
                         this.uploading = true;
                         this.uploaded = false;
                         this.progress = 0;
                         this.uploadError = null;
                         try {
                             const { url } = await $wire.getPresignedUploadUrl(ext);
                             await new Promise((resolve, reject) => {
                                 const xhr = new XMLHttpRequest();
                                 xhr.upload.onprogress = (e) => {
                                     if (e.lengthComputable) this.progress = Math.round(e.loaded / e.total * 100);
                                 };
                                 xhr.onload = () => xhr.status >= 200 && xhr.status < 300 ? resolve() : reject();
                                 xhr.onerror = reject;
                                 xhr.open('PUT', url);
                                 xhr.setRequestHeader('Content-Type', file.type || 'application/octet-stream');
                                 xhr.send(file);
                             });
                             this.uploaded = true;
                             $wire.set('removeExistingVideo', false, false);
                         } catch {
                             this.uploadError = '{{ __('Upload failed. Please try again.') }}';
                         } finally {
                             this.uploading = false;
                         }
                     }
                 }">
                <h3 class="text-lg font-semibold text-zinc-900 mb-1">{{ $isEdit ? __('Edit Post') : __('New Post') }}</h3>
                <p class="text-sm text-zinc-500 mb-4">{{ __('Write a post and optionally attach a video.') }}</p>
                <div class="border-t border-zinc-100 my-4"></div>
                <form wire:submit="{{ $isEdit ? 'saveEdit' : 'savePost' }}" class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Post Title') }}</label>
                        <input wire:model="title" type="text"
                               class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('title') border-red-500 focus:ring-red-500 @enderror">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div
                        wire:ignore
                        x-data="{
                            init() {
                                const input = this.$refs.descInput;
                                const editor = this.$refs.editor;
                                editor.addEventListener('trix-change', () => {
                                    @this.set('description', editor.innerHTML, false);
                                });
                                @if($isEdit)
                                    this.$nextTick(() => {
                                        if (editor.editor) {
                                            editor.editor.loadHTML(@this.get('description') || '');
                                        }
                                    });
                                @endif
                            }
                        }"
                    >
                        <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Description') }}</label>
                        <input x-ref="descInput" type="hidden" id="post-description-{{ $isEdit ? 'edit' : 'create' }}">
                        <trix-editor
                            x-ref="editor"
                            input="post-description-{{ $isEdit ? 'edit' : 'create' }}"
                            class="trix-content rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('description') border-red-500 @enderror"></trix-editor>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Video File (Optional)') }}</label>
                        <input type="file" accept=".mp4,.mov,.avi"
                               x-on:change="handleFile($event)"
                               x-bind:disabled="uploading"
                               class="w-full text-sm text-zinc-500 file:me-4 file:py-1.5 file:px-3 file:rounded-md file:border file:border-zinc-200 file:text-sm file:font-medium file:bg-white file:text-zinc-700 hover:file:bg-zinc-50 transition-colors disabled:opacity-50">
                        <p class="mt-1 text-xs text-zinc-400" x-show="!uploading && !uploaded">{{ __('Select video file (MP4, MOV, AVI)') }} — {{ __('Max file size: 500MB') }}</p>

                        <div x-show="uploading" x-cloak class="mt-3 space-y-1">
                            <div class="flex justify-between text-xs text-zinc-500">
                                <span>{{ __('Uploading...') }}</span>
                                <span x-text="progress + '%'"></span>
                            </div>
                            <div class="w-full bg-zinc-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-zinc-900 h-2 rounded-full transition-all duration-150"
                                     x-bind:style="'width: ' + progress + '%'"></div>
                            </div>
                        </div>

                        <p x-show="uploaded" x-cloak class="mt-2 text-xs text-emerald-600 font-medium">
                            <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('File uploaded successfully') }}
                        </p>
                        <p x-show="uploadError" x-cloak x-text="uploadError" class="mt-2 text-xs text-red-500"></p>

                        @if($isEdit)
                            <label class="mt-3 flex items-center gap-2 text-xs text-zinc-600">
                                <input type="checkbox" wire:model="removeExistingVideo" class="rounded border-zinc-300">
                                {{ __('Remove existing video') }}
                            </label>
                        @endif
                    </div>
                    <div class="flex gap-3 pt-2 justify-end">
                        <button type="button" wire:click="{{ $isEdit ? 'closeEditModal' : 'closeUploadModal' }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('Cancel') }}</button>
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none"
                                x-bind:disabled="uploading"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ __('Save') }}</span>
                            <span wire:loading>...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Video player modal --}}
    @if($playingPostId && $playingVideoUrl)
        <div class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-4xl">
                <div class="flex justify-end mb-3">
                    <button wire:click="closePlayer" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-white hover:bg-white/10 px-3 h-9 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <video src="{{ $playingVideoUrl }}" controls autoplay class="w-full rounded-lg shadow-2xl max-h-[70vh]"></video>
            </div>
        </div>
    @endif

    {{-- Delete confirm --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg border border-zinc-200 shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-zinc-900 mb-1">{{ __('Delete Post') }}</h3>
                <p class="text-sm text-zinc-500 mb-4">{{ __('Are you sure you want to delete this?') }}</p>
                <div class="border-t border-zinc-100 my-4"></div>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('No, Cancel') }}</button>
                    <button wire:click="deletePost" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 px-4 h-9 transition-colors">{{ __('Yes, Delete') }}</button>
                </div>
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
                @php $isOpen = $post->isAttendanceOpen(); @endphp
                <div wire:key="post-{{ $post->id }}" class="rounded-lg border border-zinc-200 bg-white shadow-sm">
                    <div class="p-5 flex flex-wrap items-start justify-between gap-4">
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
                                <h3 class="font-semibold text-zinc-900 truncate">{{ $post->title }}</h3>
                                <div class="trix-content text-sm text-zinc-600 mt-1">{!! $post->description !!}</div>
                                <p class="text-xs text-zinc-400 mt-2" dir="ltr">{{ $post->created_at->locale('en')->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($post->hasVideo())
                                <button wire:click="playVideo({{ $post->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="playVideo({{ $post->id }})"
                                        class="inline-flex items-center gap-1.5 justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-3 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    <span wire:loading.remove wire:target="playVideo({{ $post->id }})">{{ __('Play') }}</span>
                                    <span wire:loading wire:target="playVideo({{ $post->id }})">...</span>
                                </button>
                            @endif
                            <button wire:click="toggleAttendance({{ $post->id }})"
                                    class="inline-flex items-center gap-1.5 justify-center rounded-md text-sm font-medium px-3 h-9 transition-colors {{ $showingAttendanceForPostId === $post->id ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                {{ __('Attendance') }}
                            </button>
                            <button wire:click="toggleComments({{ $post->id }})"
                                    class="inline-flex items-center gap-1.5 justify-center rounded-md text-sm font-medium px-3 h-9 transition-colors {{ $showingCommentsForPostId === $post->id ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                {{ __('Comments') }}
                                @if($post->comments_count > 0)
                                    <span class="inline-flex items-center justify-center rounded-full {{ $showingCommentsForPostId === $post->id ? 'bg-white/20 text-white' : 'bg-zinc-100 text-zinc-600' }} text-xs font-medium w-5 h-5">{{ $post->comments_count }}</span>
                                @endif
                            </button>
                            <button wire:click="openEditModal({{ $post->id }})"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('Edit') }}</button>
                            <button wire:click="confirmDelete({{ $post->id }})"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 px-3 h-9 transition-colors">{{ __('Delete') }}</button>
                        </div>
                    </div>

                    {{-- Comments panel --}}
                    @if($showingCommentsForPostId === $post->id)
                        <div class="border-t border-zinc-100 px-5 py-4 space-y-3">
                            <h4 class="text-sm font-semibold text-zinc-700">{{ __('Comments') }}</h4>
                            @forelse($comments as $comment)
                                <div wire:key="comment-{{ $comment->id }}" class="flex gap-3 items-start">
                                    <div class="w-7 h-7 rounded-full bg-zinc-200 flex items-center justify-center text-xs font-semibold text-zinc-600 shrink-0">
                                        {{ $comment->author->initials() }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-baseline justify-between gap-2">
                                            <div>
                                                <span class="text-xs font-semibold text-zinc-700">{{ $comment->author->name }}</span>
                                                <span class="text-xs text-zinc-400 ms-1.5">{{ $comment->author->department?->name }} · {{ __('Stage') }} {{ $comment->author->stage }}</span>
                                            </div>
                                            <span class="text-xs text-zinc-400 shrink-0" dir="ltr">{{ $comment->created_at->locale('en')->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-zinc-700 mt-0.5 break-words">{{ $comment->body }}</p>
                                    </div>
                                    <button wire:click="deleteComment({{ $comment->id }})"
                                            class="shrink-0 text-zinc-300 hover:text-red-500 transition-colors mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-sm text-zinc-400">{{ __('No comments yet') }}</p>
                            @endforelse
                        </div>
                    @endif

                    {{-- Attendance panel --}}
                    @if($showingAttendanceForPostId === $post->id)
                        <div class="border-t border-zinc-100 px-5 py-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <h4 class="text-sm font-semibold text-zinc-700">
                                        {{ __('Attendees') }}
                                        <span class="ms-1.5 inline-flex items-center justify-center rounded-full bg-zinc-100 text-zinc-600 text-xs font-medium w-5 h-5">{{ $attendances->count() }}</span>
                                    </h4>
                                    <a href="{{ route('teacher.classrooms.posts.attendance.print', [$classroom, $post]) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 px-2 py-1 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        {{ __('Print') }}
                                    </a>
                                </div>
                                @if($isOpen)
                                    <span class="text-xs text-amber-600 font-medium">
                                        <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span dir="ltr" class="inline-block">{{ $post->attendanceRemainingForHumans() }}</span>
                                    </span>
                                @else
                                    <span class="text-xs text-zinc-400">{{ __('Attendance window closed') }}</span>
                                @endif
                            </div>
                            @if($attendances->isEmpty())
                                <p class="text-sm text-zinc-400 py-2">{{ __('No students have recorded attendance yet.') }}</p>
                            @else
                                <div class="divide-y divide-zinc-100">
                                    @foreach($attendances as $attendance)
                                        <div class="flex items-center justify-between py-2.5">
                                            <div>
                                                <span class="text-sm font-medium text-zinc-800">{{ $attendance->student->name }}</span>
                                                <span class="text-xs text-zinc-400 ms-2">{{ $attendance->student->department?->name }} · {{ __('Stage') }} {{ $attendance->student->stage }}</span>
                                            </div>
                                            <span class="text-xs text-zinc-400" dir="ltr">{{ $attendance->created_at->locale('en')->diffForHumans() }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
