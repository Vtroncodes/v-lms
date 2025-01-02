<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    // Specify the fillable fields (columns that can be mass-assigned)
    protected $fillable = ['title', 'description', 'content_id'];

    /**
     * Get all content blocks associated with the topic.
     */

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    
    public function contents()
    {
        return $this->morphMany(Content::class, 'contentable');
    }

    public function assignments()
    {
        return $this->morphMany(Assignment::class, 'assignmentable');
    }

    public function quizzes()
    {
        return $this->morphMany(Quiz::class, 'quizable');
    }
}
