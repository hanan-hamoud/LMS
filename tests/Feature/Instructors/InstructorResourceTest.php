<?php

use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Instructor;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use App\Filament\Resources\InstructorResource\Pages\CreateInstructor;
use App\Filament\Resources\InstructorResource\Pages\EditInstructor;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function actingAsAdmin(): User
{
    $admin = User::factory()->create();
    $this->actingAs($admin);
    return $admin;
}

it('can list instructor', function () {
    $instructor = Instructor::factory()->count(10)->create();

    Livewire::test(\App\Filament\Resources\InstructorResource\Pages\ListInstructors::class)
        ->assertCanSeeTableRecords($instructor);
});

it('can create a new instructor', function () {
    Storage::fake('public');

    actingAsAdmin();

    $fakePhoto = UploadedFile::fake()->image('photo.png');

    Livewire::test(CreateInstructor::class)
        ->fillForm([
            'name' => 'hanan',
            'email' => 'hanan2001hamoud121@gmail.com',
            'bio' => 'programming',
            'photo' => $fakePhoto,
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('instructors', [
        'name' => 'hanan',
        'email' => 'hanan2001hamoud121@gmail.com',
        'bio' => 'programming',
        'status' => true,
    ]);
    $instructor = Instructor::where('email', 'hanan2001hamoud121@gmail.com')->first();

    Storage::disk('public')->assertExists($instructor->photo);
});

it('can update an instructor', function () {
    Storage::fake('public');

    actingAsAdmin();

    $instructor = Instructor::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
        'bio' => 'Old bio',
        'status' => true,
    ]);

    $newPhoto = UploadedFile::fake()->image('newphoto.jpg');

    Livewire::test(EditInstructor::class, ['record' => $instructor->getKey()])
        ->fillForm([
            'name' => 'New Name',
            'email' => 'new@example.com',
            'bio' => 'New bio',
            'photo' => $newPhoto,
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $instructor->refresh();

    expect($instructor->name)->toBe('New Name');
    expect($instructor->email)->toBe('new@example.com');
    expect($instructor->bio)->toBe('New bio');
    expect((bool) $instructor->status)->toEqual(false);

    // التحقق من وجود الصورة حسب ما تم تخزينه فعليًا
    Storage::disk('public')->assertExists($instructor->photo);
});

it('can delete an instructor', function () {
    actingAsAdmin();

    $instructor = Instructor::factory()->create();

    Livewire::test(EditInstructor::class, ['record' => $instructor->getKey()])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('instructors', [
        'id' => $instructor->id,
    ]);
});

// #[Test]
// public function it_can_delete_a_enrollment(): void
// {
//     $this->actingAsAdmin();
//     $enrollment = Enrollment::factory()->create();
//     Livewire::test(EditEnrollment::class, ['record' => $enrollment->getKey()])
//         ->callAction(DeleteAction::class);
//     $this->assertSoftDeleted('enrollments', [
//         'id' => $enrollment->id,
//     ]);
// }
// #[Test]
// public function it_can_update_a_enrollment(): void
// {
//     $this->actingAsAdmin();
//     $user = User::factory()->create();
//     $course = Course::factory()->create();
//     $enrollment = Enrollment::create([
//         'user_id'=>$user->id,
//         'course_id'=>$course->id,
//         'enrolled_at' => now(),
//         'status' => true,
//     ]);
//     Livewire::test(EditEnrollment::class, ['record' => $enrollment->getKey()])
//         ->fillForm([
//             'user_id'=>$user->id,
//             'course_id'=>$course->id,
//             'enrolled_at' => now(),
//             'status' => false,
//         ])
//         ->call('save')
//         ->assertHasNoFormErrors();
//     $enrollment->refresh();
//     $this->assertSame(false, $enrollment->status);
// }