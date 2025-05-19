<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CourseCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'status'];

    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }
    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}
