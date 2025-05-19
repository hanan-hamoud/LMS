<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'status',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'status' => 'boolean',
    ];

    protected static function booted()
{
    static::creating(function ($enrollment) {
        $exists = self::where('user_id', $enrollment->user_id)
                      ->where('course_id', $enrollment->course_id)
                      ->exists();
        if ($exists) {
            throw ValidationException::withMessages([
                'user_id' => 'This user is already enrolled in the selected course.',
            ]);
        }
    });
}


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
