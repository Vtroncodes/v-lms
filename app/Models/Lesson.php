<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    /**
     *The attributes are mass assignable 
     * 
     *@var array
     */

    protected $fillable = [
        'title',
        'description',
        'content'
    ];

   
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_lesson')->withPivot(); // Specify pivot table name if different from default
    }
    public function topics()
    {
        return $this->hasMany(Topic::class);
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
