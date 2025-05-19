<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Instructor;
use App\Models\Lesson;

class LessonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_lesson()
    {
        $instructor = Instructor::create([
            'name' => 'Hanan',
            'email' => 'hanan2001hamoud2@gmail.com',
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

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'Intro to PHP',
            'description' => 'This is the first lesson',
            'video_url' => 'https://example.com/video.mp4',
            'is_preview' => true,  
            'order' => 1            
        ]);

        $this->assertDatabaseHas('lessons', [
            'title' => 'Intro to PHP',
            'course_id' => $course->id,
            'is_preview' => true,
            'order' => 1
        ]);
    }

     /** @test */
    public function lesson_belongs_to_course()
    {
        $instructor = Instructor::factory()->create();
        $category = CourseCategory::factory()->create();

        $course = Course::create([
            'title' => 'Intro to PHP',
            'description' => 'Basics',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'status' => true,
        ]);

        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => 'First Lesson',
            'description' => 'Lesson description',
            'video_url' => 'https://video.com/lesson1',
            'is_preview' => true,
            'order' => 1,
        ]);

        $this->assertInstanceOf(Course::class, $lesson->course);
        $this->assertEquals($course->id, $lesson->course->id);
    }
}

