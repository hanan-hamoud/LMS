<?php

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\CourseCategory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('it has the correct fillable attributes', function () {
    $enrollment = new Enrollment();

    expect($enrollment->getFillable())->toEqual([
        'user_id',
        'course_id',
        'enrolled_at',
        'status',
    ]);
});

test('it belongs to a user', function () {
    $enrollment = Enrollment::factory()->create();

    expect($enrollment->user())->toBeInstanceOf(BelongsTo::class);
});

test('it belongs to a course', function () {
    $enrollment = Enrollment::factory()->create();

    expect($enrollment->course())->toBeInstanceOf(BelongsTo::class);
});

test('status is cast to boolean', function () {
    $enrollment = Enrollment::factory()->create(['status' => true]);

    expect($enrollment->status)->toBeBool()->toBeTrue();
});

test('enrolled_at is cast to datetime', function () {
    $date = now();
    $enrollment = Enrollment::factory()->create(['enrolled_at' => $date]);

    expect($enrollment->enrolled_at)->toBeInstanceOf(Carbon::class);
    expect($enrollment->enrolled_at->format('Y-m-d H:i'))->toEqual($date->format('Y-m-d H:i'));
});

test('it can be created with valid data', function () {
    $user = User::create([
        'name' => 'Hanan',
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

    expect($enrollment)->toBeInstanceOf(Enrollment::class);
    expect($enrollment->user_id)->toEqual($user->id);
    expect($enrollment->course_id)->toEqual($course->id);

    $this->assertDatabaseHas('enrollments', [
        'user_id' => $user->id,
        'course_id' => $course->id,
        'status' => true,
    ]);
});
