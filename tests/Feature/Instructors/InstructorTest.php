<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Instructor; 
use PHPUnit\Framework\Attributes\Test;
class InstructorTest  extends TestCase
{

    use RefreshDatabase;
    #[test]
     public function it_can_create_instructor()
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
    
}






