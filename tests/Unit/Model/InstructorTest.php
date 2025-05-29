<?php

use App\Models\Instructor;
use Illuminate\Database\Eloquent\Relations\HasMany;

test('it has correct fillable attributes', function () {
    $instructor = new Instructor();

    expect($instructor->getFillable())->toEqual([
        'name',
        'email',
        'bio',
        'photo',
        'status',
    ]);
});

test('it has many courses relationship', function () {
    $instructor = Instructor::factory()->create();

    expect($instructor->courses())->toBeInstanceOf(HasMany::class);
});

test('it can be created successfully', function () {
    $instructor = Instructor::create([
        'name' => 'Hanan',
        'email' => 'hanan2001hamoud@gmail.com',
        'bio' => 'she is a programmer',
        'photo' => '123456.png',
        'status' => true,
    ]);

    expect($instructor)->toBeInstanceOf(Instructor::class);
    expect($instructor->email)->toEqual('hanan2001hamoud@gmail.com');

    $this->assertDatabaseHas('instructors', [
        'email' => 'hanan2001hamoud@gmail.com',
    ]);
});
