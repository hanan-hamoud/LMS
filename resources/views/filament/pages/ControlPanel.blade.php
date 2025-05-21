<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h2 class="text-lg font-semibold" style="color: #111827;">Total Courses</h2>
            <p class="text-5xl font-extrabold mt-3" style="color: #3B82F6;">{{ $courseCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h2 class="text-lg font-semibold" style="color: #111827;">Total Lessons</h2>
            <p class="text-5xl font-extrabold mt-3" style="color: #2563EB;">{{ $lessonCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h2 class="text-lg font-semibold" style="color: #111827;">Total Enrollments</h2>
            <p class="text-5xl font-extrabold mt-3" style="color: #3B82F6;">{{ $enrollmentCount }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h3 class="text-md font-semibold" style="color: #111827;">Users</h3>
            <p class="text-4xl font-bold mt-2" style="color: #2563EB;">{{ $userCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h3 class="text-md font-semibold" style="color: #111827;">Verified Users</h3>
            <p class="text-4xl font-bold mt-2" style="color: #2563EB;">{{ $verifiedUsersCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h3 class="text-md font-semibold" style="color: #111827;">Active Courses</h3>
            <p class="text-4xl font-bold mt-2" style="color: #3B82F6;">{{ $activeCourseCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h3 class="text-md font-semibold" style="color: #111827;">Active Enrollments</h3>
            <p class="text-4xl font-bold mt-2" style="color: #3B82F6;">{{ $activeEnrollmentCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h3 class="text-md font-semibold" style="color: #111827;">Categories</h3>
            <p class="text-4xl font-bold mt-2" style="color: #2563EB;">{{ $categoryCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h3 class="text-md font-semibold" style="color: #111827;">Instructors</h3>
            <p class="text-4xl font-bold mt-2" style="color: #2563EB;">{{ $instructorCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center border" style="border-color: #D1D5DB;">
            <h3 class="text-md font-semibold" style="color: #111827;">Active Instructors</h3>
            <p class="text-4xl font-bold mt-2" style="color: #2563EB;">{{ $activeInstructorCount }}</p>
        </div>
    </div>

   <div class="mb-10">
    <h2 class="text-2xl font-bold mb-6" style="color: #111827;">Top 5 Popular Courses</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 justify-center">
        @foreach($popularCourses as $course)
            <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between border" style="border-color: #D1D5DB; max-width: 400px; margin: auto;">
                <h3 class="text-lg font-semibold mb-1 truncate" title="{{ $course->title }}" style="color: #2563EB;">
                    {{ $course->title }}
                </h3>
                <p class="text-gray-700 text-xs mb-3">
                    Enrollments: <span class="font-bold" style="color: #3B82F6;">{{ $course->enrollments_count }}</span>
                </p>
            </div>
        @endforeach
    </div>
</div>

</x-filament::page>
