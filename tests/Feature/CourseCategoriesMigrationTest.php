<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use App\Models\CourseCategory;
use PHPUnit\Framework\Attributes\Test;

class CourseCategoriesMigrationTest extends TestCase
{
    use RefreshDatabase;

    #[test]
    public function database_has_course_categories_table()
    {
        $this->assertTrue(Schema::hasTable('course_categories'), 'جدول course_categories غير موجود!');
    }

   #[test]
    public function course_categories_table_has_expected_columns()
    {
        $columns = ['id', 'name', 'slug', 'status', 'created_at', 'updated_at'];
        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('course_categories', $column),
                "العمود {$column} غير موجود في جدول course_categories!"
            );
        }
    }

  #[test]
    public function name_is_unique_in_course_categories()
    {
        CourseCategory::create(['name' => 'UniqueName', 'slug' => 'unique-slug']);
        $this->expectException(QueryException::class);
        CourseCategory::create(['name' => 'UniqueName', 'slug' => 'another-slug']);
    }



}
