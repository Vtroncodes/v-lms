<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

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

    protected $casts = [
        'required_prerequisites_course_id' => 'array', // JSON field
    ];


    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'course_lesson')->withPivot(['order'])->withTimestamps();
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
        return $this->belongsToMany(Quiz::class, 'course_quiz')->withPivot(['order'])->withTimestamps();
    }
    public function ratingsReviews()
    {
        return $this->hasMany(RatingReview::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

}
