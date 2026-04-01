<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Departments') }}</h2>

    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Confirm Delete') }}</h3>
                <p class="text-gray-500 text-sm mb-4">{{ __('Are you sure you want to delete this?') }}</p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">{{ __('No, Cancel') }}</button>
                    <button wire:click="deleteDepartment" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">{{ __('Yes, Delete') }}</button>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Add new --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">{{ __('Add Department') }}</h3>
            <form wire:submit="create" class="flex gap-3">
                <input wire:model="newName" type="text" placeholder="{{ __('Name') }}"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('newName') border-red-400 @enderror">
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors" wire:loading.attr="disabled">
                    {{ __('Add') }}
                </button>
            </form>
            @error('newName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- List --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('Name') }}</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('Students') }}</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($departments as $department)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                @if($editingId === $department->id)
                                    <input wire:model="editingName" type="text"
                                           class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 w-full">
                                    @error('editingName') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                @else
                                    <span class="font-medium text-gray-900">{{ $department->name }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $department->students_count }}</td>
                            <td class="px-5 py-3">
                                @if($editingId === $department->id)
                                    <div class="flex gap-2 justify-end">
                                        <button wire:click="saveEdit" class="text-green-600 hover:text-green-800 text-xs font-medium">{{ __('Save') }}</button>
                                        <button wire:click="cancelEdit" class="text-gray-400 hover:text-gray-600 text-xs">{{ __('Cancel') }}</button>
                                    </div>
                                @else
                                    <div class="flex gap-2 justify-end">
                                        <button wire:click="startEdit({{ $department->id }})" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">{{ __('Edit') }}</button>
                                        <button wire:click="confirmDelete({{ $department->id }})" class="text-red-500 hover:text-red-700 text-xs font-medium">{{ __('Delete') }}</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-gray-400">{{ __('No classrooms yet') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
