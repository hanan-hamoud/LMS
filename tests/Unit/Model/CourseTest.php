<?php

use App\Models\Course;


test('fillable attributes', function () {
    $course = new Course();
    expect($course->getFillable())->toEqual(['title', 'description', 'category_id', 'instructor_id', 'status']);
});

test('course belongs to category', function () {
    $course = Course::factory()->create();
    expect($course->category())->toBeInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo');
});

test('course belongs to instructor', function () {
    $course = Course::factory()->create();
    expect($course->instructor())->toBeInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo');
});

test('course has many lessons', function () {
    $course = Course::factory()->create();
    expect($course->lessons())->toBeInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany');
});

test('course has many enrollments', function () {
    $course = Course::factory()->create();
    expect($course->enrollments())->toBeInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany');
});

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

#[test]
function it_can_create_a_course()
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
function a_course_belongs_to_an_instructor_and_category()
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

    expect($loadedCourse->instructor->name)->toEqual('Hanan');
    expect($loadedCourse->category->name)->toEqual('Programming');
}