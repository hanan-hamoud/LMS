<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\CourseCategory;
use PHPUnit\Framework\Attributes\Test;



test('database has course categories table', function () {
    expect(Schema::hasTable('course_categories'))->toBeTrue('جدول course_categories غير موجود!');
});

test('course categories table has expected columns', function () {
    $columns = ['id', 'name', 'slug', 'status', 'created_at', 'updated_at', 'deleted_at'];
    foreach ($columns as $column) {
        expect(Schema::hasColumn('course_categories', $column))->toBeTrue("العمود {$column} غير موجود في جدول course_categories!");
    }
});

test('name is unique in course categories', function () {
    CourseCategory::create(['name' => 'UniqueName', 'slug' => 'unique-slug']);
    $this->expectException(QueryException::class);
    CourseCategory::create(['name' => 'UniqueName', 'slug' => 'another-slug']);
});

test('course categories table supports soft deletes', function () {
    $category = CourseCategory::create(['name' => 'Soft Delete', 'slug' => 'soft-delete']);
    $category->delete();

    $this->assertSoftDeleted('course_categories', ['id' => $category->id]);
});

test('name column cannot be null', function () {
    $this->expectException(QueryException::class);
    CourseCategory::create(['name' => null, 'slug' => 'null-name']);
});

test('status column cannot be null', function () {
    $this->expectException(QueryException::class);
    CourseCategory::create(['name' => 'Null Status', 'slug' => 'null-status', 'status' => null]);
});
test('timestamps are set on creation', function () {
    $category = CourseCategory::create(['name' => 'Timestamps', 'slug' => 'timestamps']);
    expect($category->created_at)->not->toBeNull();
    expect($category->updated_at)->not->toBeNull();
});