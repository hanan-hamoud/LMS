<?php

use App\Filament\Resources\CourseCategoryResource;
use App\Models\CourseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Livewire\livewire;
use function Pest\Laravel\actingAs;

it('can render create form', function () {
    livewire(CourseCategoryResource\Pages\CreateCourseCategory::class)
        ->assertFormExists();
});

it('has correct form fields', function () {
    livewire(CourseCategoryResource\Pages\CreateCourseCategory::class)
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('status');
});

it('can validate form input', function () {
    livewire(CourseCategoryResource\Pages\CreateCourseCategory::class)
        ->fillForm([
            'name' => '',
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'status' => 'required',
        ]);
});

it('can create a new course category', function () {
    $admin = createAdminUser();
    actingAs($admin);
    livewire(CourseCategoryResource\Pages\CreateCourseCategory::class)
        ->fillForm([
            'name' => 'New Category',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(CourseCategory::where('name', 'New Category')->exists())->toBeTrue();
});

it('can list course categories', function () {
    $admin = createAdminUser();
    actingAs($admin);
    $categories = CourseCategory::factory()->count(3)->create();

    livewire(CourseCategoryResource\Pages\ListCourseCategories::class)
        ->assertCanSeeTableRecords($categories);
});

it('can render edit form with correct data', function () {
    $admin = createAdminUser();
    actingAs($admin);
    $category = CourseCategory::factory()->create();

    livewire(CourseCategoryResource\Pages\EditCourseCategory::class, [
        'record' => $category->id,
    ])
        ->assertFormSet([
            'name' => $category->name,
            'status' => $category->status,
        ]);
});

it('can update a course category', function () {
    $admin = createAdminUser();
    actingAs($admin);
    $category = CourseCategory::factory()->create(['name' => 'Old Name', 'status' => true]);

    livewire(CourseCategoryResource\Pages\EditCourseCategory::class, [
        'record' => $category->id,
    ])
        ->fillForm([
            'name' => 'Updated Name',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(CourseCategory::find($category->id))->toMatchArray([
        'name' => 'Updated Name',
        'status' => false,
    ]);
});

it('can delete a course category', function () {
    $admin = createAdminUser();
    actingAs($admin);
    $category = CourseCategory::factory()->create();

    livewire(CourseCategoryResource\Pages\EditCourseCategory::class, [
        'record' => $category->id,
    ])
        ->callAction('delete')
        ->assertHasNoActionErrors();

    expect(CourseCategory::find($category->id))->toBeNull();
});
