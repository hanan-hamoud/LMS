<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-white rounded-xl shadow">
            <h2 class="text-lg font-bold">📚 الكورسات</h2>
            <p class="text-3xl mt-2 text-primary-600">{{ $courseCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow">
            <h2 class="text-lg font-bold">📖 الدروس</h2>
            <p class="text-3xl mt-2 text-primary-600">{{ $lessonCount }}</p>
        </div>

        <div class="p-6 bg-white rounded-xl shadow">
            <h2 class="text-lg font-bold">👥 التسجيلات</h2>
            <p class="text-3xl mt-2 text-primary-600">{{ $enrollmentCount }}</p>
        </div>
    </div>
</x-filament::page>
