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
use App\Filament\Resources\CourseCategoryResource\Pages\ListCourseCategory;
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
}
