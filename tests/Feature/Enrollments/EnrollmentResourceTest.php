<?php

namespace Tests\Feature;
use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Filament\Resources\EnrollmentResource\Pages\CreateEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\EditEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\ListEnrollments;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
class EnrollmentResourceTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);
        return $admin;
    }
    #[Test]
  
    public function it_can_list_enrollment()
    {
        $enrollment = Enrollment::factory()->count(10)->create();
    
        Livewire::test(\App\Filament\Resources\EnrollmentResource\Pages\ListEnrollments::class)
            ->assertCanSeeTableRecords($enrollment);
    }
   

    #[Test]
    public function it_can_create_a_new_enrollment(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();
        $course = Course::factory()->create();
        Livewire::test(CreateEnrollment::class)
            ->fillForm([
                'user_id'=>$user->id,
                'course_id'=>$course->id,
                'enrolled_at' => now(),
                'status' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('enrollments', [
            'status' => true,
        ]);
    }

   
#[Test]
public function it_can_delete_a_enrollment(): void
{
    $this->actingAsAdmin();

    $enrollment = Enrollment::factory()->create();

    Livewire::test(EditEnrollment::class, ['record' => $enrollment->getKey()])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('enrollments', [
        'id' => $enrollment->id,
    ]);
}


 
#[Test]
public function it_can_update_a_enrollment(): void
{
    $this->actingAsAdmin();

    $user = User::factory()->create();
    $course = Course::factory()->create();
    $enrollment = Enrollment::create([
        'user_id'=>$user->id,
        'course_id'=>$course->id,
        'enrolled_at' => now(),
        'status' => true,
    ]);

    Livewire::test(EditEnrollment::class, ['record' => $enrollment->getKey()])
        ->fillForm([
            'user_id'=>$user->id,
            'course_id'=>$course->id,
            'enrolled_at' => now(),
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $enrollment->refresh();

    $this->assertSame(false, $enrollment->status);
}


}


