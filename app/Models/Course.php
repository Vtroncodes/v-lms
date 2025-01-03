<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'level',
        'duration',
        'course_type',
        'allowed_retakes',
        'required_prerequisites_course_id',
        'certificate_url',
        'directory_path',
    ];

    /**
     * Cast attributes to specific data types.
     *
     * @var array
     */
    protected $casts = [
        'required_prerequisites_course_id' => 'array', // JSON field
    ];

    /**
     * Define relationships with other models.
     */

    // A course has many lessons
    // public function lessons()
    // {
    //     return $this->belongsToMany(Lesson::class, 'course_lesson', 'course_id', 'lesson_id')->withPivot('lesson_order')->withTimestamps();
    // }  
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class)->withTimestamps();
    }


    public function users()
    {
        return $this->belongsToMany(User::class);
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

    public function ratingsReviews()
    {
        return $this->hasMany(RatingReview::class);
    }
}
