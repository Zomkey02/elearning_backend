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
        'thumbnail_url',
        'duration',
        'views',
        'author_id',
        'status'
    ];

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }
}
