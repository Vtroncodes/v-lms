<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'assignmentable_id',
        'assignmentable_type',
    ];

    public function assignmentable()
    {
        return $this->morphTo();
    }
    public function contents()
    {
        return $this->morphMany(Content::class, 'contentable');
    }
}
