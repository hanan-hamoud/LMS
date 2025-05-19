<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Instructor; 
class InstructorTest  extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_it_can_create_instructor()
    {
        $instructor = \App\Models\Instructor::create([
            'name' => 'hanan',
            'email' => 'hanan2001hamoud@gmail.com',
            'bio' => 'she is a programmer',         
            'photo' => '123456.png',               
            'status' => true
        ]);
    
        $this->assertDatabaseHas('instructors', [
            'email' => 'hanan2001hamoud@gmail.com',
            'status' => true,
        ]);
    }
    
}






