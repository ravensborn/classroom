<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.students.index') }}" class="text-zinc-400 hover:text-zinc-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ __('Edit Student') }}</h2>
    </div>

    <div class="rounded-lg border border-zinc-200 bg-white shadow-sm p-6">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Name') }}</label>
                <input wire:model="name" type="text" class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('name') border-red-500 focus:ring-red-500 @enderror">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Username') }}</label>
                <input wire:model="username" type="text" class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('username') border-red-500 focus:ring-red-500 @enderror">
                @error('username') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Password') }} ({{ __('No, Cancel') }})</label>
                    <input wire:model="password" type="password" placeholder="••••••••" class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('password') border-red-500 focus:ring-red-500 @enderror">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Password') }} ({{ __('Confirm Delete') }})</label>
                    <input wire:model="passwordConfirmation" type="password" placeholder="••••••••" class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Department') }}</label>
                    <select wire:model="departmentId" class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('departmentId') border-red-500 focus:ring-red-500 @enderror">
                        <option value="">—</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('departmentId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Stage') }}</label>
                    <select wire:model="stage" class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                        @foreach(range(1, 5) as $s)
                            <option value="{{ $s }}">{{ __('Stage ' . $s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Active status --}}
            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input wire:model.live="isActive" type="checkbox" class="rounded border-zinc-300 text-zinc-900 w-4 h-4">
                    <span class="text-sm font-medium text-zinc-700">{{ __('Active') }}</span>
                </label>
            </div>

            @if(!$isActive)
                <div>
                    <label class="text-sm font-medium text-zinc-700 block mb-1">{{ __('Block Message') }}</label>
                    <input wire:model="blockedMessage" type="text"
                           class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('blockedMessage') border-red-500 focus:ring-red-500 @enderror">
                    @error('blockedMessage') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </button>
                <a href="{{ route('admin.students.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-zinc-200 bg-white text-zinc-900 hover:bg-zinc-50 px-4 h-9 transition-colors">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
