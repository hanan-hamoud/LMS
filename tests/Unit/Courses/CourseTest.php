<?php

namespace Tests\Unit;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes()
    {
        $course = new Course();
        $this->assertEquals(['title', 'description', 'category_id', 'instructor_id', 'status'], $course->getFillable());
    }

    public function test_course_belongs_to_category()
    {
        $course = Course::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $course->category());
    }

    public function test_course_belongs_to_instructor()
    {
        $course = Course::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $course->instructor());
    }

    public function test_course_has_many_lessons()
    {
        $course = Course::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $course->lessons());
    }

    public function test_course_has_many_enrollments()
    {
        $course = Course::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $course->enrollments());
    }
}

