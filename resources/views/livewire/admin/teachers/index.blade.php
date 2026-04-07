<div>
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ __('Teachers') }}</h2>
        <a href="{{ route('admin.teachers.create') }}"
           class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-zinc-900 text-zinc-50 hover:bg-zinc-800 px-4 h-9 transition-colors shrink-0">
            + {{ __('Add Teacher') }}
        </a>
    </div>

    <div class="mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search') }}..."
               class="flex h-9 w-full sm:max-w-sm rounded-md border border-zinc-200 bg-white px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900">
    </div>

    @if($confirmingDeleteId)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg border border-zinc-200 shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-zinc-900 mb-1">{{ __('Confirm Delete') }}</h3>
                <p class="text-sm text-zinc-500 mb-4">{{ __('Are you sure you want to delete this?') }}</p>
                <div class="border-t border-zinc-100 my-4"></div>
                <div class="flex gap-3 justify-end">
                    <button wire:click="cancelDelete" class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('No, Cancel') }}</button>
                    <button wire:click="deleteTeacher" class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 px-4 h-9 transition-colors">{{ __('Yes, Delete') }}</button>
                </div>
            </div>
        </div>
    @endif

    <div class="rounded-lg border border-zinc-200 bg-white shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200">
                    <th class="h-10 px-4 text-right align-middle font-medium text-zinc-500 text-xs">{{ __('Name') }}</th>
                    <th class="h-10 px-4 text-right align-middle font-medium text-zinc-500 text-xs">{{ __('Username') }}</th>
                    <th class="h-10 px-4 text-right align-middle font-medium text-zinc-500 text-xs">{{ __('Posts') }}</th>
                    <th class="h-10 px-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr class="border-b border-zinc-100 hover:bg-zinc-50/50 transition-colors">
                        <td class="p-4 align-middle text-sm font-medium text-zinc-900">{{ $teacher->name }}</td>
                        <td class="p-4 align-middle text-sm text-zinc-500">{{ $teacher->username }}</td>
                        <td class="p-4 align-middle text-sm text-zinc-700">{{ $teacher->posts_count }}</td>
                        <td class="p-4 align-middle text-sm">
                            <div class="flex items-center gap-3 justify-end">
                                <a href="{{ route('admin.teachers.posts', $teacher) }}"
                                   class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('Posts') }}</a>
                                <a href="{{ route('admin.teachers.edit', $teacher) }}"
                                   class="inline-flex items-center justify-center rounded-md text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 px-3 h-9 transition-colors">{{ __('Edit') }}</a>
                                <button wire:click="confirmDelete({{ $teacher->id }})"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 px-3 h-9 transition-colors">{{ __('Delete') }}</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-16 text-zinc-400">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ __('No classrooms yet') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-zinc-100 flex items-center justify-end">
            {{ $teachers->links() }}
        </div>
    </div>
</div>
