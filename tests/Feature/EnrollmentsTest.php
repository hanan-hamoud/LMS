<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Instructor;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\Enrollment;

class EnrollmentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_enrollment()
    {
        $user = User::create([
            'name' => 'hanan',
            'email' => 'hi123h@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $instructor = Instructor::create([
            'name' => 'Hanan',
            'email' => 'hanan2001hamoud3@gmail.com',
            'bio' => 'she is a programmer',
            'photo' => 'photo.png',
            'status' => true,
        ]);

        $category = CourseCategory::create([
            'name' => 'Programming',
            'slug' => 'programming',
            'status' => true,
        ]);

        $course = Course::create([
            'title' => 'PHP Course',
            'description' => 'Start from scratch',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'status' => true,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => true,
        ]);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => true,
        ]);
    }

    /** @test */


}
