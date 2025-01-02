<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    // Specify which columns are mass assignable
    protected $fillable = [
        'contentable_id',
        'contentable_type',
        'type',
        'content',
        'media_url',
        'file_url',
        'file_type',
        'order',
    ];

    /**
     * Get the parent contentable model (Topic, Lesson, etc.).
     */
    public function contentable()
    {
        return $this->morphTo();
    }

    /**
     * Accessor to format the content properly, if needed.
     */
    public function getFormattedContentAttribute()
    {
        // Example: If you want to process or modify the content in a special way before returning
        return nl2br($this->content);  // Example: Convert line breaks to HTML <br> tags
    }

    
}
