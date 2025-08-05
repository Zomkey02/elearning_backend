<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'thumbnail',
        'duration',
        'views',
        'author_id',
        'status'
    ];

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    protected static function booted()
    {
        static::deleting(function ($course) {
            $course->lessons()->delete();
        });
    }
}
