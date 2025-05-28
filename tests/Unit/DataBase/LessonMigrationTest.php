<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\Lesson;
use PHPUnit\Framework\Attributes\Test;


#[test]
function database_has_lessons_table()
{
    expect(Schema::hasTable('lessons'))->toBeTrue('جدول lessons غير موجود!');
}

#[test]
function lessons_table_has_expected_columns()
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
        expect(Schema::hasColumn('lessons', $column))->toBeTrue("العمود {$column} غير موجود في جدول lessons!");
    }
}