<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('Classrooms') }}</h2>
        <a href="{{ route('admin.classrooms.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            + {{ __('Add Classroom') }}
        </a>
    </div>

    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Confirm Delete') }}</h3>
                <p class="text-gray-500 text-sm mb-4">{{ __('Are you sure you want to delete this?') }}</p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">{{ __('No, Cancel') }}</button>
                    <button wire:click="deleteClassroom" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">{{ __('Yes, Delete') }}</button>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('Name') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('Teachers') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">{{ __('Students') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($classrooms as $classroom)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $classroom->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $classroom->teachers_count }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $classroom->students_count }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3 justify-end">
                                <a href="{{ route('admin.classrooms.edit', $classroom) }}"
                                   class="text-indigo-600 hover:text-indigo-800 font-medium text-xs">{{ __('Edit') }}</a>
                                <button wire:click="confirmDelete({{ $classroom->id }})"
                                        class="text-red-500 hover:text-red-700 font-medium text-xs">{{ __('Delete') }}</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-400">{{ __('No classrooms yet') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $classrooms->links() }}
        </div>
    </div>
</div>
