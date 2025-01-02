<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'title',
        'description',
        'quizable_id',
        'quizable_type',
    ];

    public function quizables()
    {
        return $this->morphTo();
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function submissions()
    {
        return $this->hasMany(QuizSubmission::class);
    }
}
