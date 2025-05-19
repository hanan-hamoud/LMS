<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Instructor extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'bio', 'photo', 'status'];


    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
