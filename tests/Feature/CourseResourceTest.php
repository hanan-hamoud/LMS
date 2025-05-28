<?php

use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Course;
use Livewire\Livewire;
use App\Filament\Resources\CourseResource\Pages\CreateCourse;
use App\Filament\Resources\CourseResource\Pages\EditCourse;
use App\Filament\Resources\CourseResource\Pages\ListCourses;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);



it('can list courses', function () {
    $courses = Course::factory()->count(10)->create();

    Livewire::test(ListCourses::class)
        ->assertCanSeeTableRecords($courses);
});

it('can create a new course', function () {
    $admin = createAdminUser();
    actingAs($admin);

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
    $admin = createAdminUser();
    actingAs($admin);


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
    $admin = createAdminUser();
    actingAs($admin);


    $course = Course::factory()->create();

    Livewire::test(EditCourse::class, ['record' => $course->getKey()])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('courses', [
        'id' => $course->id,
    ]);
});

it('fails to create course with invalid data', function () {
    $admin = createAdminUser();
    actingAs($admin);

    Livewire::test(CreateCourse::class)
        ->fillForm([
            'title' => '', 
            'description' => '', 
            'status' => null,
            'category_id' => 'invalid', 
            'instructor_id' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'numeric',
            'instructor_id' => 'required',
        ]);
});


it('requires title, description, category_id, and instructor_id when creating', function () {
    $admin = createAdminUser();
    actingAs($admin);


    Livewire::test(CreateCourse::class)
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'instructor_id' => 'required',
        ]);
});
it('can sort by category_id and instructor_id', function () {
    $admin = createAdminUser();
    actingAs($admin);


    $courseA = Course::factory()->create(['category_id' => 1]);
    $courseB = Course::factory()->create(['category_id' => 2]);

    Livewire::test(ListCourses::class)
        ->sortTable('category_id', 'asc')
        ->assertCanSeeTableRecords([$courseA, $courseB]);
});
it('displays all form fields when creating', function () {
    $admin = createAdminUser();
    actingAs($admin);


    Livewire::test(CreateCourse::class)
        ->assertFormFieldExists('title')
        ->assertFormFieldExists('description')
        ->assertFormFieldExists('category_id')
        ->assertFormFieldExists('instructor_id')
        ->assertFormFieldExists('status');
});


it('can bulk delete courses', function () {
    $admin = createAdminUser();
    actingAs($admin);


    $courses = Course::factory()->count(3)->create();

    Livewire::test(ListCourses::class)
        ->callTableBulkAction(DeleteBulkAction::make()->getName(), $courses->pluck('id')->toArray())
        ->assertHasNoTableBulkActionErrors();

    foreach ($courses as $course) {
        $this->assertSoftDeleted($course);
    }
});

