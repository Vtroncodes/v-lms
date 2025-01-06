<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['title', 'description', 'passing_percentage'];


    public function courses()
    {
        return $this->morphedByMany(Course::class, 'quizable', 'quizables');
    }

    public function lessons()
    {
        return $this->morphedByMany(Lesson::class, 'quizable', 'quizables');
    }

    public function topics()
    {
        return $this->morphedByMany(Topic::class, 'quizable', 'quizables');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function submissions()
    {
        return $this->hasMany(QuizSubmission::class);
    }

    //     For return $this->morphedByMany(Course::class, 'quizable', 'quizables');, here's what each parameter does:

    // a) Course::class
    // This specifies the related model. In this case, the Quiz is related to the Course model. Laravel will automatically resolve the class name to App\Models\Course if you're using default namespaces.

    // b) quizable
    // This is the morph name used in the pivot table. It tells Laravel to look for:

    // quizable_id - the ID of the related model (e.g., the id of a Course).
    // quizable_type - the fully qualified class name of the related model (e.g., App\Models\Course).
    // This column pair (quizable_id and quizable_type) enables polymorphism, as they allow the same pivot table (quizables) to store relationships with different types of models.

    // c) quizables
    // This specifies the pivot table name. Here, the pivot table is quizables, which connects quizzes to multiple related models.

}
