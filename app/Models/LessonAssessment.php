<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonAssessment extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'questions', 'time_limit','is_published'];

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class);
    }
}

