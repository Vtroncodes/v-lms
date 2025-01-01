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

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
