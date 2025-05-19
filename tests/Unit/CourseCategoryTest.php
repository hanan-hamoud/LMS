<?php

use App\Models\CourseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
class CourseCategoryTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function test_fillable_attributes()
    {
        $category = new CourseCategory();
        $this->assertEquals(['name', 'slug', 'status'], $category->getFillable());
    }
    #[Test]
    public function test_category_has_many_courses()
    {
        $category = new CourseCategory();
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $category->courses()
        );
    }
}
