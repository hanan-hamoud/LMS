<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use App\Models\Lesson;

class LessonMigrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
  
    /** @test */
    public function database_has_lessons_table()
    {
        $this->assertTrue(Schema::hasTable('lessons'), 'جدول lessons غير موجود!');
    }

    /** @test */
    public function lessons_table_has_expected_columns()
    {
        $columns =[
            'course_id',
            'title',
            'description',
            'video_url',
            'is_preview',
            'order',
        ];
        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('lessons', $column),
                "العمود {$column} غير موجود في جدول lessons!"
            );
        }
    }

 
}
