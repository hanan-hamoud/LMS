<?php

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\Enrollment;
use PHPUnit\Framework\Attributes\Test;


#[test]
function database_has_courses_table_enrollments()
{
    expect(Schema::hasTable('enrollments'))->toBeTrue('جدول enrollments غير موجود!');
}
#[test]
function courses_table_has_expected_columns_enrollments()
{
    $columns =  [
        'user_id',
        'course_id',
        'enrolled_at',
        'status',
    ];

    foreach ($columns as $column) {
        expect(Schema::hasColumn('enrollments', $column))->toBeTrue("العمود {$column} غير موجود في جدول enrollments!");
    }
}