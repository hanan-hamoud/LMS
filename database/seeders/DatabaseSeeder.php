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
        User::factory(10)->create();

        Instructor::factory(5)->create();

        CourseCategory::factory(5)->create()->each(function ($category) {
           
            Course::factory(3)->create([
                'category_id' => $category->id,
            ])->each(function ($course) {
              
                Lesson::factory(5)->create([
                    'course_id' => $course->id,
                ]);
            });
        });

        $users = User::all();
        $courses = Course::all();

        foreach ($users as $user) {
            $userCourses = $courses->random(rand(1, 3)); 
            foreach ($userCourses as $course) {
                Enrollment::factory()->create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ]);
            }
        }
    }
}
