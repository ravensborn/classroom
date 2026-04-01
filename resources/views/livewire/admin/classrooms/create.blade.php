<div class="max-w-xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.classrooms.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-900">{{ __('Add Classroom') }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                <input wire:model="name" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </button>
                <a href="{{ route('admin.classrooms.index') }}" class="px-6 py-2.5 text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
