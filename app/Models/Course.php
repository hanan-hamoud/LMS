<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'instructor_id',
        'status',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }
    public function lessons()
{
    return $this->hasMany(Lesson::class);
}
}
