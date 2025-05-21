<?php

namespace Tests\Feature;
use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\CourseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Filament\Resources\CourseCategoryResource\Pages\CreateCourseCategory;
use App\Filament\Resources\CourseCategoryResource\Pages\EditCourseCategory;
use App\Filament\Resources\CourseCategoryResource\Pages\ListCourseCategories;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
class CourseCategoryResourceTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
        return $admin;
    }

    public function it_can_list_course_categories()
    {
        $courseCategory = CourseCategory::factory()->count(10)->create();

        Livewire::test(ListCourseCategory::class)
            ->assertCanSeeTableRecords($courseCategory);
    }

  
    

    #[Test]
    public function it_can_create_a_new_course_category(): void
    {
        $this->actingAsAdmin();

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
    }

    
    #[Test]
    public function it_can_update_a_course_category(): void
    {
        $this->actingAsAdmin();
    
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
    
        $this->assertSame('Graphic Design', $category->name);
        $this->assertFalse((bool) $category->status);
    }
    

    #[Test]
    public function it_can_delete_a_course_category(): void
    {
        $this->actingAsAdmin();

        $category = CourseCategory::factory()->create();

        Livewire::test(EditCourseCategory::class, ['record' => $category->getKey()])
            ->callAction(DeleteAction::class);

        $this->assertSoftDeleted('course_categories', [
            'id' => $category->id,
        ]);
    }

    #[Test]
public function it_fails_to_create_with_invalid_data(): void
{
    $this->actingAsAdmin();

    Livewire::test(CreateCourseCategory::class)
        ->fillForm([
            'name' => '', // اسم فاضي
            'slug' => '', // سلاق فاضي
            
        ])
        ->call('create')
        ->assertHasFormErrors(['name', 'slug']);
}
#[Test]
public function it_cannot_create_duplicate_names(): void
{
    $this->actingAsAdmin();

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
}
#[Test]
public function it_cannot_update_to_duplicate_slug(): void
{
    $this->actingAsAdmin();

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
}
#[Test]
public function guest_cannot_access_course_category_pages(): void
{
    $this->get(route('filament.admin.resources.course-categories.index'))
    ->assertRedirect(route('filament.admin.auth.login'));

}
#[Test]
public function it_can_delete_from_list_page(): void
{
    $this->actingAsAdmin();

    $category = CourseCategory::factory()->create();

    Livewire::test(ListCourseCategories::class)
        ->callTableAction(DeleteAction::class, $category);

    $this->assertSoftDeleted('course_categories', ['id' => $category->id]);
}
#[Test]
public function it_can_search_course_categories_by_name(): void
{
    $this->actingAsAdmin();

    CourseCategory::create(['name' => 'Laravel', 'slug' => 'laravel', 'status' => true]);
    CourseCategory::create(['name' => 'React', 'slug' => 'react', 'status' => true]);

    Livewire::test(ListCourseCategories::class)
        ->searchTable('React')
        ->assertCanSeeTableRecords(CourseCategory::where('name', 'React')->get())
        ->assertCanNotSeeTableRecords(CourseCategory::where('name', 'Laravel')->get());
}

}
