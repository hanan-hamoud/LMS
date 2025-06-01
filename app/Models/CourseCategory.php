<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCategory extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'slug', 'status'];

    protected $casts = [
        'name' => 'array',
        'status' => 'boolean',
    ];

    public function getNameEnAttribute()
    {
        return $this->name['en'] ?? null;
    }

    public function setNameEnAttribute($value)
    {
        $name = $this->attributes['name'] ? json_decode($this->attributes['name'], true) : [];
        $name['en'] = $value;
        $this->attributes['name'] = json_encode($name);
    }

    public function getNameArAttribute()
    {
        return $this->name['ar'] ?? null;
    }

    public function setNameArAttribute($value)
    {
        $name = $this->attributes['name'] ? json_decode($this->attributes['name'], true) : [];
        $name['ar'] = $value;
        $this->attributes['name'] = json_encode($name);
    }
    public static function boot()
    {
        parent::boot();
    
        static::saving(function ($model) {
            if (!empty($model->name['en'])) {
                $model->slug = \Str::slug($model->name['en']);
            }
        });
    }
    
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
