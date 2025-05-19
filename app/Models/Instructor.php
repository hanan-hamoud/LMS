<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; 
class Instructor extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'email', 'bio', 'photo', 'status'];


    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
