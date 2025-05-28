<?php

use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use App\Filament\Resources\EnrollmentResource\Pages\CreateEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\EditEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\ListEnrollments;
use Filament\Actions\DeleteAction;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function createAdminUser(): User
{
    return User::factory()->create();
}

it('can list enrollments', function () {
    $enrollments = Enrollment::factory()->count(10)->create();

    Livewire::test(ListEnrollments::class)
        ->assertCanSeeTableRecords($enrollments);
});

it('can create a new enrollment', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $user = User::factory()->create();
    $course = Course::factory()->create();

    Livewire::test(CreateEnrollment::class)
        ->fillForm([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists())->toBeTrue();
});

it('can update an enrollment', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $user = User::factory()->create();
    $course = Course::factory()->create();
    $enrollment = Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'enrolled_at' => now(),
        'status' => true,
    ]);

    Livewire::test(EditEnrollment::class, ['record' => $enrollment->getKey()])
        ->fillForm([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect((bool) $enrollment->fresh()->status)->toBeFalse();
});

it('can delete an enrollment', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $enrollment = Enrollment::factory()->create();

    Livewire::test(EditEnrollment::class, ['record' => $enrollment->getKey()])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('enrollments', [
        'id' => $enrollment->id,
    ]);
});

it('validates duplicate enrollment prevention', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $user = User::factory()->create();
    $course = Course::factory()->create();
    Enrollment::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'enrolled_at' => now(),
        'status' => true,
    ]);

    Livewire::test(CreateEnrollment::class)
        ->fillForm([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['course_id']);
});

it('requires user_id and course_id when creating enrollment', function () {
    $admin = createAdminUser();
    actingAs($admin);

    Livewire::test(CreateEnrollment::class)
        ->fillForm([
            'user_id' => null,
            'course_id' => null,
            'enrolled_at' => now(),
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['user_id', 'course_id']);
});
