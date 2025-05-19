<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\CourseCategory;

class CourseCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_course_category()
    {
        $category = CourseCategory::create([
            'name' => 'Programming',
            'status' => true,
        ]);

        $this->assertDatabaseHas('course_categories', [
            'name' => 'Programming',
            'slug' => Str::slug('Programming'),
            'status' => true,
        ]);
    }
}
