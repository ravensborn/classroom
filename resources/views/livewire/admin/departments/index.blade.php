<div>
    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900 mb-6">{{ __('Departments') }}</h2>

    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg border border-zinc-200 shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-zinc-900 mb-1">{{ __('Confirm Delete') }}</h3>
                <p class="text-sm text-zinc-500 mb-4">{{ __('Are you sure you want to delete this?') }}</p>
                <div class="border-t border-zinc-100 my-4"></div>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('No, Cancel') }}</button>
                    <button wire:click="deleteDepartment" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 px-4 h-9 transition-colors">{{ __('Yes, Delete') }}</button>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Add new --}}
        <div class="rounded-lg border border-zinc-200 bg-white shadow-sm p-6">
            <h3 class="text-base font-semibold text-zinc-900 mb-4">{{ __('Add Department') }}</h3>
            <form wire:submit="create" class="flex gap-3">
                <input wire:model="newName" type="text" placeholder="{{ __('Name') }}"
                       class="flex h-9 flex-1 rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('newName') border-red-500 focus:ring-red-500 @enderror">
                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors disabled:opacity-50 disabled:pointer-events-none" wire:loading.attr="disabled">
                    {{ __('Add') }}
                </button>
            </form>
            @error('newName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- List --}}
        <div class="rounded-lg border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-zinc-200">
                        <th class="h-10 px-4 text-right align-middle font-medium text-zinc-500 text-xs">{{ __('Name') }}</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-zinc-500 text-xs">{{ __('Students') }}</th>
                        <th class="h-10 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $department)
                        <tr class="border-b border-zinc-100 hover:bg-zinc-50/50 transition-colors">
                            <td class="p-4 align-middle text-sm">
                                @if($editingId === $department->id)
                                    <input wire:model="editingName" type="text"
                                           class="flex h-9 w-full rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    @error('editingName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                @else
                                    <span class="font-medium text-zinc-900">{{ $department->name }}</span>
                                @endif
                            </td>
                            <td class="p-4 align-middle text-sm text-zinc-500">{{ $department->students_count }}</td>
                            <td class="p-4 align-middle text-sm">
                                @if($editingId === $department->id)
                                    <div class="flex gap-2 justify-end">
                                        <button wire:click="saveEdit" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('Save') }}</button>
                                        <button wire:click="cancelEdit" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-400 hover:bg-zinc-100 hover:text-zinc-600 px-3 h-9 transition-colors">{{ __('Cancel') }}</button>
                                    </div>
                                @else
                                    <div class="flex gap-2 justify-end">
                                        <button wire:click="startEdit({{ $department->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('Edit') }}</button>
                                        <button wire:click="confirmDelete({{ $department->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 px-3 h-9 transition-colors">{{ __('Delete') }}</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-16 text-zinc-400">{{ __('No classrooms yet') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
