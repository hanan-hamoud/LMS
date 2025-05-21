<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use App\Models\CourseCategory;
use PHPUnit\Framework\Attributes\Test;

class CourseCategoriesMigrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function database_has_course_categories_table()
    {
        $this->assertTrue(Schema::hasTable('course_categories'), 'جدول course_categories غير موجود!');
    }

    #[Test]
    public function course_categories_table_has_expected_columns()
    {
        $columns = ['id', 'name', 'slug', 'status', 'created_at', 'updated_at', 'deleted_at'];
        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('course_categories', $column),
                "العمود {$column} غير موجود في جدول course_categories!"
            );
        }
    }

    #[Test]
    public function name_is_unique_in_course_categories()
    {
        CourseCategory::create(['name' => 'UniqueName', 'slug' => 'unique-slug']);
        $this->expectException(QueryException::class);
        CourseCategory::create(['name' => 'UniqueName', 'slug' => 'another-slug']);
    }

  


    #[Test]
    public function course_categories_table_supports_soft_deletes()
    {
        $category = CourseCategory::create(['name' => 'Soft Delete', 'slug' => 'soft-delete']);
        $category->delete();

        $this->assertSoftDeleted('course_categories', ['id' => $category->id]);
    }

    #[Test]
    public function name_column_cannot_be_null()
    {
        $this->expectException(QueryException::class);
        CourseCategory::create(['name' => null, 'slug' => 'null-name']);
    }

   

    #[Test]
    public function status_column_cannot_be_null()
    {
        $this->expectException(QueryException::class);
        CourseCategory::create(['name' => 'Null Status', 'slug' => 'null-status', 'status' => null]);
    }
    #[Test]
    public function timestamps_are_set_on_creation()
    {
        $category = CourseCategory::create(['name' => 'Timestamps', 'slug' => 'timestamps']);
        $this->assertNotNull($category->created_at);
        $this->assertNotNull($category->updated_at);
    }
}
