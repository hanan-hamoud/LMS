<?php

use function Pest\Laravel\actingAs;
use \Tests\Feature\ListCourseCategory;
use App\Models\User;
use App\Models\CourseCategory;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use App\Filament\Resources\CourseCategoryResource\Pages\CreateCourseCategory;
use App\Filament\Resources\CourseCategoryResource\Pages\EditCourseCategory;
use App\Filament\Resources\CourseCategoryResource\Pages\ListCourseCategories;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function actingAsAdmin(): User
{
    $admin = User::factory()->create();
    $this->actingAs($admin);
    return $admin;
}

function it_can_list_course_categories()
{
    $courseCategory = CourseCategory::factory()->count(10)->create();

    Livewire::test(ListCourseCategory::class)
        ->assertCanSeeTableRecords($courseCategory);
}

it('can create a new course category', function () {
    actingAsAdmin();

    Livewire::test(CreateCourseCategory::class)
        ->fillForm([
            'name' => 'Programming',
            'slug' => 'programming',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('course_categories', [
        'name' => 'Programming',
        'slug' => 'programming',
        'status' => true,
    ]);
});

it('can update a course category', function () {
    actingAsAdmin();

    $category = CourseCategory::create([
        'name' => 'Design',
        'slug' => 'design',
        'status' => true,
    ]);

    Livewire::test(EditCourseCategory::class, ['record' => $category->getKey()])
        ->fillForm([
            'name' => 'Graphic Design',
            'slug' => 'graphic-design',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $category->refresh();

    expect($category->name)->toBe('Graphic Design');
    expect((bool) $category->status)->toBeFalse();
});

it('can delete a course category', function () {
    actingAsAdmin();

    $category = CourseCategory::factory()->create();

    Livewire::test(EditCourseCategory::class, ['record' => $category->getKey()])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('course_categories', [
        'id' => $category->id,
    ]);
});

it('fails to create with invalid data', function () {
    actingAsAdmin();

    Livewire::test(CreateCourseCategory::class)
        ->fillForm([
            'name' => '', // اسم فاضي
            'slug' => '', // سلاق فاضي
            
        ])
        ->call('create')
        ->assertHasFormErrors(['name', 'slug']);
});
it('cannot create duplicate names', function () {
    actingAsAdmin();

    CourseCategory::create([
        'name' => 'Mobile Apps',
        'slug' => 'mobile-apps',
        'status' => true,
    ]);

    Livewire::test(CreateCourseCategory::class)
        ->fillForm([
            'name' => 'Mobile Apps',
            'slug' => 'mobile-apps-duplicate',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['name']);
});
it('cannot update to duplicate slug', function () {
    actingAsAdmin();

    CourseCategory::create(['name' => 'AI', 'slug' => 'ai', 'status' => true]);
    $category2 = CourseCategory::create(['name' => 'ML', 'slug' => 'ml', 'status' => true]);

    Livewire::test(EditCourseCategory::class, ['record' => $category2->getKey()])
        ->fillForm([
            'name' => 'Machine Learning',
            'slug' => 'ai', // مكرر
            'status' => true,
        ])
        ->call('save')
        ->assertHasFormErrors(['slug']);
});
test('guest cannot access course category pages', function () {
    $this->get(route('filament.admin.resources.course-categories.index'))
    ->assertRedirect(route('filament.admin.auth.login'));
});
it('can delete from list page', function () {
    actingAsAdmin();

    $category = CourseCategory::factory()->create();

    Livewire::test(ListCourseCategories::class)
        ->callTableAction(DeleteAction::class, $category);

    $this->assertSoftDeleted('course_categories', ['id' => $category->id]);
});
it('can search course categories by name', function () {
    actingAsAdmin();

    CourseCategory::create(['name' => 'Laravel', 'slug' => 'laravel', 'status' => true]);
    CourseCategory::create(['name' => 'React', 'slug' => 'react', 'status' => true]);

    Livewire::test(ListCourseCategories::class)
        ->searchTable('React')
        ->assertCanSeeTableRecords(CourseCategory::where('name', 'React')->get())
        ->assertCanNotSeeTableRecords(CourseCategory::where('name', 'Laravel')->get());
});