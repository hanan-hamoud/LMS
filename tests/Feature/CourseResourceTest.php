<?php

namespace Tests\Feature;
use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Filament\Resources\CourseResource\Pages\CreateCourse;
use App\Filament\Resources\CourseResource\Pages\EditCourse;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;

class CourseResourceTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
        return $admin;
    }
   
    #[Test]
    public function it_can_create_a_new_course(): void
    {
        $this->actingAsAdmin();
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
    }
    

       
    #[Test]
    public function it_can_update_a_course(): void
    {
        $this->actingAsAdmin();
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
    
        $this->assertSame('Programming php', $course->title);

        $this->assertFalse((bool) $course->status);
    }
    

    #[Test]
    public function it_can_delete_a_course(): void
    {
        $this->actingAsAdmin();

        $course = Course::factory()->create();

        Livewire::test(EditCourse::class, ['record' => $course->getKey()])
            ->callAction(DeleteAction::class);

        $this->assertSoftDeleted('courses', [
            'id' => $course->id,
        ]);
    }


    #[Test]
public function it_can_render_the_index_page_of_courses(): void
{
    $this->actingAsAdmin();
    $course = Course::factory()->create([
        'title' => 'Laravel Advanced',
    ]);
    $response = $this->get('/admin/courses');
    $response->assertOk();
    $response->assertSee('Courses'); 
    $response->assertSee('Laravel Advanced'); 
}

}

