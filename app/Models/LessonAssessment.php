<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonAssessment extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'questions'];

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class);
    }
}

