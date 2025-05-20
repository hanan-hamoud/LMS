<?php

namespace Tests\Feature;
use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Filament\Resources\LessonResource\Pages\CreateLesson;
use App\Filament\Resources\LessonResource\Pages\EditLesson;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
class LessonResourceTest extends TestCase
{
    use RefreshDatabase;
    private function actingAsAdmin()
{
    $admin = \App\Models\User::factory()->create();
    $this->actingAs($admin);
    return $admin;
}

    #[Test]
    public function it_can_create_a_new_lesson(): void
    {
        $this->actingAsAdmin();
    
        $course = Course::factory()->create();
    
        Livewire::test(CreateLesson::class)
            ->fillForm([
                'course_id' => $course->id,
                'title' => 'variable',
                'description' => 'know how make variable',
                'video_url' => 'https://example.com/video.mp4',
                'is_preview' => false,
                'order' => 1,
            ])
            ->call('create') 
            ->assertHasNoFormErrors(); 
    
        $this->assertDatabaseHas('lessons', [
            'title' => 'variable',
            'is_preview' => false,
        ]);
    }
    
    #[Test]
    public function it_can_update_a_lesson(): void
    {
        $this->actingAsAdmin();
    
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
    
        Livewire::test(EditLesson::class, ['record' => $lesson->getKey()])
            ->fillForm([
                'title' => 'Updated Title',
            ])
            ->call('save') 
            ->assertHasNoFormErrors();
    
        $lesson->refresh();
    
        $this->assertSame('Updated Title', $lesson->title);
    }
    
    #[Test]
    public function it_can_delete_a_lesson(): void
    {
        $this->actingAsAdmin();
    
        $lesson = Lesson::factory()->create();
    
        Livewire::test(EditLesson::class, ['record' => $lesson->getKey()])
            ->callAction(DeleteAction::class);
    
        $this->assertSoftDeleted('lessons', [
            'id' => $lesson->id,
        ]);
    }
    
    
}
