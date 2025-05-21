<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\CourseCategory;
use PHPUnit\Framework\Attributes\Test;

class CourseCategoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
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


    #[Test]
public function it_does_not_allow_duplicate_names()
{
    CourseCategory::create([
        'name' => 'Design',
        'status' => true,
    ]);

    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => 'Design',
        'status' => true,
    ]);
}
#[Test]
public function it_generates_slug_automatically_from_name()
{
    $category = CourseCategory::create([
        'name' => 'Web Development',
        'status' => true,
    ]);

    $this->assertEquals('web-development', $category->slug);
}
#[Test]
public function name_is_required()
{
    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => null,
        'status' => true,
    ]);
}
#[Test]
public function status_is_required()
{
    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => 'AI',
        'status' => null,
    ]);
}
#[Test]
public function it_can_be_soft_deleted()
{
    $category = CourseCategory::create([
        'name' => 'Biology',
        'status' => true,
    ]);

    $category->delete();

    $this->assertSoftDeleted('course_categories', [
        'id' => $category->id,
    ]);
}
#[Test]
public function it_does_not_allow_duplicate_slug()
{
    CourseCategory::create([
        'name' => 'Physics',
        'status' => true,
    ]);

    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => 'Physics', 
        'status' => true,
    ]);
}



#[Test]
public function it_updates_slug_when_name_is_updated()
{
    $category = CourseCategory::create([
        'name' => 'Old Name',
        'status' => true,
    ]);

    $category->update(['name' => 'New Name']);

    $this->assertEquals('new-name', $category->slug);
}



}
