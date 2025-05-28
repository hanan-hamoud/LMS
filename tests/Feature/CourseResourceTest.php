<?php

use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Course;
use Livewire\Livewire;
use App\Filament\Resources\CourseResource\Pages\CreateCourse;
use App\Filament\Resources\CourseResource\Pages\EditCourse;
use App\Filament\Resources\CourseResource\Pages\ListCourses;
use Filament\Actions\DeleteAction;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function actingAsAdmin(): User
{
    $admin = User::factory()->create();
    actingAs($admin);
    return $admin;
}

it('can list courses', function () {
    $courses = Course::factory()->count(10)->create();

    Livewire::test(ListCourses::class)
        ->assertCanSeeTableRecords($courses);
});

it('can create a new course', function () {
    actingAsAdmin();

    $category = \App\Models\CourseCategory::factory()->create();
    $instructor = \App\Models\Instructor::factory()->create();

    Livewire::test(CreateCourse::class)
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
            'title' => 'Programming PHP',
            'description' => 'Intro programming PHP',
            'status' => false,
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $course->refresh();

    expect($course->title)->toBe('Programming PHP');
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
