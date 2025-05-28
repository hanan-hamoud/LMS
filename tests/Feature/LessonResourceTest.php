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


it('fails to create a lesson with invalid data', function () {
    $admin = createAdminUser();
    actingAs($admin);

    Livewire::test(CreateLesson::class)
        ->fillForm([
           
        ])
        ->call('create')
        ->assertHasFormErrors([
            'course_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'video_url' => 'required',
            'is_preview' => 'required',
            'order' => 'required',
        ]);
});

it('fails to create a lesson with invalid order', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $course = Course::factory()->create();

    Livewire::test(CreateLesson::class)
        ->fillForm([
            'course_id' => $course->id,
            'title' => 'Invalid Order',
            'description' => 'Invalid Order Desc',
            'video_url' => 'https://example.com/video.mp4',
            'is_preview' => true,
            'order' => 0, 
        ])
        ->call('create')
        ->assertHasFormErrors([
            'order' => 'min',
        ]);
});


it('allows duplicate lesson titles in different courses', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $course1 = Course::factory()->create();
    $course2 = Course::factory()->create();

    Lesson::factory()->create([
        'course_id' => $course1->id,
        'title' => 'Same Title',
    ]);

    Livewire::test(CreateLesson::class)
        ->fillForm([
            'course_id' => $course2->id,
            'title' => 'Same Title',
            'description' => 'Another',
            'video_url' => 'https://example.com/video2.mp4',
            'is_preview' => false,
            'order' => 2,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseCount('lessons', 2);
});
it('fails with invalid video url', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $course = Course::factory()->create();

    Livewire::test(CreateLesson::class)
        ->fillForm([
            'course_id' => $course->id,
            'title' => 'Video URL Test',
            'description' => 'Testing invalid video url',
            'video_url' => 'not-a-valid-url',
            'is_preview' => true,
            'order' => 1,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'video_url',
        ]);
});
