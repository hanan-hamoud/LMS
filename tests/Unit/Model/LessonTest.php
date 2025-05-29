<?php

use App\Models\Lesson;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('lesson has correct fillable attributes', function () {
    $lesson = new Lesson();

    expect($lesson->getFillable())->toEqual([
        'course_id',
        'title',
        'description',
        'video_url',
        'is_preview',
        'order',
    ]);
});

test('lesson belongs to a course', function () {
    $lesson = Lesson::factory()->create();

    expect($lesson->course())->toBeInstanceOf(BelongsTo::class);
});

test('lesson model relationship returns correct course instance', function () {
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

    expect($lesson->course)->toBeInstanceOf(Course::class);
    expect($lesson->course->id)->toEqual($course->id);
});

test('lesson can be created and saved in database', function () {
    $instructor = Instructor::factory()->create();
    $category = CourseCategory::factory()->create();

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
        'order' => 1,
    ]);

    expect($lesson->title)->toEqual('Intro to PHP');

    expect($lesson->course_id)->toEqual($course->id);

    $this->assertDatabaseHas('lessons', [
        'title' => 'Intro to PHP',
        'course_id' => $course->id,
        'is_preview' => true,
        'order' => 1,
    ]);
});
