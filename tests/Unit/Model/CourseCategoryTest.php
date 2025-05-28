<?php

use App\Models\CourseCategory;
use PHPUnit\Framework\Attributes\Test;

test('fillable attributes', function () {
    $category = new CourseCategory();
    expect($category->getFillable())->toEqual(['name', 'slug', 'status']);
});
test('category has many courses', function () {
    $category = new CourseCategory();
    expect($category->courses())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});
test('slug is generated from name', function () {
    $category = CourseCategory::create([
        'name' => 'Web Development',
    ]);

    expect($category->slug)->toEqual('web-development');
});



uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('can create course category', function () {
    $category = CourseCategory::create([
        'name' => 'Programming',
        'status' => true,
    ]);

    $this->assertDatabaseHas('course_categories', [
        'name' => 'Programming',
        'slug' => Str::slug('Programming'),
        'status' => true,
    ]);
});

it('does not allow duplicate names', function () {
    CourseCategory::create([
        'name' => 'Design',
        'status' => true,
    ]);

    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => 'Design',
        'status' => true,
    ]);
});
it('generates slug automatically from name', function () {
    $category = CourseCategory::create([
        'name' => 'Web Development',
        'status' => true,
    ]);

    expect($category->slug)->toEqual('web-development');
});
test('name is required', function () {
    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => null,
        'status' => true,
    ]);
});
test('status is required', function () {
    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => 'AI',
        'status' => null,
    ]);
});
it('can be soft deleted', function () {
    $category = CourseCategory::create([
        'name' => 'Biology',
        'status' => true,
    ]);

    $category->delete();

    $this->assertSoftDeleted('course_categories', [
        'id' => $category->id,
    ]);
});
it('does not allow duplicate slug', function () {
    CourseCategory::create([
        'name' => 'Physics',
        'status' => true,
    ]);

    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => 'Physics', 
        'status' => true,
    ]);
});

it('updates slug when name is updated', function () {
    $category = CourseCategory::create([
        'name' => 'Old Name',
        'status' => true,
    ]);

    $category->update(['name' => 'New Name']);

    expect($category->slug)->toEqual('new-name');
});