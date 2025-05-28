<?php

use function Pest\Laravel\actingAs;
use App\Models\User;
use App\Models\Instructor;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use App\Filament\Resources\InstructorResource\Pages\CreateInstructor;
use App\Filament\Resources\InstructorResource\Pages\EditInstructor;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// function createAdminUser(): User
// {
//     return User::factory()->create();
// }

it('can list instructor', function () {
    $instructors = Instructor::factory()->count(10)->create();

    Livewire::test(\App\Filament\Resources\InstructorResource\Pages\ListInstructors::class)
        ->assertCanSeeTableRecords($instructors);
});

it('can create a new instructor', function () {
    Storage::fake('public');

    $admin = createAdminUser();
    actingAs($admin);

    $fakePhoto = UploadedFile::fake()->image('photo.png');

    Livewire::test(CreateInstructor::class)
        ->fillForm([
            'name' => 'hanan',
            'email' => 'hanan2001hamoud121@gmail.com',
            'bio' => 'programming',
            'photo' => $fakePhoto,
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('instructors', [
        'name' => 'hanan',
        'email' => 'hanan2001hamoud121@gmail.com',
        'bio' => 'programming',
        'status' => true,
    ]);

    $instructor = Instructor::where('email', 'hanan2001hamoud121@gmail.com')->first();

    Storage::disk('public')->assertExists($instructor->photo);
});

it('can update an instructor', function () {
    Storage::fake('public');

    $admin = createAdminUser();
    actingAs($admin);

    $instructor = Instructor::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
        'bio' => 'Old bio',
        'status' => true,
    ]);

    $newPhoto = UploadedFile::fake()->image('newphoto.jpg');

    Livewire::test(EditInstructor::class, ['record' => $instructor->getKey()])
        ->fillForm([
            'name' => 'New Name',
            'email' => 'new@example.com',
            'bio' => 'New bio',
            'photo' => $newPhoto,
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $instructor->refresh();

    expect($instructor->name)->toBe('New Name');
    expect($instructor->email)->toBe('new@example.com');
    expect($instructor->bio)->toBe('New bio');
    expect((bool) $instructor->status)->toBeFalse();

    Storage::disk('public')->assertExists($instructor->photo);
});

it('can delete an instructor', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $instructor = Instructor::factory()->create();

    Livewire::test(EditInstructor::class, ['record' => $instructor->getKey()])
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('instructors', [
        'id' => $instructor->id,
    ]);
});
it('fails to create instructor with invalid data', function () {
    $admin = createAdminUser();
    actingAs($admin);

    Livewire::test(CreateInstructor::class)
        ->fillForm([
            'name' => '',
            'bio' => '',
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name', 'email', 'bio', 'status']);
});

it('fails to update instructor with duplicate email', function () {
    $admin = createAdminUser();
    actingAs($admin);

    $existing = Instructor::factory()->create(['email' => 'existing@example.com']);
    $instructor = Instructor::factory()->create();

    Livewire::test(EditInstructor::class, ['record' => $instructor->getKey()])
        ->fillForm([
            'name' => 'Test',
            'email' => 'existing@example.com', 
            'bio' => 'Bio',
            'status' => true,
        ])
        ->call('save')
        ->assertHasFormErrors(['email']);
});
it('displays instructor photo in table', function () {
    $admin = createAdminUser();
    actingAs($admin);

    Storage::fake('public');
    $photo = UploadedFile::fake()->image('photo.jpg');

    $instructor = Instructor::factory()->create([
        'photo' => $photo->store('instructors', 'public'),
    ]);

    Livewire::test(\App\Filament\Resources\InstructorResource\Pages\ListInstructors::class)
        ->assertCanSeeTableRecords([$instructor])
        ->assertSee($instructor->name)
        ->assertSee($instructor->email);
});


it('can update instructor with same email without triggering unique error', function () {
    $admin = createAdminUser();
    actingAs($admin);

    Storage::fake('public');

    
    $photoPath = UploadedFile::fake()->image('photo.jpg')->store('instructors', 'public');

    $instructor = Instructor::factory()->create([
        'email' => 'same@example.com',
        'photo' => $photoPath, 
    ]);

    Livewire::test(EditInstructor::class, ['record' => $instructor->getKey()])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => 'same@example.com', 
            'bio' => 'Updated bio',
            'status' => true,
          
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $instructor->refresh();

    expect($instructor->name)->toBe('Updated Name');
    expect($instructor->email)->toBe('same@example.com');
    expect($instructor->photo)->toBe($photoPath); 
});
