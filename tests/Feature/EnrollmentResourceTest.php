<?php

use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Enrollment;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use App\Filament\Resources\EnrollmentResource\Pages\CreateEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\EditEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\ListEnrollments;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function createAdminUser(): User
{
    return User::factory()->create();
}

it('can list enrollment', function () {
    $enrollment = Enrollment::factory()->count(10)->create();

    Livewire::test(\App\Filament\Resources\EnrollmentResource\Pages\ListEnrollments::class)
        ->assertCanSeeTableRecords($enrollment);
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

    $this->assertDatabaseHas('enrollments', [
        'status' => true,
    ]);
});

it('can delete a enrollment', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $enrollment = Enrollment::factory()->create();

    Livewire::test(EditEnrollment::class, ['record' => $enrollment->getKey()])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('enrollments', [
        'id' => $enrollment->id,
    ]);
});

it('can update a enrollment', function () {
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

    $enrollment->refresh();

    expect((bool) $enrollment->status)->toBeFalse();
});
