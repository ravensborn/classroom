<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.students.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-900">{{ __('Edit Student') }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                <input wire:model="name" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Username') }}</label>
                <input wire:model="username" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('username') border-red-400 @enderror">
                @error('username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }} ({{ __('No, Cancel') }})</label>
                    <input wire:model="password" type="password" placeholder="••••••••" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-400 @enderror">
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }} ({{ __('Confirm Delete') }})</label>
                    <input wire:model="passwordConfirmation" type="password" placeholder="••••••••" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Department') }}</label>
                    <select wire:model="departmentId" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('departmentId') border-red-400 @enderror">
                        <option value="">—</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('departmentId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Stage') }}</label>
                    <select wire:model="stage" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach(range(1, 5) as $s)
                            <option value="{{ $s }}">{{ __('Stage ' . $s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Active status --}}
            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input wire:model.live="isActive" type="checkbox" class="w-4 h-4 text-indigo-600 rounded">
                    <span class="text-sm font-medium text-gray-700">{{ __('Active') }}</span>
                </label>
            </div>

            @if(!$isActive)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Block Message') }}</label>
                    <input wire:model="blockedMessage" type="text"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-red-400 @error('blockedMessage') border-red-400 @enderror">
                    @error('blockedMessage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </button>
                <a href="{{ route('admin.students.index') }}" class="px-6 py-2.5 text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
