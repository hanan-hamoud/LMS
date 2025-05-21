<?php

namespace Tests\Unit;

use App\Models\Instructor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes()
    {
        $instructor = new Instructor();
        $this->assertEquals(['name', 'email', 'bio', 'photo', 'status'], $instructor->getFillable());
    }

    public function test_instructor_has_many_courses()
    {
        $instructor = Instructor::factory()->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $instructor->courses());
    }
}
