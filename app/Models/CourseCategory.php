<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; 
class CourseCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'status'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}
