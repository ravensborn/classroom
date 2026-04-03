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
            + {{ __('Upload Video') }}
        </button>
    </div>

    {{-- Upload modal --}}
    @if($showUploadModal)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg border border-zinc-200 shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-zinc-900 mb-1">{{ __('Upload Video') }}</h3>
                <p class="text-sm text-zinc-500 mb-4">{{ __('Add a new video to this classroom.') }}</p>
                <div class="border-t border-zinc-100 my-4"></div>
                <form wire:submit="uploadVideo" class="space-y-4"
                      x-data="{ uploading: false, progress: 0 }"
                      x-on:livewire-upload-start="uploading = true; progress = 0"
                      x-on:livewire-upload-finish="uploading = false"
                      x-on:livewire-upload-cancel="uploading = false"
                      x-on:livewire-upload-error="uploading = false"
                      x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <div>
                        <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Video Title') }}</label>
                        <input wire:model="title" type="text"
                               class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('title') border-red-500 focus:ring-red-500 @enderror">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Description') }}</label>
                        <textarea wire:model="description" rows="3"
                                  class="flex w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('description') border-red-500 focus:ring-red-500 @enderror"></textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Video File') }}</label>
                        <input wire:model="videoFile" type="file" accept=".mp4,.mov,.avi"
                               class="w-full text-sm text-zinc-500 file:me-4 file:py-1.5 file:px-3 file:rounded-md file:border file:border-zinc-200 file:text-sm file:font-medium file:bg-white file:text-zinc-700 hover:file:bg-zinc-50 transition-colors">
                        @error('videoFile') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-zinc-400">{{ __('Select video file (MP4, MOV, AVI)') }} — {{ __('Max file size: 500MB') }}</p>

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
                    </div>
                    <div class="flex gap-3 pt-2 justify-end">
                        <button type="button" wire:click="closeUploadModal" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('Cancel') }}</button>
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none"
                                wire:loading.attr="disabled" wire:loading.class="opacity-75"
                                x-bind:disabled="uploading">
                            <span wire:loading.remove wire:target="uploadVideo">{{ __('Upload') }}</span>
                            <span wire:loading wire:target="uploadVideo">{{ __('Uploading...') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg border border-zinc-200 shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-zinc-900 mb-1">{{ __('Edit Video') }}</h3>
                <p class="text-sm text-zinc-500 mb-4">{{ __('Update video title and description.') }}</p>
                <div class="border-t border-zinc-100 my-4"></div>
                <form wire:submit="saveEdit" class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Video Title') }}</label>
                        <input wire:model="title" type="text"
                               class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('title') border-red-500 focus:ring-red-500 @enderror">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Description') }}</label>
                        <textarea wire:model="description" rows="3"
                                  class="flex w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('description') border-red-500 focus:ring-red-500 @enderror"></textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-3 pt-2 justify-end">
                        <button type="button" wire:click="closeEditModal" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('Cancel') }}</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none" wire:loading.attr="disabled">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete confirm --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg border border-zinc-200 shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-zinc-900 mb-1">{{ __('Delete Video') }}</h3>
                <p class="text-sm text-zinc-500 mb-4">{{ __('Are you sure you want to delete this?') }}</p>
                <div class="border-t border-zinc-100 my-4"></div>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('No, Cancel') }}</button>
                    <button wire:click="deleteVideo" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 px-4 h-9 transition-colors">{{ __('Yes, Delete') }}</button>
                </div>
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
                @php $isOpen = $video->isAttendanceOpen(); @endphp
                <div wire:key="video-{{ $video->id }}" class="rounded-lg border border-zinc-200 bg-white shadow-sm">
                    <div class="p-5 flex flex-wrap items-start justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-10 h-10 bg-zinc-100 rounded-md flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-zinc-900 truncate">{{ $video->title }}</h3>
                                <p class="text-sm text-zinc-500 mt-0.5 line-clamp-2">{{ $video->description }}</p>
                                <p class="text-xs text-zinc-400 mt-1">{{ $video->created_at->locale('en')->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button wire:click="toggleAttendance({{ $video->id }})"
                                    class="inline-flex items-center gap-1.5 justify-center rounded-md text-sm font-medium px-3 h-9 transition-colors {{ $showingAttendanceForVideoId === $video->id ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                {{ __('Attendance') }}
                            </button>
                            <button wire:click="openEditModal({{ $video->id }})"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('Edit') }}</button>
                            <button wire:click="confirmDelete({{ $video->id }})"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 px-3 h-9 transition-colors">{{ __('Delete') }}</button>
                        </div>
                    </div>

                    {{-- Attendance panel --}}
                    @if($showingAttendanceForVideoId === $video->id)
                        <div class="border-t border-zinc-100 px-5 py-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <h4 class="text-sm font-semibold text-zinc-700">
                                        {{ __('Attendees') }}
                                        <span class="ms-1.5 inline-flex items-center justify-center rounded-full bg-zinc-100 text-zinc-600 text-xs font-medium w-5 h-5">{{ $attendances->count() }}</span>
                                    </h4>
                                    <a href="{{ route('teacher.classrooms.videos.attendance.print', [$classroom, $video]) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 px-2 py-1 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        {{ __('Print') }}
                                    </a>
                                </div>
                                @if($isOpen)
                                    <span class="text-xs text-amber-600 font-medium">
                                        <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span dir="ltr" class="inline-block">{{ $video->attendanceRemainingForHumans() }}</span>
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
