<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    // Add this to allow mass assignment
    protected $fillable = [
        'attachmentable_type',
        'attachmentable_id',
        'file_url',
        'file_type',
    ];

    // Define the polymorphic relationship (if necessary)
    public function attachmentable()
    {
        return $this->morphTo();
    }
}
