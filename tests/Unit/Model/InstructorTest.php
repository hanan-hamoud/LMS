<?php

use App\Models\Instructor;


test('fillable attributes', function () {
    $instructor = new Instructor();
    expect($instructor->getFillable())->toEqual(['name', 'email', 'bio', 'photo', 'status']);
});

test('instructor has many courses', function () {
    $instructor = Instructor::factory()->create();
    expect($instructor->courses())->toBeInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany');
});

#[test]
function it_can_create_instructor()
{
    Instructor::create([
        'name' => 'hanan',
        'email' => 'hanan2001hamoud@gmail.com',
        'bio' => 'she is a programmer',
        'photo' => '123456.png',
        'status' => true,
    ]);

    $this->assertDatabaseHas('instructors', ['email' => 'hanan2001hamoud@gmail.com']);
}