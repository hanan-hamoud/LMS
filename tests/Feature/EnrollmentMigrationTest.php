<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\Enrollment;
class EnrollmentMigrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function database_has_courses_table()
    {
        $this->assertTrue(Schema::hasTable('enrollments'), 'جدول enrollments غير موجود!');
    }

    /** @test */
    public function courses_table_has_expected_columns()
    {
        $columns =  [
            'user_id',
            'course_id',
            'enrolled_at',
            'status',
        ];
    
        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('enrollments', $column),
                "العمود {$column} غير موجود في جدول enrollments!"
            );
        }
    }

  
}
