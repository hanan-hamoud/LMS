<?php

namespace Tests\Unit;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EnrollmentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes()
    {
        $enrollment = new Enrollment();
        $this->assertEquals(['user_id', 'course_id', 'enrolled_at', 'status'], $enrollment->getFillable());
    }

    public function test_enrollment_belongs_to_user()
    {
        $enrollment = Enrollment::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $enrollment->user());
    }

    public function test_enrollment_belongs_to_course()
    {
        $enrollment = Enrollment::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $enrollment->course());
    }

    public function test_status_is_cast_to_boolean()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => 1,
        ]);

        $this->assertIsBool($enrollment->status);
        $this->assertTrue($enrollment->status);
    }

    public function test_enrolled_at_is_cast_to_datetime()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $date = now();

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => $date->toDateTimeString(),
            'status' => true,
        ]);

        $this->assertInstanceOf(Carbon::class, $enrollment->enrolled_at);
        $this->assertEquals(
            $date->format('Y-m-d H:i'),
            $enrollment->enrolled_at->format('Y-m-d H:i')
        );
    }
}
