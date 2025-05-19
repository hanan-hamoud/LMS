<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Tests\TestCase;
use App\Models\Instructor;

class InstructorMigrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function database_has_instructors_table()
    {
        $this->assertTrue(Schema::hasTable('instructors'), 'جدول instructors غير موجود!');
    }

    /** @test */
    public function instructors_table_has_expected_columns()
    {
        $columns =  ['name', 'email', 'bio', 'photo', 'status'];
        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('instructors', $column),
                "العمود {$column} غير موجود في جدول instructors!"
            );
        }
    }

    /** @test */
    public function email_is_unique_in_instructors()
    {
        Instructor::create([
            'name' => 'Ahmed',
            'email' => 'UniqueEmail',
            'bio' => 'test',
            'photo' => 'photo.png',
            'status' => true
        ]);

        $this->expectException(QueryException::class);

        Instructor::create([
            'name' => 'Ali',
            'email' => 'UniqueEmail',
            'bio' => 'test2',
            'photo' => 'photo2.png',
            'status' => true
        ]);
    }
}
