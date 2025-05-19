<?php

namespace Tests\Unit;

use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes()
    {
        $lesson = new Lesson();
        $this->assertEquals([
            'course_id',
            'title',
            'description',
            'video_url',
            'is_preview',
            'order',
        ], $lesson->getFillable());
        
    }

    public function test_lesson_belongs_to_course()
    {
        $lesson = Lesson::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $lesson->course());
    }
}
