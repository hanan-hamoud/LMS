<?php

use App\Filament\Resources\CourseCategoryResource;
use App\Models\CourseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Livewire\livewire;
use function Pest\Laravel\actingAs;
use App\Filament\Resources\CourseCategoryResource\Pages\CreateCourseCategory;
use App\Filament\Resources\CourseCategoryResource\Pages\EditCourseCategory;
use App\Filament\Resources\CourseCategoryResource\Pages\ListCourseCategories;
it('can render create form', function () {
    livewire(CreateCourseCategory::class)
        ->assertFormExists();
});

it('has correct form fields', function () {
    livewire(CreateCourseCategory::class)
        ->assertFormFieldExists('name_en')
        ->assertFormFieldExists('name_ar')
        ->assertFormFieldExists('status');
});

it('can validate form input', function () {
    livewire(CreateCourseCategory::class)
        ->fillForm([
            'name_en' => '',
            'name_ar' => '',
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name_en' => 'required',
            'name_ar' => 'required',
            'status' => 'required',
        ]);
});

it('can create a new course category', function () {
    livewire(CreateCourseCategory::class)
        ->fillForm([
            'name_en' => 'English Name',
            'name_ar' => 'اسم عربي',
            'status' => true,
        ])
        ->call('create') 
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('course_categories', [
        'slug' => 'english-name',
        'status' => 1,
    ]);
});


it('can list course categories', function () {
    $admin = createAdminUser();
    actingAs($admin);
    $categories = CourseCategory::factory()->count(3)->create();

    livewire(ListCourseCategories::class)
        ->assertCanSeeTableRecords($categories);
});

it('can render edit form with correct data', function () {
    $admin = createAdminUser();
    actingAs($admin);
    $category = CourseCategory::factory()->create([
        'name' => ['en' => 'English Name', 'ar' => 'الاسم العربي'],
    ]);

    livewire(EditCourseCategory::class, [
        'record' => $category->id,
    ])
        ->assertFormSet([
            'name_en' => $category->name['en'] ?? '',
            'name_ar' => $category->name['ar'] ?? '',
            'status' => $category->status,
        ]);
});

it('can update a course category', function () {
    $admin = createAdminUser();
    actingAs($admin);
    $category = CourseCategory::factory()->create([
        'name' => ['en' => 'Old Name', 'ar' => 'اسم قديم'],
        'status' => true,
    ]);

    livewire(EditCourseCategory::class, [
        'record' => $category->id,
    ])
        ->fillForm([
            'name_en' => 'Updated Name',
            'name_ar' => 'اسم محدث',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $category->refresh();
    expect($category->name['en'])->toBe('Updated Name');
    expect($category->name['ar'])->toBe('اسم محدث');
    expect((bool) $category->status)->toBeFalse();
});

it('can delete a course category', function () {
    $admin = createAdminUser();
    actingAs($admin);
    $category = CourseCategory::factory()->create();

    livewire(EditCourseCategory::class, [
        'record' => $category->id,
    ])
        ->callAction('delete')
        ->assertHasNoActionErrors();

    expect(CourseCategory::find($category->id))->toBeNull();
});
