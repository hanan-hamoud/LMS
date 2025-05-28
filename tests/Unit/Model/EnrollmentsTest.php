<?php

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Carbon;


test('fillable attributes', function () {
    $enrollment = new Enrollment();
    expect($enrollment->getFillable())->toEqual(['user_id', 'course_id', 'enrolled_at', 'status']);
});

test('enrollment belongs to user', function () {
    $enrollment = Enrollment::factory()->create();
    expect($enrollment->user())->toBeInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo');
});

test('enrollment belongs to course', function () {
    $enrollment = Enrollment::factory()->create();
    expect($enrollment->course())->toBeInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo');
});

test('status is cast to boolean', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();

    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'enrolled_at' => now(),
        'status' => 1,
    ]);

    expect($enrollment->status)->toBeBool();
    expect($enrollment->status)->toBeTrue();
});

test('enrolled at is cast to datetime', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();

    $date = now();

    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'enrolled_at' => $date->toDateTimeString(),
        'status' => true,
    ]);

    expect($enrollment->enrolled_at)->toBeInstanceOf(Carbon::class);
    expect($enrollment->enrolled_at->format('Y-m-d H:i'))->toEqual($date->format('Y-m-d H:i'));
});


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

#[test]
function it_can_create_an_enrollment()
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