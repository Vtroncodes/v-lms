<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'content'
    ];
    
 
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_lesson')->withPivot(['order'])->withTimestamps(); 
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'lesson_topic')->withPivot(['order'])->withTimestamps();
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
