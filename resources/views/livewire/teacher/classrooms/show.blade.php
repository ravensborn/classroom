<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('teacher.dashboard') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">{{ $classroom->name }}</h2>
        </div>
        <button wire:click="openUploadModal"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            + {{ __('Upload Video') }}
        </button>
    </div>

    {{-- Upload modal --}}
    @if($showUploadModal)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Upload Video') }}</h3>
                <form wire:submit="uploadVideo" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Video Title') }}</label>
                        <input wire:model="title" type="text"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-400 @enderror">
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-400 @enderror"></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Video File') }}</label>
                        <input wire:model="videoFile" type="file" accept=".mp4,.mov,.avi"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('videoFile') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-gray-400">{{ __('Select video file (MP4, MOV, AVI)') }} — {{ __('Max file size: 500MB') }}</p>
                        <div wire:loading wire:target="videoFile" class="mt-2 text-sm text-indigo-600">{{ __('Uploading...') }}</div>
                    </div>
                    <div class="flex gap-3 pt-2 justify-end">
                        <button type="button" wire:click="closeUploadModal" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</button>
                        <button type="submit"
                                class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                                wire:loading.attr="disabled" wire:loading.class="opacity-75">
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
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Edit Video') }}</h3>
                <form wire:submit="saveEdit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Video Title') }}</label>
                        <input wire:model="title" type="text"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-400 @enderror">
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-400 @enderror"></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-3 pt-2 justify-end">
                        <button type="button" wire:click="closeEditModal" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors" wire:loading.attr="disabled">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete confirm --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Delete Video') }}</h3>
                <p class="text-gray-500 text-sm mb-4">{{ __('Are you sure you want to delete this?') }}</p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">{{ __('No, Cancel') }}</button>
                    <button wire:click="deleteVideo" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">{{ __('Yes, Delete') }}</button>
                </div>
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
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-900 truncate">{{ $video->title }}</h3>
                            <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">{{ $video->description }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $video->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <button wire:click="openEditModal({{ $video->id }})"
                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">{{ __('Edit') }}</button>
                        <button wire:click="confirmDelete({{ $video->id }})"
                                class="text-red-500 hover:text-red-700 text-sm font-medium">{{ __('Delete') }}</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
