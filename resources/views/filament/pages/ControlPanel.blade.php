<x-filament::page>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-10">
        {{-- Total Courses --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex items-center justify-between border border-gray-200 dark:border-gray-700 min-h-[110px]">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400"> {{ __('total_courses') }} </p>
                <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $courseCount }}</h3>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900 p-2.5 rounded-full">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 4h16v2H4zm0 6h10v2H4zm0 6h16v2H4z" />
                </svg>
            </div>
        </div>

        {{-- Total Lessons --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex items-center justify-between border border-gray-200 dark:border-gray-700 min-h-[110px]">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('total_lessons') }} </p>
                <h3 class="text-2xl font-bold text-indigo-600 mt-1">{{ $lessonCount }}</h3>
            </div>
            <div class="bg-indigo-100 dark:bg-indigo-900 p-2.5 rounded-full">
                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 4a1 1 0 000 2h18a1 1 0 100-2H3zm0 6a1 1 0 011-1h16a1 1 0 110 2H4a1 1 0 01-1-1zm0 6a1 1 0 100 2h18a1 1 0 100-2H3z"/>
                </svg>
            </div>
        </div>

        {{-- Total Enrollments --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex items-center justify-between border border-gray-200 dark:border-gray-700 min-h-[110px]">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('total_enrollments') }}</p>
                <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $enrollmentCount }}</h3>
            </div>
            <div class="bg-green-100 dark:bg-green-900 p-2.5 rounded-full">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex items-center justify-between border border-gray-200 dark:border-gray-700 min-h-[110px]">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('total_users') }}</p>
                <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ $userCount }}</h3>
            </div>
            <div class="bg-purple-100 dark:bg-purple-900 p-2.5 rounded-full">
                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4S8 5.79 8 8s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
        </div>
    </div>

{{-- Top Courses Section --}}
<div class="mb-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">{{ __('top_popular_courses') }}</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($popularCourses as $course)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex items-center justify-between border border-gray-200 dark:border-gray-700 min-h-[110px]">
                <div class="max-w-[75%]">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="{{ $course->title }}">
                        {{ $course->title }}
                    </p>
                    <h3 class="text-xl font-bold text-blue-600 mt-1">
                        {{ $course->enrollments_count }} {{ __('total_users') }}
                    </h3>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-2.5 rounded-full shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14.5h-2v-2h2v2zm0-4h-2V7h2v5.5z" />
                    </svg>
                </div>
            </div>
        @endforeach
    </div>
</div>


</x-filament::page>
