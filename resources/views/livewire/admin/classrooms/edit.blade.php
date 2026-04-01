<div class="max-w-4xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.classrooms.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-900">{{ __('Edit Classroom') }}</h2>
    </div>

    <form wire:submit="save" class="space-y-6">
        {{-- Name --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
            <input wire:model="name" type="text" class="w-full max-w-sm border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Assign Teachers --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">{{ __('Assign Teachers') }}</h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @forelse($teachers as $teacher)
                    <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 px-2 py-1.5 rounded-lg">
                        <input type="checkbox" wire:model="selectedTeacherIds" value="{{ $teacher->id }}"
                               class="w-4 h-4 text-indigo-600 rounded">
                        <span class="text-sm text-gray-700">{{ $teacher->name }}</span>
                        <span class="text-xs text-gray-400">@{{ $teacher->username }}</span>
                    </label>
                @empty
                    <p class="text-sm text-gray-400">{{ __('No classrooms yet') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Enroll Students --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">{{ __('Enroll Students') }}</h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @forelse($students as $student)
                    <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 px-2 py-1.5 rounded-lg">
                        <input type="checkbox" wire:model="selectedStudentIds" value="{{ $student->id }}"
                               class="w-4 h-4 text-indigo-600 rounded">
                        <span class="text-sm text-gray-700">{{ $student->name }}</span>
                        <span class="text-xs text-gray-400">{{ $student->department?->name }} · {{ __('Stage') }} {{ $student->stage }}</span>
                    </label>
                @empty
                    <p class="text-sm text-gray-400">{{ __('No classrooms yet') }}</p>
                @endforelse
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors" wire:loading.attr="disabled">
                {{ __('Save') }}
            </button>
            <a href="{{ route('admin.classrooms.index') }}" class="px-6 py-2.5 text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</a>
        </div>
    </form>
</div>
