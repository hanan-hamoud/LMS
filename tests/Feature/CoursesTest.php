<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Instructor;
use App\Models\CourseCategory;
use App\Models\Course;
use PHPUnit\Framework\Attributes\Test;
class CoursesTest extends TestCase
{
    use RefreshDatabase;

    #[test]
    public function it_can_create_a_course()
    {
        $instructor = Instructor::create([
            'name' => 'Hanan',
            'email' => 'hanan2001hamoud@gmail.com',
            'bio' => 'she is a programmer',
            'photo' => 'photo.png',
            'status' => true,
        ]);

        $category = CourseCategory::create([
            'name' => 'Programming',
            'slug' => 'programming',
        ]);
        

        $course = Course::create([
            'title' => 'PHP Course',
            'description' => 'Start from scratch',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'status' => true,
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'PHP Course',
            'instructor_id' => $instructor->id,
            'category_id' => $category->id,
            'status' => true,
        ]);
    }

   #[test]
public function a_course_belongs_to_an_instructor_and_category()
{
    $instructor = \App\Models\Instructor::create([
        'name' => 'Hanan',
        'email' => 'hanan2001hamoud@gmail.com',
        'bio' => 'she is a programmer',
        'photo' => 'photo.png',
        'status' => true,
    ]);

    $category = \App\Models\CourseCategory::create([
        'name' => 'Programming',
        'slug' => 'programming',
        'status' => true,
    ]);

    $course = \App\Models\Course::create([
        'title' => 'PHP Course',
        'description' => 'Start from scratch',
        'category_id' => $category->id,
        'instructor_id' => $instructor->id,
        'status' => true,
    ]);

    $loadedCourse = \App\Models\Course::with(['instructor', 'category'])->find($course->id);

    $this->assertEquals('Hanan', $loadedCourse->instructor->name);
    $this->assertEquals('Programming', $loadedCourse->category->name);
}

}
