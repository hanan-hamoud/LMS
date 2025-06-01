<?php
use App\Models\CourseCategory;
use Illuminate\Support\Str;

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
        'name' => ['en' => 'Web Development', 'ar' => 'تطوير الويب'],
        'status' => true,
    ]);

    expect($category->slug)->toEqual('web-development');
});

it('can create course category', function () {
    $category = CourseCategory::create([
        'name' => ['en' => 'Programming'],
        'status' => true,
    ]);

    $this->assertDatabaseHas('course_categories', [
        'name->en' => 'Programming',
        'slug' => Str::slug('Programming'),
        'status' => true,
    ]);
});

it('does not allow duplicate names', function () {
    CourseCategory::create([
        'name' => ['en' => 'Design', 'ar' => 'تصميم'],
        'status' => true,
    ]);

    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => ['en' => 'Design', 'ar' => 'تصميم'],
        'status' => true,
    ]);
});

it('generates slug automatically from name', function () {
    $category = CourseCategory::create([
        'name' => ['en' => 'Web Development', 'ar' => 'تطوير الويب'],
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
        'name' => ['en' => 'AI', 'ar' => 'ذكاء اصطناعي'],
        'status' => null,
    ]);
});

it('can be soft deleted', function () {
    $category = CourseCategory::create([
        'name' => ['en' => 'Biology', 'ar' => 'أحياء'],
        'status' => true,
    ]);

    $category->delete();

    $this->assertSoftDeleted('course_categories', [
        'id' => $category->id,
    ]);
});

it('does not allow duplicate slug', function () {
    CourseCategory::create([
        'name' => ['en' => 'Physics', 'ar' => 'فيزياء'],
        'status' => true,
    ]);

    $this->expectException(\Illuminate\Database\QueryException::class);

    CourseCategory::create([
        'name' => ['en' => 'Physics', 'ar' => 'فيزياء'],
        'status' => true,
    ]);
});

it('updates slug when name is updated', function () {
    $category = CourseCategory::create([
        'name' => ['en' => 'Old Name', 'ar' => 'اسم قديم'],
        'status' => true,
    ]);

    $category->update(['name' => ['en' => 'New Name', 'ar' => 'اسم جديد']]);

    expect($category->slug)->toEqual('new-name');
});
