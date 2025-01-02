<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingReview extends Model
{
    protected $fillable = [
        'course_id',
        'student_id',
        'rating',
        'review',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class);
    }
}