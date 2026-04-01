<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('Students') }}</h2>
        <a href="{{ route('admin.students.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            + {{ __('Add Student') }}
        </a>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search') }}..."
               class="w-full max-w-sm border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>

    {{-- Block modal --}}
    @if($blockingUserId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Block Message') }}</h3>
                <input wire:model="blockedMessage" type="text"
                       placeholder="{{ __('Block Message') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-1 focus:outline-none focus:ring-2 focus:ring-red-400">
                @error('blockedMessage')
                    <p class="text-sm text-red-600 mb-3">{{ $message }}</p>
                @enderror
                <div class="flex gap-3 justify-end mt-4">
                    <button wire:click="cancelBlock" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">{{ __('Cancel') }}</button>
                    <button wire:click="confirmBlock" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">{{ __('Blocked') }}</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete modal --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Confirm Delete') }}</h3>
                <p class="text-gray-500 text-sm mb-4">{{ __('Are you sure you want to delete this?') }}</p>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">{{ __('No, Cancel') }}</button>
                    <button wire:click="deleteStudent" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">{{ __('Yes, Delete') }}</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Name') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Username') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Department') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Stage') }}</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Active') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $student->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $student->username }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $student->department?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $student->stage }}</td>
                        <td class="px-5 py-3">
                            <button wire:click="toggleActive({{ $student->id }})"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $student->is_active ? 'bg-green-500' : 'bg-red-400' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $student->is_active ? 'translate-x-6 rtl:-translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3 justify-end">
                                <a href="{{ route('admin.students.edit', $student) }}"
                                   class="text-indigo-600 hover:text-indigo-800 font-medium text-xs">{{ __('Edit') }}</a>
                                <button wire:click="confirmDelete({{ $student->id }})"
                                        class="text-red-500 hover:text-red-700 font-medium text-xs">{{ __('Delete') }}</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400">{{ __('No classrooms yet') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $students->links() }}
        </div>
    </div>
</div>
