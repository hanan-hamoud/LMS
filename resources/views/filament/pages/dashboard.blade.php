<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white rounded-xl shadow text-center">
            <h2 class="text-lg font-bold text-gray-700"> number courses</h2>
            <p class="text-4xl font-bold text-primary-600 mt-2">{{ $courseCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center">
            <h2 class="text-lg font-bold text-gray-700"> number lesson </h2>
            <p class="text-4xl font-bold text-primary-600 mt-2">{{ $lessonCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow text-center">
            <h2 class="text-lg font-bold text-gray-700">number enrollments </h2>
            <p class="text-4xl font-bold text-primary-600 mt-2">{{ $enrollmentCount }}</p>
        </div>
    </div>
</x-filament::page>
