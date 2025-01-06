<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizzable extends Model
{
    protected $fillable = ['quiz_id', 'quizable_id', 'quizable_type'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
