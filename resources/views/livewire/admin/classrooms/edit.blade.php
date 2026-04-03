<div class="max-w-4xl w-full">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.classrooms.index') }}" class="text-zinc-400 hover:text-zinc-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ __('Edit Classroom') }}</h2>
    </div>

    <form wire:submit="save" class="space-y-5">
        {{-- Name --}}
        <div class="rounded-lg border border-zinc-200 bg-white shadow-sm p-6">
            <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Name') }}</label>
            <input wire:model="name" type="text" class="flex h-9 w-full sm:max-w-sm rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('name') border-red-500 focus:ring-red-500 @enderror">
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Assign Teachers --}}
        <div class="rounded-lg border border-zinc-200 bg-white shadow-sm p-6">
            <h3 class="text-base font-semibold text-zinc-900 mb-4">{{ __('Assign Teachers') }}</h3>
            <div class="mb-3">
                <input wire:model.live.debounce.300ms="teacherSearch" type="text" placeholder="{{ __('Search by name') }}..."
                       class="flex h-9 w-full sm:max-w-sm rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900">
            </div>
            <div class="space-y-1 max-h-64 overflow-y-auto">
                @forelse($teachers as $teacher)
                    <label wire:key="teacher-{{ $teacher->id }}" class="flex items-center gap-3 cursor-pointer hover:bg-zinc-50 px-2 py-1.5 rounded-md">
                        <input type="checkbox" wire:model="selectedTeacherIds" value="{{ $teacher->id }}"
                               class="rounded border-zinc-300 text-zinc-900 w-4 h-4">
                        <span class="text-sm text-zinc-700">{{ $teacher->name }}</span>
                        <span class="text-xs text-zinc-400">@{{ $teacher->username }}</span>
                    </label>
                @empty
                    <p class="text-sm text-zinc-400">{{ __('No teachers found') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Enroll Students --}}
        <div class="rounded-lg border border-zinc-200 bg-white shadow-sm p-6">
            <h3 class="text-base font-semibold text-zinc-900 mb-4">{{ __('Enroll Students') }}</h3>
            <div class="flex flex-wrap gap-2 mb-3">
                <input wire:model.live.debounce.300ms="studentSearch" type="text" placeholder="{{ __('Search by name') }}..."
                       class="flex h-9 w-full sm:max-w-xs rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                <select wire:model.live="studentStage"
                        class="h-9 rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors text-zinc-700 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                    <option value="">{{ __('All Stages') }}</option>
                    @foreach($stages as $stage)
                        <option value="{{ $stage }}">{{ __('Stage') }} {{ $stage }}</option>
                    @endforeach
                </select>
                <select wire:model.live="studentDepartment"
                        class="h-9 rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors text-zinc-700 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                    <option value="">{{ __('All Departments') }}</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1 max-h-64 overflow-y-auto">
                @forelse($students as $student)
                    <label wire:key="student-{{ $student->id }}" class="flex items-center gap-3 cursor-pointer hover:bg-zinc-50 px-2 py-1.5 rounded-md">
                        <input type="checkbox" wire:model="selectedStudentIds" value="{{ $student->id }}"
                               class="rounded border-zinc-300 text-zinc-900 w-4 h-4">
                        <span class="text-sm text-zinc-700">{{ $student->name }}</span>
                        <span class="text-xs text-zinc-400">{{ $student->department?->name }} · {{ __('Stage') }} {{ $student->stage }}</span>
                    </label>
                @empty
                    <p class="text-sm text-zinc-400">{{ __('No students found') }}</p>
                @endforelse
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none" wire:loading.attr="disabled">
                {{ __('Save') }}
            </button>
            <a href="{{ route('admin.classrooms.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-zinc-200 bg-white text-zinc-900 hover:bg-zinc-50 px-4 h-9 transition-colors">{{ __('Cancel') }}</a>
        </div>
    </form>
</div>
