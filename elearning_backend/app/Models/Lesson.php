<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'thumbnail_url',
        'duration',
        'level',
        'views',
        'author_id',
        'status',
        'layout_type',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
