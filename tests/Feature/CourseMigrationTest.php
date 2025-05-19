<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\Instructor;
class CourseMigrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function database_has_courses_table()
    {
        $this->assertTrue(Schema::hasTable('courses'), 'جدول courses غير موجود!');
    }

    /** @test */
    public function courses_table_has_expected_columns()
    {
        $columns =   [
            'title',
            'description',
            'category_id',
            'instructor_id',
            'status',
        ];
    
        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('courses', $column),
                "العمود {$column} غير موجود في جدول courses!"
            );
        }
    }

  
}
