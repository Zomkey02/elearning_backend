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
        'thumbnail',
        'duration',
        'level',
        'views',
        'author_id',
        'status',
        'layout_type',
        'course_id',
    ];

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }
        
    
}
