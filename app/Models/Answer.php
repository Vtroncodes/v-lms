<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'quiz_submission_id',
        'question_id',
        'selected_option',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function quizSubmission()
    {
        return $this->belongsTo(QuizSubmission::class);
    }
}
