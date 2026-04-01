<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Dashboard') }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Students --}}
        <a href="{{ route('admin.students.index') }}" class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ $studentsCount }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('Total Students') }}</p>
            </div>
        </a>

        {{-- Teachers --}}
        <a href="{{ route('admin.teachers.index') }}" class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ $teachersCount }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('Total Teachers') }}</p>
            </div>
        </a>

        {{-- Classrooms --}}
        <a href="{{ route('admin.classrooms.index') }}" class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ $classroomsCount }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('Total Classrooms') }}</p>
            </div>
        </a>
    </div>
</div>
