<?php

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\Instructor;
use PHPUnit\Framework\Attributes\Test;

#[test]
function database_has_courses_table_courses()
{
    expect(Schema::hasTable('courses'))->toBeTrue('جدول courses غير موجود!');
}

#[test]
function courses_table_has_expected_columns_courses()
{
    $columns =   [
        'title',
        'description',
        'category_id',
        'instructor_id',
        'status',
    ];

    foreach ($columns as $column) {
        expect(Schema::hasColumn('courses', $column))->toBeTrue("العمود {$column} غير موجود في جدول courses!");
    }
}