<?php

use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Course;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use App\Filament\Resources\CourseResource\Pages\CreateCourse;
use App\Filament\Resources\CourseResource\Pages\EditCourse;
use App\Filament\Resources\CourseResource\Pages\ListCourses;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function actingAsAdmin(): User
{
    $admin = User::factory()->create();
    $this->actingAs($admin);
    return $admin;
}
it('can list courses', function () {
    $courses = Course::factory()->count(10)->create();

    Livewire::test(\App\Filament\Resources\CourseResource\Pages\ListCourses::class)
        ->assertCanSeeTableRecords($courses);
});

it('can create a new course', function () {
    actingAsAdmin();
    $category = \App\Models\CourseCategory::factory()->create();
    $instructor = \App\Models\Instructor::factory()->create();

    Livewire::test(\App\Filament\Resources\CourseResource\Pages\CreateCourse::class)
        ->fillForm([
            'title' => 'Programming C++',
            'description' => 'Intro programming',
            'status' => true,
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('courses', [
        'title' => 'Programming C++',
        'description' => 'Intro programming',
        'status' => true,
        'category_id' => $category->id,
        'instructor_id' => $instructor->id,
    ]);
});

it('can update a course', function () {
    actingAsAdmin();
    $category = \App\Models\CourseCategory::factory()->create();
    $instructor = \App\Models\Instructor::factory()->create();
    $course = Course::create([
        'title' => 'Programming C++',
            'description' => 'Intro programming',
            'status' => true,
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
    ]);

    Livewire::test(EditCourse::class, ['record' => $course->getKey()])
        ->fillForm([
            'title' => 'Programming php',
            'description' => 'Intro programming p',
            'status' => false,
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $course->refresh();

    expect($course->title)->toBe('Programming php');

    expect((bool) $course->status)->toBeFalse();
});

it('can delete a course', function () {
    actingAsAdmin();

    $course = Course::factory()->create();

    Livewire::test(EditCourse::class, ['record' => $course->getKey()])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('courses', [
        'id' => $course->id,
    ]);
});