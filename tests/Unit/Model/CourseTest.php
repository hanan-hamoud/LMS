<?php

use App\Models\Course;
use App\Models\Instructor;
use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('it has the correct fillable attributes', function () {
    $course = new Course();

    expect($course->getFillable())->toEqual([
        'title',
        'description',
        'category_id',
        'instructor_id',
        'status',
    ]);
});

test('it belongs to a category', function () {
    $course = Course::factory()->create();

    expect($course->category())->toBeInstanceOf(BelongsTo::class);
});

test('it belongs to an instructor', function () {
    $course = Course::factory()->create();

    expect($course->instructor())->toBeInstanceOf(BelongsTo::class);
});

test('it has many lessons', function () {
    $course = Course::factory()->create();

    expect($course->lessons())->toBeInstanceOf(HasMany::class);
});

test('it has many enrollments', function () {
    $course = Course::factory()->create();

    expect($course->enrollments())->toBeInstanceOf(HasMany::class);
});

test('it can be created with valid data', function () {
    $instructor = Instructor::create([
        'name' => 'Hanan',
        'email' => 'hanan2001hamoud@gmail.com',
        'bio' => 'She is a programmer',
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

    expect($course)->toBeInstanceOf(Course::class);

    expect($course->title)->toEqual('PHP Course');

    $this->assertDatabaseHas('courses', [
        'title' => 'PHP Course',
        'instructor_id' => $instructor->id,
        'category_id' => $category->id,
        'status' => true,
    ]);
});

test('it belongs to both instructor and category correctly', function () {
    $instructor = Instructor::create([
        'name' => 'Hanan',
        'email' => 'hanan2001hamoud@gmail.com',
        'bio' => 'She is a programmer',
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

    $loadedCourse = Course::with(['instructor', 'category'])->find($course->id);

    expect($loadedCourse->instructor->name)->toEqual('Hanan');
    expect($loadedCourse->category->name)->toEqual('Programming');
});
