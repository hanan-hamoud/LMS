<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\Instructor;
use PHPUnit\Framework\Attributes\Test;


#[test]
function database_has_instructors_table()
{
    expect(Schema::hasTable('instructors'))->toBeTrue('جدول instructors غير موجود!');
}

#[test]
function instructors_table_has_expected_columns()
{
    $columns =  ['name', 'email', 'bio', 'photo', 'status'];
    foreach ($columns as $column) {
        expect(Schema::hasColumn('instructors', $column))->toBeTrue("العمود {$column} غير موجود في جدول instructors!");
    }
}

#[test]
function email_is_unique_in_instructors()
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