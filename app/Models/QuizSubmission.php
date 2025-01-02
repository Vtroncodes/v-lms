<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSubmission extends Model
{
    protected $fillable = [
        'quiz_id',
        'course_id',
        'student_id',
        'score',
        'submitted_at',
    ];

    public function quizable()
    {
        return $this->morphTo(); // This will allow us to get the related entity (Course, Topic, or Lesson)
    }
    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
