<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Instructor;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء 10 مستخدمين
        User::factory(10)->create();

        // إنشاء 5 معلمين
        Instructor::factory(5)->create();

        // إنشاء 5 فئات دورات
        CourseCategory::factory(5)->create()->each(function ($category) {
            // لكل فئة، أنشئ 3 دورات
            Course::factory(3)->create([
                'category_id' => $category->id,
            ])->each(function ($course) {
                // لكل دورة، أنشئ دروس
                Lesson::factory(5)->create([
                    'course_id' => $course->id,
                ]);
            });
        });

        // تعيين مستخدمين لدورات عشوائيًا (enrollments)
        $users = User::all();
        $courses = Course::all();

        foreach ($users as $user) {
            $userCourses = $courses->random(rand(1, 3)); // كل مستخدم يلتحق بـ 1 إلى 3 دورات
            foreach ($userCourses as $course) {
                Enrollment::factory()->create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ]);
            }
        }
    }
}
