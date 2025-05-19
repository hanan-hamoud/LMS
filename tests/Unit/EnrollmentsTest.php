<?php

namespace Tests\Unit;

use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}

