<?php

use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Lesson;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use App\Filament\Resources\LessonResource\Pages\CreateLesson;
use App\Filament\Resources\LessonResource\Pages\EditLesson;
use Filament\Actions\DeleteAction;
use App\Models\Course;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// دالة فقط لإنشاء المستخدم الإداري وإرجاعه
function createAdminUser(): User
{
    return User::factory()->create();
}

it('can list lesson', function () {
    $lesson = Lesson::factory()->count(10)->create();

    Livewire::test(\App\Filament\Resources\LessonResource\Pages\ListLessons::class)
        ->assertCanSeeTableRecords($lesson);
});

it('can create a new lesson', function () {
    $admin = createAdminUser();
    actingAs($admin);

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
});

it('can update a lesson', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $course = Course::factory()->create();
    $lesson = Lesson::factory()->create(['course_id' => $course->id]);

    Livewire::test(EditLesson::class, ['record' => $lesson->getKey()])
        ->fillForm([
            'title' => 'Updated Title',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $lesson->refresh();

    expect($lesson->title)->toBe('Updated Title');
});

it('can delete a lesson', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $lesson = Lesson::factory()->create();

    Livewire::test(EditLesson::class, ['record' => $lesson->getKey()])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('lessons', [
        'id' => $lesson->id,
    ]);
});
